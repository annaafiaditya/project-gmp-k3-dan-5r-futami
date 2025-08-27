<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
