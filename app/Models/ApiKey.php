<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $fillable = ['key', 'subscription', 'quota_renewal'];

    protected $casts = [
        'quota_renewal' => 'datetime',
    ];
}
