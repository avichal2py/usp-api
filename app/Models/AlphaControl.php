<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlphaControl extends Model
{
    protected $table = 'alpha_control'; // 👈 important if your table name is not plural

    protected $fillable = ['status', 'semester'];

    public $timestamps = false; // 👈 disable if your table doesn’t have created_at/updated_at
}
