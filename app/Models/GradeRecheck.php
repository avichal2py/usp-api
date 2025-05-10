<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeRecheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_code',
        'reason',
        'status',
        'old_grade',
        'new_grade',
    ];
}
