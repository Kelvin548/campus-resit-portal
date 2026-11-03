<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class StaffInvitation extends Model
{
    public const ROLE_ADMIN = 'admin';

    public const ROLE_CHIEF_EXAMINER = 'chief_examiner';

    /**
     * Attributes that may be assigned when creating or updating an invitation.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'role',
        'token_hash',
        'expires_at',
        'used_at',
        'revoked_at',
        'created_by',
        'accepted_user_id',
    ];

    /**
     * Convert invitation timestamps to date objects when they are read.
     * The token hash is stored as-is; hashing is done explicitly when issuing
     * an invitation so the raw invitation code can be checked consistently.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    /**
     * Administrator who issued this invitation, when applicable.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Staff account created by accepting this invitation, when applicable.
     */
    public function acceptedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accepted_user_id');
    }

    /**
     * Whether the invitation is unused, unrevoked, and still within its validity period.
     */
    public function isUsable(): bool
    {
        return $this->used_at === null
            && $this->revoked_at === null
            && $this->expires_at !== null
            && $this->expires_at->isFuture();
    }
}
