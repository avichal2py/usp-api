<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActiveStudents extends Model
{
    protected $table = 'active_students';
    public $incrementing = false;

    protected $fillable = [
        'student_id',
        'first_name',
        'middle_name',
        'last_name',
        'dob',
        'gender',
        'citizenship',
        'residential_address',
        'postal_address',
        'city',
        'nation',
        'email_address',
        'phone',
        'ec_firstname',
        'ec_lastname',
        'ec_othername',
        'ec_relationship',
        'ec_residential_address',
        'ec_city',
        'ec_nation',
        'ec_phone',
        'registration_date',
        'program_id',
        'program_name',
        'is_restricted',
    ];
}
