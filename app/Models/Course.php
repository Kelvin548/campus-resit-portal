<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'course_code',
        'course_name',
        'credit_hours',
        'level',
        'semester',
        'registration_fee',
        'registration_start',
        'registration_deadline',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'credit_hours' => 'integer',
            'level' => 'integer',
            'registration_fee' => 'decimal:2',
            'registration_start' => 'datetime',
            'registration_deadline' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Helper to verify if course registration window is currently open.
     */
    public function isRegistrationOpen(): bool
    {
        if (! $this->is_active || ! $this->registration_start || ! $this->registration_deadline) {
            return false;
        }

        $now = now();

        return $now->greaterThanOrEqualTo($this->registration_start)
            && $now->lessThan($this->registration_deadline);
    }

    /**
     * Whether at least one active supplementary course currently accepts registration.
     */
    public static function hasOpenRegistrationWindow(): bool
    {
        $now = now();

        return self::query()
            ->where('is_active', true)
            ->whereNotNull('registration_start')
            ->where('registration_start', '<=', $now)
            ->whereNotNull('registration_deadline')
            ->where('registration_deadline', '>', $now)
            ->exists();
    }

    /**
     * Registrations associated with this course.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Exams scheduled for this course.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Timetable entries for this course.
     */
    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }
}
