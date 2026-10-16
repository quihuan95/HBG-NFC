# THE HBG — NFC Employee Cards (Laravel 12)

Module quản lý thẻ danh thiếp NFC. **Đường dẫn công khai chính xác:** `https://your-domain.vn/{ma_nhan_vien}`, ví dụ `/HBG001`. NFC chỉ lưu URL; thông tin nhân viên được thay đổi từ trang quản trị.

## Yêu cầu
- PHP 8.2+, Composer, Laravel **12** project mới hoặc đang hoạt động; MySQL hoặc SQLite.
- Package này là **source overlay cho Laravel 12**, không gồm thư mục `vendor/` hay lõi framework.

## Cài đặt (dự án Laravel 12 mới)
```bash
composer create-project laravel/laravel the-hbg-nfc "^12.0"
cd the-hbg-nfc
# Giải nén package này và copy các thư mục app/, routes/, resources/, database/ vào project (cho phép ghi đè routes/web.php nếu project mới)
cp .env.example .env        # nếu Laravel chưa tạo .env
php artisan key:generate
# Cấu hình DB_* và APP_URL trong .env
php artisan migrate
php artisan storage:link
php artisan nfc:admin admin@thehbg.com 'TenTaiKhoanAdmin' # hệ thống sẽ hỏi mật khẩu an toàn
php artisan serve
```

- Mở `http://127.0.0.1:8000/admin/login` để đăng nhập.
- Mở `/admin/employees` để tạo nhân viên.
- Sau khi tạo, mở `/{mã nhân viên}` để xem danh thiếp công khai.
- Ghi URL tuyệt đối vào NTAG213 bằng NFC Tools, record loại **URL/URI**. In QR URL tương tự nếu cần (bản demo hiển thị URL, không phụ thuộc thư viện QR).

> **Lưu ý khi tích hợp dự án đang chạy**: `routes/web.php` trong package là file tham khảo trọn bộ; merge các route thay vì ghi đè route hiện có. Đặt route `/{code}` **cuối cùng** để không bắt nhầm đường dẫn khác. Laravel 12 mặc định có auth session middleware `web` và bảng `users` từ migration chuẩn.

## Luồng nghiệp vụ
1. Admin đăng nhập, tạo hồ sơ với `code` duy nhất; trạng thái `active` hoặc `inactive`.
2. Admin nhập họ tên, chức vụ, phòng ban, email, điện thoại công việc, Zalo, website, mô tả và ảnh (tùy chọn).
3. Admin chép URL `APP_URL/{code}` và ghi lên NFC.
4. Khách quét NFC, mở trang công khai. Nếu `inactive` trả về 404.
5. Admin sửa hồ sơ tại `/admin/employees/{id}/edit`; URL cũ giữ nguyên nếu không đổi mã.

## Quy tắc mã nhân viên
- Độ dài 2–32 ký tự, chỉ `[A-Za-z0-9_-]`.
- Không phân biệt chữ hoa/thường khi kiểm tra trùng vì server chuẩn hóa sang chữ hoa.
- Các mã bắt đầu bằng `ADMIN`, hoặc trùng đường dẫn dành riêng `login`, `logout`, `storage`, `api`, `up`, `build` bị từ chối.
- Đổi mã làm thay URL, nên cần ghi lại NFC. Mã mặc định chỉ nhập lúc tạo và **không thể sửa** từ form.

## Bảo mật & quyền riêng tư
- Chỉ admin đăng nhập mới sửa dữ liệu; xác thực session, CSRF, throttle đăng nhập.
- Tạo admin bằng lệnh artisan; không có mật khẩu hardcode hoặc đăng ký tài khoản công khai.
- Dữ liệu trên hồ sơ công khai là **công khai hoàn toàn**: chỉ nhập thông tin được nhân viên/công ty chấp thuận công bố.
- Không dùng đường dẫn NFC này để chấm công hay xác minh danh tính; URL có thể sao chép.
- File ảnh: chỉ jpg/png/webp, tối đa 2MB; lưu disk public.
- Bật HTTPS khi lên production, tắt `APP_DEBUG`, giới hạn quyền admin và sao lưu DB/ảnh.

## Danh sách file
- `routes/web.php` định tuyến.
- `app/Models/Employee.php` model.
- `app/Http/Controllers/NfcCardController.php` profile công khai.
- `app/Http/Controllers/Admin/*` login, CRUD.
- `app/Http/Requests/EmployeeRequest.php` validation.
- `app/Console/Commands/CreateNfcAdmin.php` tạo tài khoản admin.
- `database/migrations/2026_10_16_000001_create_employees_table.php` schema.
- `resources/views/...` giao diện Blade (CSS thuần, không cần npm).

## Kiểm thử thủ công
1. Chưa đăng nhập vào `/admin/employees` -> chuyển `/admin/login`.
2. Tạo nhân viên HBG001 -> `/HBG001` xem được; nhập HTML vào mô tả -> được escape.
3. Tạo HBG001 lần nữa -> báo trùng.
4. Sửa chức vụ -> `/HBG001` hiện mới, URL không đổi.
5. Chuyển inactive -> `/HBG001` 404.
6. Thử upload PDF hoặc ảnh >2MB -> bị từ chối.
7. Quét NFC trên thiết bị thực tế -> truy cập đúng URL HTTPS.

## Giới hạn bản MVP
Một tài khoản admin loại cơ bản; chưa có phân vai admin nhiều cấp, audit log, trang QR sinh tự động, import Excel, analytics, hoặc xác thực chống sao chép NFC.
