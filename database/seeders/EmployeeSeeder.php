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
            ['code' => 'HBG00154', 'name' => 'Đặng Đình Thiền', 'department' => 'Sự kiện', 'job_title' => 'Chuyên viên Sales Sự kiện', 'email' => 'thien.dang@hoabinh-group.com', 'phone' => '0913381658', 'active' => true],
            ['code' => 'HBG00145', 'name' => 'Đinh Tuấn Hải', 'department' => 'Sự kiện', 'job_title' => 'Chuyên viên Sales Sự kiện', 'email' => 'hai.dinh@hoabinh-group.com', 'phone' => '0913886911', 'active' => true],
            ['code' => 'HBG00346', 'name' => 'Phan Thành Hưng', 'department' => 'Sự kiện', 'job_title' => 'Chuyên viên Sales Sự kiện', 'email' => 'av1@hoabinh-group.com', 'phone' => '0987196526', 'active' => true],
            ['code' => 'HBG01097', 'name' => 'Lê Văn Dũng', 'department' => 'Sự kiện', 'job_title' => 'Chuyên viên Sales sự kiện', 'email' => 'event28@hoabinh-group.com', 'phone' => '0939100608', 'active' => true],
            ['code' => 'HBG00105', 'name' => 'Vũ Thị Phương', 'department' => 'Hội nghị', 'job_title' => 'Trưởng phòng Hội Nghị', 'email' => 'phuong.vu@hoabinhtourist.com', 'phone' => '0913188911', 'active' => true],
            ['code' => 'HBG00079', 'name' => 'Phan Tiến Quyết', 'department' => 'Hội nghị', 'job_title' => null, 'email' => 'events@hoabinhtourist.com', 'phone' => '0918640988', 'active' => true],
            ['code' => 'HBG00106', 'name' => 'Thái Thị Anh', 'department' => 'Sales Hội nghị', 'job_title' => 'Chuyên viên Sales Hội nghị', 'email' => 'haianh@hoabinhtourist.com', 'phone' => '0913321816', 'active' => true],
            ['code' => 'HBG00108', 'name' => 'Lưu Thúy Quyên', 'department' => 'Sales Hội nghị', 'job_title' => 'Chuyên viên Sales Thiết bị', 'email' => 'sales6@hoabinh-group.com', 'phone' => '0912186829', 'active' => true],
            ['code' => 'HBG00107', 'name' => 'Mai Thị Hường', 'department' => 'Sales Hội nghị', 'job_title' => 'Chuyên viên Sales Thiết bị', 'email' => 'huong.mai@hoabinhtourist.com', 'phone' => '0916216829', 'active' => true],
            ['code' => 'HBG00109', 'name' => 'Phạm Thị Lệ Thu', 'department' => 'Sales Hội nghị', 'job_title' => 'Chuyên viên Sales Thiết bị', 'email' => 'lethu@hoabinh-group.com', 'phone' => '0919136829', 'active' => true],
            ['code' => 'HBG00135', 'name' => 'Nguyễn Thị Thắm', 'department' => 'Sales Tour', 'job_title' => 'Nhân viên Sales Tour', 'email' => 'tour5@hoabinhtourist.com', 'phone' => '0903216936', 'active' => true],
            ['code' => 'HBG00965', 'name' => 'Nguyễn Văn Long', 'department' => 'Sales Tour', 'job_title' => 'Nhân viên sale tour', 'email' => 'outbound@hoabinhtourist.com', 'phone' => '0903278136', 'active' => true],
            ['code' => 'HBG00132', 'name' => 'Đỗ Quốc Huy', 'department' => 'Sales Tour', 'job_title' => 'Nhân viên sale tour', 'email' => 'huy.do@hoabinhtourist.com', 'phone' => '0967141486', 'active' => true],
            ['code' => 'HBG00121', 'name' => 'Nguyễn Hồng Nhung', 'department' => 'Sales Vé', 'job_title' => 'Trưởng bộ phận Sales Vé', 'email' => 'vemaybay@hoabinhtourist.com', 'phone' => '0903227736', 'active' => true],
            ['code' => 'HBG00122', 'name' => 'Nguyễn Mai Anh', 'department' => 'Sales Vé', 'job_title' => 'Chuyên viên Sales Vé', 'email' => 'ticket3@hoabinhtourist.com', 'phone' => '0903278538', 'active' => true],
            ['code' => 'HBG00124', 'name' => 'Nguyễn Thị Hương Trầm', 'department' => 'Xe', 'job_title' => 'Trưởng bộ phận Sales kiêm Điều hành Xe', 'email' => 'trans@hoabinhtourist.com', 'phone' => '0913130155', 'active' => true],
            ['code' => 'HBG00112', 'name' => 'Bạch Thu Hường', 'department' => 'Sự kiện', 'job_title' => 'Chuyên viên Sale Sự kiện', 'email' => 'thuhuong.bach@hoabinh-group.com', 'phone' => '0913391816', 'active' => true],
            ['code' => 'HBG00162', 'name' => 'Đinh Thị Thanh Nhàn', 'department' => 'Ban Giám đốc', 'job_title' => 'Giám đốc VP Đà Nẵng', 'email' => 'nhandt@hoabinhtourist.com', 'phone' => '0913186829', 'active' => true],
            ['code' => 'HBG00169', 'name' => 'Võ Thành Đồng', 'department' => 'Sales Sự kiện', 'job_title' => 'Phó phòng Kinh doanh Sự kiện - Thiết bị', 'email' => 'sales.pro1@hoabinh-group.com', 'phone' => '0865243168', 'active' => true],
            ['code' => 'HBG00555', 'name' => 'Đinh Tiến Chiến', 'department' => 'Sales Sự kiện', 'job_title' => 'Nhân viên Sales Sự Kiện', 'email' => 'event2@hoabinh-group.com', 'phone' => '0913991648', 'active' => true],
            ['code' => 'HBG00991', 'name' => 'Hà Điền Liên Hương', 'department' => 'Sales Sự kiện', 'job_title' => 'Nhân viên Sales Sự kiện', 'email' => 'event10.dn@hoabinh-group.com', 'phone' => '0903260933', 'active' => true],
            ['code' => 'HBG01118', 'name' => 'Nguyễn Mạnh Dũng', 'department' => 'Sales Sự kiện', 'job_title' => 'Nhân viên Sales Sự kiện', 'email' => 'event15.dn@hoabinh-group.com', 'phone' => '0913331226', 'active' => true],
            ['code' => 'HBG01158', 'name' => 'Trần Bình Minh', 'department' => 'Sự kiện', 'job_title' => 'Nhân viên Sale Sự kiện', 'email' => 'event9.dn@hoabinh-group.com', 'phone' => '0916930300', 'active' => true],
            ['code' => 'HBG00430', 'name' => 'Lê Kim Nhân', 'department' => 'Sales Tour', 'job_title' => 'Phó phòng Kinh doanh Tour', 'email' => 'travel.dn@hoabinhtourist.com', 'phone' => '0886803099', 'active' => true],
            ['code' => 'HBG00200', 'name' => 'Đổng Mạnh Huy', 'department' => 'Sales Tour', 'job_title' => 'Nhân viên Sales Tour', 'email' => 'huy.dong@hoabinhtourist.com', 'phone' => '0913971829', 'active' => true],
            ['code' => 'HBG01536', 'name' => 'Đặng Ngọc Thịnh', 'department' => 'Sales Tour', 'job_title' => 'Nhân viên Sales Tour', 'email' => 'travel.dn@hoabinh-group.com', 'phone' => '0913419911', 'active' => true],
            ['code' => 'HBG00223', 'name' => 'Lê Thành Dốn', 'department' => 'Ban Giám đốc', 'job_title' => 'Phó Giám đốc chi nhánh', 'email' => 'thanh@hoabinh-group.com', 'phone' => '0903299446', 'active' => true],
            ['code' => 'HBG00720', 'name' => 'Ngô Phước Trung', 'department' => 'Sales Sự kiện', 'job_title' => 'Chuyên viên Sales Sự kiện', 'email' => 'event3.hcm@hoabinh-group.com', 'phone' => '0903235069', 'active' => true],
            ['code' => 'HBG00832', 'name' => 'Phạm Tải Diễm My', 'department' => 'Sales Sự kiện', 'job_title' => 'Nhân viên Sales Sự kiện', 'email' => 'event9.hcm@hoabinh-group.com', 'phone' => '0973591520', 'active' => true],
            ['code' => 'HBG00764', 'name' => 'Nguyễn Thế Nhả Bình', 'department' => 'Sales Sự kiện', 'job_title' => 'Nhân viên Sales Sự kiện', 'email' => 'hn3.hcm.hbg@gmail.com', 'phone' => '0903259328', 'active' => true],
            ['code' => 'HBG00222', 'name' => 'Đỗ Thị Kim Trung', 'department' => 'Tour', 'job_title' => 'Trưởng Bộ phận Điều hành Tour', 'email' => 'travel.hcm@hoabinhtourist.com', 'phone' => '0903286438', 'active' => true],
            ['code' => 'HBG00368', 'name' => 'Giãng Huỳnh Như', 'department' => 'Tour', 'job_title' => 'Nhân viên Sales Tour Mice', 'email' => 'event29.hcm@hoabinh-group.com', 'phone' => '0889984753', 'active' => true],
            ['code' => 'HBG01303', 'name' => 'Nguyễn Thị Thanh Thảo', 'department' => 'Sales Sự kiện', 'job_title' => 'Nhân viên Sales sự kiện', 'email' => 'event36.hcm@hoabinh-group.com', 'phone' => null, 'active' => false],
        ];

        $photoExtensions = [
            'HBG00105' => 'jpg',
            'HBG00106' => 'jpg',
            'HBG00107' => 'jpg',
            'HBG00108' => 'jpg',
            'HBG00109' => 'jpg',
            'HBG00112' => 'jpg',
            'HBG00121' => 'jpg',
            'HBG00122' => 'jpg',
            'HBG00124' => 'png',
            'HBG00132' => 'jpg',
            'HBG00135' => 'jpg',
            'HBG00145' => 'jpg',
            'HBG00154' => 'jpg',
            'HBG00346' => 'jpg',
            'HBG00965' => 'jpg',
            'HBG01097' => 'jpg',
        ];

        foreach ($employees as $employee) {
            if (isset($photoExtensions[$employee['code']])) {
                $employee['photo_path'] = 'employee-photos/'.$employee['code'].'.'.$photoExtensions[$employee['code']];
            }

            Employee::updateOrCreate(
                ['code' => $employee['code']],
                $employee,
            );
        }
    }
}
