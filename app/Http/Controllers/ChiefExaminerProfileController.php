<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerificationPinMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ChiefExaminerProfileController extends Controller
{
    public function show(): View
    {
        return view('chief-examiner.profile', ['examiner' => auth()->user()]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:30'],
        ]);

        $examiner = $request->user();
        $examiner->full_name = $validated['full_name'];
        $examiner->phone_number = $validated['phone_number'] ?? null;
        $examiner->save();

        return redirect()->route('chief-examiner.profile.show')
            ->with('status', 'Your profile was updated.');
    }

    public function requestEmailChange(Request $request): RedirectResponse
    {
        $request->merge([
            'new_email' => strtolower(trim($request->input('new_email', ''))),
        ]);

        $examiner = $request->user();

        $validated = $request->validate([
            'new_email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
                Rule::unique('users', 'pending_email')->ignore($examiner->id),
            ],
        ]);

        $pin = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $examiner->pending_email = $validated['new_email'];
        $examiner->email_verification_code_hash = hash('sha256', $examiner->id . '|' . $pin);
        $examiner->email_verification_expires_at = now()->addMinutes(10);
        $examiner->email_verification_attempts = 0;
        $examiner->save();

        try {
            Mail::to($examiner->pending_email)->send(
                new EmailVerificationPinMail(
                    $pin,
                    'You requested to change the email address on your Chief Examiner portal account.',
                    'in 10 minutes',
                )
            );
        } catch (\Throwable $exception) {
            report($exception);

            $examiner->pending_email = null;
            $examiner->email_verification_code_hash = null;
            $examiner->email_verification_expires_at = null;
            $examiner->email_verification_attempts = 0;
            $examiner->save();

            return back()->with('error', 'We could not send the confirmation PIN. Please try again.');
        }

        return redirect()->route('chief-examiner.profile.show')
            ->with('status', 'A confirmation PIN was sent to your new email address.');
    }

    public function confirmEmailChange(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pin' => ['required', 'digits:6'],
        ]);

        $examiner = $request->user();

        if (! $examiner->pending_email || ! $examiner->email_verification_code_hash) {
            return redirect()->route('chief-examiner.profile.show')
                ->with('error', 'There is no pending email change to confirm.');
        }

        if (! $examiner->email_verification_expires_at || now()->greaterThan($examiner->email_verification_expires_at)) {
            $examiner->email_verification_code_hash = null;
            $examiner->email_verification_expires_at = null;
            $examiner->email_verification_attempts = 0;
            $examiner->save();

            return redirect()->route('chief-examiner.profile.show')
                ->with('error', 'Your confirmation PIN has expired. Please request a new PIN.');
        }

        $submittedPinHash = hash('sha256', $examiner->id . '|' . $validated['pin']);

        if (! hash_equals($examiner->email_verification_code_hash, $submittedPinHash)) {
            $examiner->email_verification_attempts++;

            if ($examiner->email_verification_attempts >= 5) {
                $examiner->email_verification_code_hash = null;
                $examiner->email_verification_expires_at = null;
                $examiner->save();

                return redirect()->route('chief-examiner.profile.show')
                    ->with('error', 'Too many incorrect PIN attempts. Please request a new PIN.');
            }

            $examiner->save();

            return redirect()->route('chief-examiner.profile.show')
                ->with('error', 'The confirmation PIN is incorrect. Please try again.');
        }

        $examiner->email = $examiner->pending_email;
        $examiner->email_verified_at = now();
        $examiner->pending_email = null;
        $examiner->email_verification_code_hash = null;
        $examiner->email_verification_expires_at = null;
        $examiner->email_verification_attempts = 0;
        $examiner->save();

        return redirect()->route('chief-examiner.profile.show')
            ->with('status', 'Your email address has been updated and verified.');
    }
}
