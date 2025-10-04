<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Observatory extends Model
{
    protected $table = 'observatories';

    protected $fillable = [
        'name',
        'observatory_type',
        'latitude',
        'longitude',
        'location',
        'country',
        'description',
        'website',
        'color'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];
}
