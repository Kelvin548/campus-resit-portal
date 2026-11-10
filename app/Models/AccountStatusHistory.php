<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountStatusHistory extends Model
{
    protected $fillable = [
        'user_id',
        'changed_by',
        'previous_status',
        'new_status',
    ];

    protected function casts(): array
    {
        return [
            'previous_status' => 'boolean',
            'new_status' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
