<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detector extends Model
{
    protected $table = 'detectors';

    protected $fillable = [
        'name',
        'code',
        'latitude',
        'longitude',
        'location',
        'country',
        'arm_length_km',
        'status',
        'operational_since',
        'description',
        'color'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'arm_length_km' => 'decimal:2',
        'operational_since' => 'date',
    ];

    public function glitches()
    {
        return $this->hasMany(Glitch::class);
    }
}
