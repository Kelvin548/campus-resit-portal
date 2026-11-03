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
