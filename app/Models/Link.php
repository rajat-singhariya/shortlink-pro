<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'original_url',
        'short_code',
        'type',
        'is_safe',
        'status',
        'expires_at',
        'creator_ip_hash',
        'country',
    ];

    protected $casts = [
        'is_safe' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function clicks()
    {
        return $this->hasMany(Click::class);
    }
}
