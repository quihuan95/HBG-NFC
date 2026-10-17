<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'code', 'name', 'job_title', 'department', 'email', 'phone',
        'zalo', 'facebook', 'whatsapp', 'viber', 'linkedin', 'website', 'bio', 'photo_path', 'active',
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

    public function getFacebookUrlAttribute(): ?string
    {
        $fb = trim($this->facebook ?? '');

        if (blank($fb)) {
            return 'https://www.facebook.com/hoabinhgroupofficial/';
        }

        if (str_starts_with($fb, 'http://') || str_starts_with($fb, 'https://')) {
            return $fb;
        }

        if (str_starts_with($fb, 'facebook.com/') || str_starts_with($fb, 'fb.com/') || str_starts_with($fb, 'www.facebook.com/')) {
            return 'https://'.ltrim($fb, '/');
        }

        return 'https://facebook.com/'.ltrim($fb, '@/');
    }

    public function getViberUrlAttribute(): ?string
    {
        if (blank($this->viber)) {
            return null;
        }

        $viber = trim($this->viber);

        if (str_starts_with($viber, 'http://') || str_starts_with($viber, 'https://') || str_starts_with($viber, 'viber://')) {
            return $viber;
        }

        $digits = preg_replace('/[^0-9]/', '', $viber);

        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            $digits = '84'.substr($digits, 1);
        }

        return 'viber://chat?number=%2B'.$digits;
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        if (blank($this->whatsapp)) {
            return null;
        }

        $wa = trim($this->whatsapp);

        if (str_starts_with($wa, 'http://') || str_starts_with($wa, 'https://')) {
            return $wa;
        }

        if (str_starts_with($wa, 'wa.me/')) {
            return 'https://'.$wa;
        }

        $digits = preg_replace('/[^0-9]/', '', $wa);

        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            $digits = '84'.substr($digits, 1);
        }

        return 'https://wa.me/'.$digits;
    }

    public function getLinkedinUrlAttribute(): ?string
    {
        if (blank($this->linkedin)) {
            return null;
        }

        $li = trim($this->linkedin);

        if (str_starts_with($li, 'http://') || str_starts_with($li, 'https://')) {
            return $li;
        }

        if (str_starts_with($li, 'linkedin.com/') || str_starts_with($li, 'www.linkedin.com/')) {
            return 'https://'.ltrim($li, '/');
        }

        if (str_starts_with($li, 'in/')) {
            return 'https://linkedin.com/'.ltrim($li, '/');
        }

        return 'https://linkedin.com/in/'.ltrim($li, '@/');
    }
}
