<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'visitor_id',
        'session_id',
        'ip_address',
        'country',
        'city',
        'browser',
        'os',
        'device',
        'url',
        'referrer',
        'traffic_source'
    ];
}
