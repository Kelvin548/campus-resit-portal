<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timetable extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'exam_id',
        'course_id',
        'academic_year',
        'semester',
        'exam_date',
        'start_time',
        'end_time',
        'venue',
        'seating_capacity',
        'status',
        'published_by',
        'published_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'exam_date' => 'date',
            'seating_capacity' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Publication status helper.
     */
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * Scope to find schedule conflicts at a given venue and date.
     * Checks if requested window [startTime, endTime] overlaps with existing slots.
     */
    public function scopeConflicting(Builder $query, string $venue, string $examDate, string $startTime, string $endTime, ?int $excludeId = null): Builder
    {
        return $query->where('venue', $venue)
            ->whereDate('exam_date', $examDate)
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($sub) use ($startTime, $endTime) {
                        $sub->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });
    }

    /**
     * Associated Exam instance.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    /**
     * Course being scheduled.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Chief Examiner who published.
     */
    public function publishedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
