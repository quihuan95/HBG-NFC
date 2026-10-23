<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'code' => 'HBG00132',
                'name' => 'Đỗ Quốc Huy',
                'job_title' => 'Nhân viên sale tour',
                'department' => 'Kinh Doanh',
                'email' => 'huy.do@hoabinhtourist.com',
                'phone' => '0974792975',
                'zalo' => '0974792975',
                'website' => 'https://hoabinhtourist.com',
                'active' => true,
            ],
            [
                'code' => 'HBG00108',
                'name' => 'Lưu Thúy Quyên',
                'job_title' => 'Nhân viên Sale Thiết Bị',
                'department' => 'Kinh Doanh Thiết Bị',
                'email' => 'sales6@hoabinh-group.com',
                'phone' => '0912186829',
                'zalo' => '0912186829',
                'website' => 'https://hoabinh-group.com',
                'active' => true,
            ],
            [
                'code' => 'HBG00107',
                'name' => 'Mai Thị Hường',
                'job_title' => 'Nhân viên Sale thiết bị',
                'department' => 'Kinh Doanh Thiết Bị',
                'email' => 'huong.mai@hoabinhtourist.com',
                'phone' => '0916216829',
                'zalo' => '0916216829',
                'website' => 'https://hoabinhtourist.com',
                'active' => true,
            ],
            [
                'code' => 'HBG00135',
                'name' => 'Nguyễn Thị Thắm',
                'job_title' => 'Nhân viên sale Tour',
                'department' => 'Kinh Doanh Tour',
                'email' => 'tour5@hoabinhtourist.com',
                'phone' => '0903216936',
                'zalo' => '0903216936',
                'website' => 'https://hoabinhtourist.com',
                'bio' => 'Hotline phụ: 037891313',
                'active' => true,
            ],
            [
                'code' => 'HBG00106',
                'name' => 'Thái Thị Anh',
                'job_title' => 'Nhân viên Sale',
                'department' => 'Kinh Doanh',
                'email' => 'haianh@hoabinhtourist.com',
                'phone' => '0913321816',
                'zalo' => '0913321816',
                'website' => 'https://hoabinhtourist.com',
                'active' => true,
            ],
            [
                'code' => 'HBG00105',
                'name' => 'Vũ Thị Phương',
                'job_title' => 'Sales Manager',
                'department' => 'Kinh Doanh',
                'email' => 'phuong.vu@hoabinhtourist.com',
                'phone' => '0913188911',
                'zalo' => '0913188911',
                'website' => 'https://hoabinhtourist.com',
                'active' => true,
            ],
            [
                'code' => 'HBG00154',
                'name' => 'Đặng Đình Thiền',
                'job_title' => 'Chuyên viên Sale',
                'department' => 'Kinh Doanh',
                'email' => 'thien.dang@hoabinh-group.com',
                'phone' => '0913381658',
                'zalo' => '0913381658',
                'website' => 'https://hoabinh-group.com',
                'active' => true,
            ],
            [
                'code' => 'HBG00112',
                'name' => 'Bạch Thu Hường',
                'job_title' => 'Nhân viên Sale',
                'department' => 'Kinh Doanh',
                'email' => 'thuhuong.bach@hoabinh-group.com',
                'phone' => '0913391816',
                'zalo' => '0913391816',
                'website' => 'https://hoabinh-group.com',
                'active' => true,
            ],
            [
                'code' => 'HBG01097',
                'name' => 'Lê Văn Dũng',
                'job_title' => 'Nhân viên Sale',
                'department' => 'Kinh Doanh',
                'email' => 'event28@hoabinh-group.com',
                'phone' => '0939100608',
                'zalo' => '0939100608',
                'website' => 'https://hoabinh-group.com',
                'active' => true,
            ],
            [
                'code' => 'HBG00145',
                'name' => 'Đinh Tuấn Hải',
                'job_title' => 'Nhân viên Sale',
                'department' => 'Kinh Doanh',
                'email' => 'hai.dinh@hoabinh-group.com',
                'phone' => '0903216258',
                'zalo' => '0903216258',
                'website' => 'https://hoabinh-group.com',
                'active' => true,
            ],
        ];

        foreach ($employees as $data) {
            Employee::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}
