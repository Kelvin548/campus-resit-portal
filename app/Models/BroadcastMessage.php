<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BroadcastMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'category',
        'created_by',
        'is_active',
        'emails_sent_count',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'emails_sent_count' => 'integer',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
