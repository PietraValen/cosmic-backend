<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScientificDiscovery extends Model
{
    protected $table = 'scientific_discoveries';

    protected $fillable = [
        'title',
        'description',
        'discovery_date',
        'related_event_id',
        'researchers',
        'publication_url',
        'significance'
    ];

    protected $casts = [
        'discovery_date' => 'date',
        'researchers' => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(GravitationalWaveEvent::class, 'related_event_id');
    }
}
