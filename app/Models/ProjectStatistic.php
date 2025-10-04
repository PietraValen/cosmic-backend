<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectStatistic extends Model
{
    protected $table = 'project_statistics';

    protected $fillable = [
        'stat_key',
        'stat_value'
    ];

    protected $casts = [
        'stat_value' => 'array',
    ];
}
