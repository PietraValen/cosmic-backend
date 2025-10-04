<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlitchType extends Model
{
    protected $table = 'glitch_types';

    protected $fillable = [
        'name',
        'code',
        'description',
        'frequency_range',
        'duration_range',
        'common_causes',
        'visual_pattern',
        'color',
        'icon_name',
        'severity'
    ];

    public function glitches()
    {
        return $this->hasMany(Glitch::class);
    }
}
