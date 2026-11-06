<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResitFormSubmission extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'user_id',
        'exam_type',
        'name',
        'department',
        'index_number',
        'level',
        'programme',
        'number_of_papers',
        'course_details',
        'year_of_entry',
        'year_of_completion',
        'telephone',
        'medical_report_path',
        'letter_path',
    ];
}
?>