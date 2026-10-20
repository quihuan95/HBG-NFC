<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushMessage extends Model
{
    protected $fillable = [
        'title', 'body', 'target_url', 'status', 'sent_count',
        'failed_count', 'expired_count', 'sent_at',
    ];

    protected function casts(): array
    {
        return ['sent_at' => 'datetime'];
    }
}
