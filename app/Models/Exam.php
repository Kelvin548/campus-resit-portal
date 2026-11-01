<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    public const TYPE_RESIT = 'resit';
    public const TYPE_REGULAR = 'regular';
    public const TYPE_SUPPLEMENTARY_QUIZ = 'supplementary_quiz';

    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'course_id',
        'title',
        'academic_year',
        'semester',
        'exam_type',
        'duration_minutes',
        'total_marks',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'total_marks' => 'integer',
        ];
    }

    /**
     * Associated course.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Timetable entries scheduled for this exam.
     */
    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class, 'exam_id');
    }

    /**
     * Exam results recorded for this exam.
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'exam_id');
    }
}
