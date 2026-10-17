<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => env('ADMIN_DEFAULT_EMAIL', 'admin@thehbg.com')],
            [
                'name' => 'Admin HoaBinh Group',
                'password' => Hash::make(env('ADMIN_DEFAULT_PASSWORD', 'password12345')),
            ]
        );

        \App\Models\Employee::updateOrCreate(
            ['code' => 'HBG001'],
            [
                'name' => 'Nguyễn Minh Tuấn',
                'job_title' => 'Giám đốc Điều hành',
                'department' => 'Ban Giám Đốc',
                'email' => 'tuan.nm@thehbg.com',
                'phone' => '0901234567',
                'zalo' => '0901234567',
                'viber' => '0901234567',
                'facebook' => 'https://www.facebook.com/hoabinhgroupofficial/',
                'whatsapp' => '+84901234567',
                'linkedin' => 'hoabinh-group',
                'website' => 'https://hoabinh-group.com',
                'bio' => "Hơn 10 năm kinh nghiệm quản trị và phát triển hệ sinh thái HoaBinh Group. Rất hân hạnh được kết nối cùng quý đối tác và khách hàng.",
                'active' => true,
            ]
        );
    }
}
