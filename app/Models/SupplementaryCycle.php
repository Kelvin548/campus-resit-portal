<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

class SupplementaryCycle extends Model
{
    public const PHASE_NOT_SCHEDULED = 'not_scheduled';

    public const PHASE_NOT_STARTED = 'not_started';

    public const PHASE_REGISTRATION = 'registration';

    public const PHASE_EXAM_ACCESS = 'exam_access';

    public const PHASE_CLOSED = 'closed';

    protected $fillable = [
        'name',
        'registration_starts_at',
        'registration_ends_at',
        'student_access_ends_at',
        'is_current',
        'is_open',
    ];

    protected function casts(): array
    {
        return [
            'registration_starts_at' => 'datetime',
            'registration_ends_at' => 'datetime',
            'student_access_ends_at' => 'datetime',
            'is_current' => 'boolean',
            'is_open' => 'boolean',
        ];
    }

    public static function current(): ?self
    {
        return self::query()
            ->where('is_current', true)
            ->latest('id')
            ->first();
    }

    public static function currentPhase(): string
    {
        return self::current()?->phase() ?? self::PHASE_NOT_SCHEDULED;
    }

    public function phase(?CarbonInterface $at = null): string
    {
        if (! $this->is_current
            || ! $this->registration_starts_at
            || ! $this->registration_ends_at
            || ! $this->student_access_ends_at
            || $this->registration_ends_at->lessThanOrEqualTo($this->registration_starts_at)
            || $this->student_access_ends_at->lessThanOrEqualTo($this->registration_ends_at)) {
            return self::PHASE_CLOSED;
        }

        $at ??= now();

        if ($this->is_open === false) {
            if ($at->lessThan($this->student_access_ends_at)) {
                return self::PHASE_EXAM_ACCESS;
            }
            return self::PHASE_CLOSED;
        }

        if ($at->lessThan($this->registration_starts_at)) {
            return self::PHASE_NOT_STARTED;
        }

        if ($at->lessThan($this->registration_ends_at)) {
            return self::PHASE_REGISTRATION;
        }

        if ($at->lessThan($this->student_access_ends_at)) {
            return self::PHASE_EXAM_ACCESS;
        }

        return self::PHASE_CLOSED;
    }

    public function registrationIsOpen(?CarbonInterface $at = null): bool
    {
        if ($this->is_open === false) {
            return false;
        }

        return $this->phase($at) === self::PHASE_REGISTRATION;
    }

    public function studentPortalIsOpen(?CarbonInterface $at = null): bool
    {
        return in_array($this->phase($at), [self::PHASE_REGISTRATION, self::PHASE_EXAM_ACCESS], true);
    }

    public static function registrationIsOpenNow(): bool
    {
        $current = self::current();
        if (! $current) {
            return false;
        }

        if ($current->is_open === false) {
            return false;
        }

        return $current->registrationIsOpen();
    }

    public static function studentPortalIsOpenNow(): bool
    {
        return self::current()?->studentPortalIsOpen() ?? false;
    }
}
