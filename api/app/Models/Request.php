<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $table = 'requests';
    
    protected $fillable = [
        'pod_id',
        'process_id',
        'timestamp'
    ];

    protected $casts = [
        'executed_at' => 'datetime',
        'timestamp' => 'datetime'
    ];
}
