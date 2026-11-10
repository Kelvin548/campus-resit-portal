<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerificationPinMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentProfileController extends Controller
{
    public function show(): View
    {
        $student = auth()->user();

        return view('student.profile', ['student' => $student]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:30'],
        ]);

        $student = $request->user();
        $student->full_name = $validated['full_name'];
        $student->phone_number = $validated['phone_number'] ?? null;
        $student->save();

        return redirect()->route('student.profile.show')
            ->with('status', 'Your profile was updated.');
    }

    public function requestEmailChange(Request $request): RedirectResponse
    {
        $request->merge([
            'new_email' => strtolower(trim($request->input('new_email', ''))),
        ]);

        $student = $request->user();

        $validated = $request->validate([
            'new_email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
                Rule::unique('users', 'pending_email')->ignore($student->id),
            ],
        ]);

        $pin = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $student->pending_email = $validated['new_email'];
        $student->email_verification_code_hash = hash('sha256', $student->id . '|' . $pin);
        $student->email_verification_expires_at = now()->addMinutes(10);
        $student->email_verification_attempts = 0;
        $student->save();

        try {
            Mail::to($student->pending_email)->send(
                new EmailVerificationPinMail(
                    $pin,
                    'You requested to change the email address on your student portal account.',
                    'in 10 minutes',
                )
            );
        } catch (\Throwable $exception) {
            report($exception);

            $student->pending_email = null;
            $student->email_verification_code_hash = null;
            $student->email_verification_expires_at = null;
            $student->email_verification_attempts = 0;
            $student->save();

            return back()->with('error', 'We could not send the confirmation PIN. Please try again.');
        }

        return redirect()->route('student.profile.show')
            ->with('status', 'A confirmation PIN was sent to your new email address.');
    }

    public function confirmEmailChange(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pin' => ['required', 'digits:6'],
        ]);

        $student = $request->user();

        if (! $student->pending_email || ! $student->email_verification_code_hash) {
            return redirect()->route('student.profile.show')
                ->with('error', 'There is no pending email change to confirm.');
        }

        if (! $student->email_verification_expires_at || now()->greaterThan($student->email_verification_expires_at)) {
            $student->email_verification_code_hash = null;
            $student->email_verification_expires_at = null;
            $student->email_verification_attempts = 0;
            $student->save();

            return redirect()->route('student.profile.show')
                ->with('error', 'Your confirmation PIN has expired. Please request a new PIN.');
        }

        $submittedPinHash = hash('sha256', $student->id . '|' . $validated['pin']);

        if (! hash_equals($student->email_verification_code_hash, $submittedPinHash)) {
            $student->email_verification_attempts++;

            if ($student->email_verification_attempts >= 5) {
                $student->email_verification_code_hash = null;
                $student->email_verification_expires_at = null;
                $student->save();

                return redirect()->route('student.profile.show')
                    ->with('error', 'Too many incorrect PIN attempts. Please request a new PIN.');
            }

            $student->save();

            return redirect()->route('student.profile.show')
                ->with('error', 'The confirmation PIN is incorrect. Please try again.');
        }

        $student->email = $student->pending_email;
        $student->email_verified_at = now();
        $student->pending_email = null;
        $student->email_verification_code_hash = null;
        $student->email_verification_expires_at = null;
        $student->email_verification_attempts = 0;
        $student->save();

        return redirect()->route('student.profile.show')
            ->with('status', 'Your email address has been updated and verified.');
    }
}
