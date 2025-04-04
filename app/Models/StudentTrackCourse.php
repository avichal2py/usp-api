<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTrackCourse extends Model
{
    protected $table = 'student_track_course';

    protected $fillable = [
        'student_id',
        'course_code',
        'semester',
        'status',
        'grade',
        'registered_at',
    ];

    public $timestamps = false; // registered_at is handled by DB
}
