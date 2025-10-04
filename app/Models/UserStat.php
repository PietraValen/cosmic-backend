<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStat extends Model
{
    protected $table = 'user_stats';
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'total_classifications',
        'correct_classifications',
        'accuracy',
        'points',
        'level',
        'badges',
        'streak_days',
        'last_classification_at'
    ];

    protected $casts = [
        'accuracy' => 'decimal:4',
        'badges' => 'array',
        'last_classification_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
