<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResitRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'department',
        'index_number',
        'level',
        'programme',
        'paper_count',
        'course_entries', // JSON
        'year_entry',
        'year_completion',
        'telephone',
        'exam_type',
        'medical_report_path',
        'letter_path',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'course_entries' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
