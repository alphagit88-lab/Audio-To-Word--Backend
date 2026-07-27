<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_seconds',
        'file_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
