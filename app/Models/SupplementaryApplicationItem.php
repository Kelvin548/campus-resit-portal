<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplementaryApplicationItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'supplementary_application_id',
        'course_id',
        'semester',
        'academic_year',
    ];

    /**
     * Relationship: belongs to a supplementary application.
     */
    public function supplementaryApplication()
    {
        return $this->belongsTo(SupplementaryApplication::class);
    }

    /**
     * Relationship: belongs to a course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
?>