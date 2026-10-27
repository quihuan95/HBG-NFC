<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
                'photo_drive_id' => '1kN9pTzJ18Sk3F-hBRau3Lm0cFfpX8rjo',
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
                'photo_drive_id' => '1n8XnNL0-6shQycD2YFlaalySirt_ujlL',
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
                'photo_drive_id' => '1McP0SR1Cwp2g-r-3vWB2_T_UbW9xpCto',
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
                'photo_drive_id' => '1L4PTXKvSzkRSUjTqB7mE1c5W_wHPwcNx',
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
                'photo_drive_id' => '1ZiGFjdmEyLiUTuYX56LcW1Fns16Joyb8',
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
                'photo_drive_id' => '1faO6ZZfLepi7Caz3cRSA8rGFSbGUvYU5',
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
                'photo_drive_id' => '1Qql1yRHzMy4plvAlAXS6n1UNHSCmVqbP',
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
                'photo_drive_id' => '1ZWSqIAZLtuPu6hKw_xm05e8jtyzFpkMP',
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
                'photo_drive_id' => '1H1mAC4CmyCL9dkqMYbUUU511SSMSlN41',
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
                'photo_drive_id' => '14rz54RLgbqzzIUzvq6UUjAcAvc9AZ3wD',
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
            $photoDriveId = $data['photo_drive_id'];
            unset($data['photo_drive_id']);

            $photoPath = 'employee-photos/'.$data['code'].'.jpg';
            $response = Http::timeout(60)
                ->retry(3, 1000)
                ->get('https://drive.google.com/uc', [
                    'export' => 'download',
                    'id' => $photoDriveId,
                ]);

            if ($response->successful() && str_starts_with($response->header('Content-Type', ''), 'image/')) {
                Storage::disk('public')->put($photoPath, $response->body());
                $data['photo_path'] = $photoPath;
            } else {
                $this->command?->warn("Không tải được ảnh cho {$data['code']}; giữ nguyên ảnh hiện tại.");
            }

            Employee::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}
