<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserClassification extends Model
{
    protected $table = 'user_classifications';

    protected $fillable = [
        'user_id',
        'glitch_id',
        'glitch_type_id',
        'confidence',
        'time_spent_seconds',
        'notes'
    ];

    protected $casts = [
        'confidence' => 'decimal:4',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function glitch()
    {
        return $this->belongsTo(Glitch::class);
    }

    public function glitchType()
    {
        return $this->belongsTo(GlitchType::class);
    }
}
