<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registration extends Model
{
    use HasFactory;

    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_PAID = 'paid';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'semester',
        'academic_year',
        'status',
        'approval_remarks',
        'approved_by',
        'approved_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Status checkers.
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isPendingPayment(): bool
    {
        return $this->status === self::STATUS_PENDING_PAYMENT;
    }

    /**
     * Student owning this registration.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Course registered for.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Chief Examiner who reviewed/approved.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Associated primary payment.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'registration_id')->latestOfMany();
    }

    /**
     * All payment attempts.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'registration_id');
    }

    /**
     * Academic results associated with this registration.
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'registration_id');
    }
}
