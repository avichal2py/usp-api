<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'course_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'course_code', 'program_id', 'course_name', 'description', 'level', 'semester'
    ];

    public function prerequisites()
    {
        return $this->hasMany(Prerequisite::class, 'course_code', 'course_code');
    }
}
