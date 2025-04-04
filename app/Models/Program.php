<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use HasFactory;

    // Primary key is not 'id'
    protected $primaryKey = 'program_id';

    // If you don't have created_at / updated_at columns
    public $timestamps = false;

    // Fillable fields
    protected $fillable = [
        'program_id',
        'program_name',
        'registration_status', // ← make sure this is fillable
    ];

    // Default attributes
    protected $attributes = [
        'registration_status' => false,
    ];
}
