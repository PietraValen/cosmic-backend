<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Glitch extends Model
{
    protected $table = 'glitches';

    protected $fillable = [
        'detector_id',
        'glitch_type_id',
        'timestamp',
        'peak_frequency',
        'snr',
        'duration',
        'confidence',
        'classification_method',
        'spectrogram_url',
        'notes',
        'validated',
        'validated_by',
        'validated_at'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'peak_frequency' => 'decimal:2',
        'snr' => 'decimal:4',
        'duration' => 'decimal:4',
        'confidence' => 'decimal:4',
        'validated' => 'boolean',
        'validated_at' => 'datetime',
    ];

    public function detector()
    {
        return $this->belongsTo(Detector::class);
    }

    public function glitchType()
    {
        return $this->belongsTo(GlitchType::class);
    }

    public function userClassifications()
    {
        return $this->hasMany(UserClassification::class, 'glitch_id');
    }

    public function validatedByUser()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
