<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function showRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetCode(Request $request): RedirectResponse
    {
        $request->merge(['email' => strtolower(trim($request->input('email', '')))]);
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user) {
            return redirect()->route('password.verify.form', ['email' => $validated['email']])
                ->with('status', $this->genericSentMessage());
        }

        $pin = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($pin),
                'created_at' => now(),
                'attempts' => 0,
            ]
        );

        try {
            Mail::to($user->email)->send(new PasswordResetMail($user, $pin));
        } catch (\Throwable $exception) {
            report($exception);
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'We could not send the reset code. Please try again later.']);
        }

        return redirect()->route('password.verify.form', ['email' => $user->email])
            ->with('status', $this->genericSentMessage());
    }

    public function showVerificationForm(Request $request): View
    {
        return view('auth.verify-password-reset', [
            'email' => old('email', $request->query('email')),
        ]);
    }

    public function verifyResetCode(Request $request): RedirectResponse
    {
        $request->merge(['email' => strtolower(trim($request->input('email', '')))]);
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'pin' => ['required', 'digits:6'],
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->first();

        $expiresAt = $record?->created_at
            ? Carbon::parse($record->created_at)->addMinutes(10)
            : null;

        if (! $record || ! $expiresAt || now()->greaterThan($expiresAt)) {
            if ($record) {
                DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();
            }

            return back()->withInput($request->only('email'))
                ->withErrors(['pin' => 'This reset code is invalid or expired. Request a new code and try again.']);
        }

        if (($record->attempts ?? 0) >= 5) {
            DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

            return back()->withInput($request->only('email'))
                ->withErrors(['pin' => 'Too many incorrect attempts. Request a new reset code.']);
        }

        if (! Hash::check($validated['pin'], $record->token)) {
            $attempts = (int) $record->attempts + 1;

            if ($attempts >= 5) {
                DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

                return back()->withInput($request->only('email'))
                    ->withErrors(['pin' => 'Too many incorrect attempts. Request a new reset code.']);
            }

            DB::table('password_reset_tokens')
                ->where('email', $validated['email'])
                ->update(['attempts' => $attempts]);

            return back()->withInput($request->only('email'))
                ->withErrors(['pin' => 'That reset code is incorrect. Please try again.']);
        }

        DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();
        $request->session()->put([
            'password_reset_verified_email' => $validated['email'],
            'password_reset_verified_at' => now()->timestamp,
        ]);

        return redirect()->route('password.reset');
    }

    public function showResetForm(Request $request): View|RedirectResponse
    {
        $email = $this->verifiedEmail($request);

        if (! $email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Verify a reset code before choosing a new password.']);
        }

        return view('auth.reset-password', ['email' => $email]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $email = $this->verifiedEmail($request);

        if (! $email) {
            $this->clearResetSession($request);

            return redirect()->route('password.request')
                ->withErrors(['email' => 'Your reset verification expired. Request a new code.']);
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $email)->firstOrFail();
        $user->password = $validated['password'];
        $user->remember_token = Str::random(60);
        $user->save();

        $this->clearResetSession($request);

        $signInRoute = $user->isStudent() ? 'login' : 'staff.login';

        return redirect()->route($signInRoute)
            ->with('status', 'Your password has been successfully reset! You can now sign in with your new password.');
    }

    private function verifiedEmail(Request $request): ?string
    {
        $email = $request->session()->get('password_reset_verified_email');
        $verifiedAt = $request->session()->get('password_reset_verified_at');

        if (! is_string($email) || ! is_numeric($verifiedAt) || now()->timestamp - (int) $verifiedAt > 600) {
            return null;
        }

        return $email;
    }

    private function clearResetSession(Request $request): void
    {
        $request->session()->forget([
            'password_reset_verified_email',
            'password_reset_verified_at',
        ]);
    }

    private function genericSentMessage(): string
    {
        return 'If an account exists for that email, a reset code has been sent. Check your inbox and spam folder.';
    }
}
