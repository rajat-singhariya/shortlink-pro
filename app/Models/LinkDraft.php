<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkDraft extends Model
{
    protected $fillable = ['token', 'original_url', 'options', 'expires_at'];

    protected $casts = [
        'options' => 'array',
        'expires_at' => 'datetime',
    ];
}
