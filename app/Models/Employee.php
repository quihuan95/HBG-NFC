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
}
