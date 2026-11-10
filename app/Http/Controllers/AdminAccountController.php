<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use App\Mail\EmailVerificationPinMail;
use Illuminate\Support\Facades\Mail;

class AdminAccountController extends Controller
{
    public function updateStudentStatus(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        if (! $user->isStudent()) {
            return back()->with('error', 'Only student accounts can be changed here.');
        }

        DB::transaction(function () use ($user, $validated): void {
            // Lock the row while changing it, so concurrent requests don't overwrite each other.
            $student = User::whereKey($user->id)->lockForUpdate()->firstOrFail();

            $previousStatus = $student->is_active;
            $newStatus = (bool) $validated['is_active'];

            // Don't create a history entry if the status was already the requested value.
            if ($previousStatus === $newStatus) {
                return;
            }

            $student->is_active = $newStatus;
            $student->save();

            $student->accountStatusHistory()->create([
                'changed_by' => auth()->id(),
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
            ]);
        });

        return back()->with('status', 'Student account status updated.');
    }

    public function updateStaffStatus(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $newStatus = (bool) $validated['is_active'];

        $failure = DB::transaction(function () use ($user, $newStatus): ?string {
            // Lock active Administrators so concurrent requests cannot disable the last one.
            $activeAdministratorIds = User::query()
                ->where('role', 'admin')
                ->where('is_active', true)
                ->orderBy('id')
                ->lockForUpdate()
                ->pluck('id');

            $staff = User::whereKey($user->id)->lockForUpdate()->firstOrFail();

            if (! $staff->isChiefExaminer() && ! $staff->isAdmin()) {
                return 'Only Administrator and Chief Examiner accounts can be changed here.';
            }

            if ($staff->id === auth()->id() && ! $newStatus) {
                return 'You cannot deactivate your own Administrator account.';
            }

            $previousStatus = $staff->is_active;

            if ($staff->isAdmin() && $previousStatus && ! $newStatus && $activeAdministratorIds->count() <= 1) {
                return 'At least one active Administrator account must remain.';
            }

            // Don't create a history entry if the status was already the requested value.
            if ($previousStatus === $newStatus) {
                return null;
            }

            $staff->is_active = $newStatus;
            $staff->save();

            $staff->accountStatusHistory()->create([
                'changed_by' => auth()->id(),
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
            ]);

            return null;
        });

        if ($failure !== null) {
            return back()->with('error', $failure);
        }

        return back()->with('status', 'Staff account status updated.');
    }

    public function showProfile(): View
{
        $admin = auth()->user();

        return view('admin.profile', ['admin' => $admin]);
}

    public function updateProfile(Request $request): RedirectResponse
{
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:30'],
        ]);

        $admin = $request->user();
        $admin->full_name = $validated['full_name'];
        $admin->phone_number = $validated['phone_number'] ?? null;
        $admin->save();

        return redirect()->route('admin.profile.show')
            ->with('status', 'Your profile was updated.');
}

    public function requestEmailChange(Request $request): RedirectResponse{
       $request->merge([
            'new_email' => strtolower(trim($request->input('new_email', ''))),
        ]);

        $admin = $request->user();

        $validated = $request->validate([
            'new_email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
                Rule::unique('users', 'pending_email')->ignore($admin->id),
            ],
        ]);

        $pin = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $admin->pending_email = $validated['new_email'];
        $admin->email_verification_code_hash = hash('sha256', $admin->id . '|' . $pin);
        $admin->email_verification_expires_at = now()->addMinutes(10);
        $admin->email_verification_attempts = 0;
        $admin->save();

        try {
            Mail::to($admin->pending_email)->send(
                new EmailVerificationPinMail(
                    $pin,
                    'You requested to change the email address on your portal account.',
                    'in 10 minutes',
                )
            );
        } catch (\Throwable $exception) {
            report($exception);

            $admin->pending_email = null;
            $admin->email_verification_code_hash = null;
            $admin->email_verification_expires_at = null;
            $admin->email_verification_attempts = 0;
            $admin->save();

            return back()->with('error', 'We could not send the confirmation PIN. Please try again.');
        }

        return redirect()->route('admin.profile.show')
            ->with('status', 'A confirmation PIN was sent to your new email address.');
    }


    public function confirmEmailChange(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'pin' => ['required', 'digits:6'],
    ]);
    $admin = $request->user();

    if (! $admin->pending_email || ! $admin->email_verification_code_hash) {
        return redirect()->route('admin.profile.show')
            ->with('error', 'There is no pending email change to confirm.');
    }

    if (! $admin->email_verification_expires_at || now()->greaterThan($admin->email_verification_expires_at)) {
        $admin->email_verification_code_hash = null;
        $admin->email_verification_expires_at = null;
        $admin->email_verification_attempts = 0;
        $admin->save();

        return redirect()->route('admin.profile.show')
            ->with('error', 'Your confirmation PIN has expired. Please request a new PIN.');
    }

    $submittedPinHash = hash('sha256', $admin->id . '|' . $validated['pin']);

    if (! hash_equals($admin->email_verification_code_hash, $submittedPinHash)) {
        $admin->email_verification_attempts++;

        if ($admin->email_verification_attempts >= 5) {
            $admin->email_verification_code_hash = null;
            $admin->email_verification_expires_at = null;
            $admin->save();

            return redirect()->route('admin.profile.show')
                ->with('error', 'Too many incorrect PIN attempts. Please request a new PIN.');
        }

        $admin->save();

        return redirect()->route('admin.profile.show')
            ->with('error', 'The confirmation PIN is incorrect. Please try again.');
    }

    $admin->email = $admin->pending_email;
    $admin->email_verified_at = now();
    $admin->pending_email = null;
    $admin->email_verification_code_hash = null;
    $admin->email_verification_expires_at = null;
    $admin->email_verification_attempts = 0;
    $admin->save();

    return redirect()->route('admin.profile.show')
        ->with('status', 'Your email address has been updated and verified.');

}
}
