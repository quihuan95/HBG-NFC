<?php

$logPath = 'D:\\Code\\logs\\cursor-task-log.md';

$logEntry = "\n\n## 2026-10-19 17:49 - Tạo trang đăng nhập riêng biệt cho phân hệ quản trị /admin/minh\n\n"
    . "- **Project:** TheHBG-NFC\n"
    . "- **Đã làm:**\n"
    . "  - Tạo Controller `MinhAuthController` phụ trách đăng nhập (`showLogin`, `login`) và đăng xuất (`logout`) dành riêng cho phân hệ cá nhân tại `/admin/minh/login` và `/admin/minh/logout`.\n"
    . "  - Thiết kế giao diện đăng nhập riêng biệt `resources/views/admin/minh_cards/login.blade.php` với phong cách cá nhân hiện đại (dark theme, glassmorphism), tách biệt hoàn toàn nhận diện thương hiệu HBG.\n"
    . "  - Cập nhật middleware định tuyến tại `bootstrap/app.php` tự động phân luồng: truy cập trang `/admin/minh*` khi chưa đăng nhập sẽ chuyển hướng về `/admin/minh/login`, còn các trang admin công ty chuyển về `/admin/login`.\n"
    . "  - Bổ sung 5 kịch bản kiểm thử Feature trong `MinhCardTest` bao quát toàn bộ luồng đăng nhập, đăng xuất, chuyển hướng khách và người dùng; toàn bộ 27/27 tests pass 100% (114 assertions).\n";

if (!file_exists(dirname($logPath))) {
    mkdir(dirname($logPath), 0777, true);
}

file_put_contents($logPath, $logEntry, FILE_APPEND | LOCK_EX);
echo "LOGGED_SUCCESSFULLY\n";
