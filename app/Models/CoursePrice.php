<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursePrice extends Model
{
    protected $table = 'course_price';

    protected $primaryKey = 'course_code';
    public $incrementing = false; // because course_code is not auto-incremented
    public $timestamps = false;

    protected $fillable = [
        'course_code',
        'course_price',
    ];
}
