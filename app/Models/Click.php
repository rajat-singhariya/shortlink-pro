<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'link_id',
        'ip_hash',
        'country',
        'device',
        'browser',
        'referrer',
        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
