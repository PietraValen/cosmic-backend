<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GravitationalWaveEvent extends Model
{
    protected $table = 'gravitational_wave_events';

    protected $fillable = [
        'name',
        'event_date',
        'latitude',
        'longitude',
        'event_type',
        'mass_1',
        'mass_2',
        'distance_mpc',
        'spectrogram_url',
        'false_alarm_rate',
        'description',
        'significance',
        'detectors',
        'color'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'mass_1' => 'decimal:2',
        'mass_2' => 'decimal:2',
        'distance_mpc' => 'decimal:2',
        'false_alarm_rate' => 'decimal:10',
        'event_date' => 'datetime',
        'detectors' => 'array'
    ];
}
