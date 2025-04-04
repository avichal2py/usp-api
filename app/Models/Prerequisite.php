<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prerequisite extends Model
{
    protected $table = 'prerequisites';
    public $timestamps = false;

    protected $fillable = [
        'course_code', 'prereq_code'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_code', 'course_code');
    }
}
