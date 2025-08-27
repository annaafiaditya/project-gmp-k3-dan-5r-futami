<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finding extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'image2',
        'gmp_criteria',
        'department',
        'description',
        'week',
        'year',
        'auditor',
        'jenis_audit',
        'kriteria',
        'status',
    ];

    public function closing()
    {
        return $this->hasOne(Closing::class);
    }
}
