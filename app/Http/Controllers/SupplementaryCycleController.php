<?php

namespace App\Http\Controllers;

use App\Models\SupplementaryCycle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SupplementaryCycleController extends Controller
{
    public function edit(): View
    {
        $cycle = SupplementaryCycle::current();

        return view('chief-examiner.cycle-settings', [
            'cycle' => $cycle,
            'phase' => $cycle?->phase() ?? SupplementaryCycle::PHASE_NOT_SCHEDULED,
        ]);
    }

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'registration_starts_at' => ['required', 'date'],
            'registration_ends_at' => ['required', 'date', 'after:registration_starts_at'],
            'student_access_ends_at' => ['required', 'date', 'after:registration_ends_at'],
        ]);

        DB::transaction(function () use ($validated): void {
            $current = SupplementaryCycle::query()
                ->where('is_current', true)
                ->latest('id')
                ->lockForUpdate()
                ->first();

            if ($current && $current->phase() !== SupplementaryCycle::PHASE_CLOSED) {
                $current->update($validated);

                return;
            }

            SupplementaryCycle::query()
                ->where('is_current', true)
                ->update(['is_current' => false]);

            SupplementaryCycle::create($validated + ['is_current' => true]);
        });

        return redirect()->route('chief-examiner.cycle-settings.edit')->with(
            'status',
            'The supplementary examination cycle dates have been saved.'
        );
    }
}
