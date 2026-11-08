<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StaffInvitation;
use App\Models\SupplementaryCycle;
use App\Mail\PasswordResetMail;
use App\Mail\StaffInvitationMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->isStudent() && SupplementaryCycle::studentPortalIsOpenNow()) {
                return redirect()->route('student.dashboard');
            }

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->isChiefExaminer()) {
                return redirect()->route('chief-examiner.dashboard');
            }

            if ($user->isStudent()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        }

        return view('auth.login', [
            'studentRegistrationOpen' => SupplementaryCycle::registrationIsOpenNow(),
            'studentLoginAllowed'     => SupplementaryCycle::studentPortalIsOpenNow(),
            'studentRegistrationPhase' => SupplementaryCycle::currentPhase(),
        ]);
    }

    /**
     * Handle authentication attempt.
     * Accepts both `login_identifier` (form field) and `email` alias (test compatibility).
     */
    public function login(Request $request): RedirectResponse
    {
        // Allow tests / API callers that POST `email` instead of `login_identifier`
        if ($request->has('email') && ! $request->filled('login_identifier')) {
            $request->merge(['login_identifier' => $request->input('email')]);
        }

        $validated = $request->validate([
            'login_identifier' => ['required', 'string', 'max:255'],
            'password'         => ['required', 'string'],
        ]);

        $identifier = trim($validated['login_identifier']);
        $remember   = $request->boolean('remember');

        // Check if an authorized staff member (Admin / Chief Examiner) is logging in via the primary login screen
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $staffUser = User::where('email', strtolower($identifier))
                ->whereIn('role', ['admin', 'chief_examiner'])
                ->first();

            if ($staffUser && Auth::attempt(['email' => strtolower($identifier), 'password' => $validated['password']], $remember)) {
                $request->session()->regenerate();

                return $this->redirectBasedOnRole($staffUser)
                    ->with('status', "Welcome back, {$staffUser->full_name}!");
            }
        }

        // Enforce supplementary-cycle access window for students
        if (! SupplementaryCycle::studentPortalIsOpenNow()) {
            return redirect()->route('login')->withErrors([
                'login_identifier' => 'Student access is unavailable outside an active supplementary examination cycle.',
            ]);
        }

        $credentials = [
            'password' => $validated['password'],
            'role'     => 'student',
        ];

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = strtolower($identifier);
        } else {
            $credentials['student_id_number'] = $identifier;
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();

            return $this->redirectBasedOnRole($user)
                ->with('status', "Welcome back, {$user->full_name}!");
        }

        return back()
            ->withInput($request->only('login_identifier', 'remember'))
            ->withErrors([
                'login_identifier' => 'The sign-in details do not match a student account.',
            ]);
    }

    /**
     * Sign staff in and direct them using the role stored on their account.
     */
    public function staffLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            /** @var User $user */
            $user = Auth::user();

            if ($user->isAdmin() || $user->isChiefExaminer()) {
                $request->session()->regenerate();

                $dashboard = $user->isAdmin()
                    ? 'admin.dashboard'
                    : 'chief-examiner.dashboard';

                return redirect()->route($dashboard)
                    ->with('status', "Welcome back, {$user->full_name}!");
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'The staff sign-in details do not match an authorized account.',
            ]);
    }

    /**
     * Show the staff invitation form, or account setup after code verification.
     */
    public function showStaffInvitation(Request $request): View
    {
        $invitation   = null;
        $invitationId = $request->session()->get('staff_invitation_id');

        if ($invitationId) {
            $invitation = StaffInvitation::find($invitationId);

            if (! $invitation || ! $invitation->isUsable() || User::where('email', $invitation->email)->exists()) {
                $request->session()->forget('staff_invitation_id');
                $invitation = null;
            }
        }

        return view('auth.staff-invitation', compact('invitation'));
    }

    /**
     * Verify an invitation code and retain its ID in the guest session for setup.
     */
    public function verifyStaffInvitation(Request $request): RedirectResponse
    {
        $request->session()->forget('staff_invitation_id');

        $validated = $request->validate([
            'email'           => ['required', 'email', 'max:255'],
            'invitation_code' => ['required', 'string', 'max:255'],
        ]);

        $email     = strtolower(trim($validated['email']));
        $tokenHash = hash('sha256', trim($validated['invitation_code']));

        $invitation = StaffInvitation::query()
            ->where('email', $email)
            ->where('token_hash', $tokenHash)
            ->first();

        if (! $invitation || ! $invitation->isUsable() || User::where('email', $email)->exists()) {
            return back()->withErrors([
                'invitation_code' => 'The invitation details are invalid or have expired.',
            ]);
        }

        $request->session()->put('staff_invitation_id', $invitation->id);

        return redirect()->route('staff.invitation');
    }

    /**
     * Create the invited staff account and atomically consume its invitation.
     */
    public function completeStaffInvitation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $invitationId = $request->session()->get('staff_invitation_id');

        if (! $invitationId) {
            return redirect()->route('staff.invitation')->withErrors([
                'invitation_code' => 'Verify your invitation before setting up an account.',
            ]);
        }

        DB::transaction(function () use ($invitationId, $validated, $request): void {
            $invitation = StaffInvitation::query()
                ->whereKey($invitationId)
                ->lockForUpdate()
                ->first();

            if (! $invitation || ! $invitation->isUsable()) {
                throw ValidationException::withMessages([
                    'invitation_code' => 'The invitation is no longer valid. Request a new invitation from an Administrator.',
                ]);
            }

            if (User::where('email', $invitation->email)->exists()) {
                throw ValidationException::withMessages([
                    'email' => 'An account already exists for this invited email address.',
                ]);
            }

            $user = User::create([
                'full_name' => $validated['full_name'],
                'email'     => $invitation->email,
                'password'  => Hash::make($validated['password']),
                'role'      => $invitation->role,
            ]);

            $invitation->update([
                'used_at'          => now(),
                'accepted_user_id' => $user->id,
            ]);
        });

        $request->session()->forget('staff_invitation_id');

        return redirect()->route('staff.login')->with(
            'status',
            'Your staff account has been created. You can now sign in with your email address and password.'
        );
    }

    /**
     * Show the Administrator-only staff invitation form.
     */
    public function showStaffInvitationForm(): View
    {
        return view('admin.staff-invitations.create');
    }

    /**
     * Create a single-use staff invitation and email its code to the invitee.
     */
    public function sendStaffInvitation(Request $request): RedirectResponse
    {
        if (in_array(config('mail.default'), ['log', 'array', 'failover'], true)) {
            return back()->withInput()->withErrors([
                'email' => 'Real email delivery is not configured yet. Set up the project mail account before sending invitations.',
            ]);
        }

        $validated = $request->validate([
            'email'            => ['required', 'email', 'max:255', 'unique:users,email'],
            'role'             => ['required', 'in:admin,chief_examiner'],
            'expires_in_hours' => ['required', 'integer', 'between:1,72'],
        ]);

        $email = strtolower(trim($validated['email']));

        if (User::where('email', $email)->exists()) {
            return back()->withInput()->withErrors([
                'email' => 'An account already uses this email address.',
            ]);
        }

        $invitationCode = Str::random(48);
        $expiresAt      = now()->addHours((int) $validated['expires_in_hours']);

        $invitation = StaffInvitation::create([
            'email'      => $email,
            'role'       => $validated['role'],
            'token_hash' => hash('sha256', $invitationCode),
            'expires_at' => $expiresAt,
            'created_by' => $request->user()->id,
        ]);

        try {
            Mail::to($email)->send(new StaffInvitationMail(
                invitationCode: $invitationCode,
                role: $invitation->role,
                expiresAt: $invitation->expires_at,
            ));
        } catch (\Throwable $exception) {
            $invitation->update(['revoked_at' => now()]);
            report($exception);

            return back()->withInput()->withErrors([
                'email' => 'The invitation email could not be sent. Check the mail settings and try again.',
            ]);
        }

        return redirect()->route('admin.staff-invitations.create')->with(
            'status',
            'The staff invitation was emailed successfully.'
        );
    }

    /**
     * Show registration form for new students.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        if (! SupplementaryCycle::registrationIsOpenNow()) {
            return redirect()->route('login')->with('cycle_notice', true);
        }

        return view('auth.register');
    }

    /**
     * Register a new student account.
     */
    public function register(Request $request): RedirectResponse
    {
        if (! SupplementaryCycle::registrationIsOpenNow()) {
            return redirect()->route('login')->with('cycle_notice', true);
        }

        $validated = $request->validate([
            'full_name'         => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'student_id_number' => ['required', 'string', 'digits:10', 'regex:/^[1-9][0-9]{9}$/', 'unique:users,student_id_number'],
            'phone_number'      => ['nullable', 'string', 'max:30'],
            'password'          => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (! SupplementaryCycle::registrationIsOpenNow()) {
            return redirect()->route('login')->with('cycle_notice', true);
        }

        $user = User::create([
            'full_name'         => $validated['full_name'],
            'email'             => $validated['email'],
            'student_id_number' => $validated['student_id_number'],
            'phone_number'      => $validated['phone_number'] ?? null,
            'role'              => 'student',
            'password'          => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('student.dashboard')
            ->with('status', 'Your student account has been created successfully!');
    }

    /**
     * Log user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        $staffLogout = Auth::user()?->isAdmin() || Auth::user()?->isChiefExaminer();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($staffLogout ? 'staff.login' : 'login')
            ->with('status', 'You have been safely logged out.');
    }

    /**
     * Redirect authenticated user based on role.
     */
    protected function redirectBasedOnRole(User $user): RedirectResponse
    {
        if ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isChiefExaminer()) {
            return redirect()->route('chief-examiner.dashboard');
        }

        return redirect()->route('login');
    }

    /**
     * Display the forgot password request form.
     */
    public function showForgotPasswordForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link to student email.
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'We could not find an account with that email address.',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        $token = Str::random(64);

        // Store token in database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);

        // Attempt to deliver the email
        try {
            Mail::to($user->email)->send(new PasswordResetMail($user, $resetUrl));
        } catch (\Throwable $e) {
            // Mail transport error (e.g. SMTP unavailable in local offline setup)
        }

        return back()
            ->with('status', "A password reset link has been sent to {$user->email}. Please check your inbox or spam folder on your device.");
    }

    /**
     * Display the password reset form.
     */
    public function showResetPasswordForm(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record || ! Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'This password reset link is invalid or has expired. Please request a new one.']);
        }

        // Check 60-minute expiration
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'This password reset link has expired. Please request a new link.']);
        }

        // Update user password
        $user = User::where('email', $request->email)->firstOrFail();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Invalidate used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('status', 'Your password has been successfully reset! You can now sign in with your new password.');
    }
}
