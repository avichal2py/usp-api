<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    protected $fillable = [
    'type',
    'identifier',
    'status',
    'ip_address',
    'action',
];

}
