<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplementaryApplication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'status',
        'medical_report',
        'letter',
    ];

    /**
     * Relationship: a supplementary application belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: a supplementary application has many items (courses).
     */
    public function items()
    {
        return $this->hasMany(SupplementaryApplicationItem::class);
    }
}
?>