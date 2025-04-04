<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentsCourse extends Model
{
    protected $table = 'students_courses';

    protected $fillable = [
        'student_id',
        'course_code',
        'status',
    ];

    public $timestamps = false; 
}
