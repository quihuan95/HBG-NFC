<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'code', 'name', 'job_title', 'department', 'email', 'phone',
        'zalo', 'website', 'bio', 'photo_path', 'active',
    ];

    protected $casts = ['active' => 'boolean'];

    public function getZaloUrlAttribute(): ?string
    {
        if (blank($this->zalo)) {
            return null;
        }

        $zalo = trim($this->zalo);

        if (str_starts_with($zalo, 'http://') || str_starts_with($zalo, 'https://')) {
            return $zalo;
        }

        if (str_starts_with($zalo, 'zalo.me/')) {
            return 'https://'.$zalo;
        }

        $clean = preg_replace('/[^0-9A-Za-z_]/', '', $zalo);

        return 'https://zalo.me/'.$clean;
    }
}
