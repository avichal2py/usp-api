<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewStudentRegistration extends Model
{
    protected $table = 'new_student_registration';

    protected $primaryKey = 'application_id';
    public $timestamps = false;

    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'dob', 'gender', 'citizenship',
        'program_id', 'program_name', 'residential_address', 'postal_address', 'city',
        'nation', 'email_address', 'phone',
        'ec_firstname', 'ec_lastname', 'ec_othername', 'ec_relationship',
        'ec_residential_address', 'ec_city', 'ec_nation', 'ec_phone',
        'tertiary_qualification', 'registration_date'
    ];
}

