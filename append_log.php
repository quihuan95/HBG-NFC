<?php

$logPath = 'D:\\Code\\logs\\cursor-task-log.md';

$logEntry = "\n\n## 2026-10-17 11:42 - Chuyển phần QR lên trên và đổi tên mục thông tin liên hệ thành thông tin thêm\n\n"
    . "- **Project:** TheHBG-NFC\n"
    . "- **Đã làm:**\n"
    . "  - Di chuyển khối 'Hồ sơ năng lực tập đoàn' (chứa mã QR và nút truy cập profile.hoabinh-group.com) lên trên phần liên hệ, nằm ngay bên dưới 2 nút hành động chính (Lưu danh bạ & Chia sẻ thẻ).\n"
    . "  - Đổi tiêu đề phân mục 'Thông tin liên hệ' thành 'Thông tin thêm'.\n"
    . "  - Cập nhật và chạy kiểm thử tự động toàn bộ 14/14 tests pass 100% (61 assertions).\n";

if (!file_exists(dirname($logPath))) {
    mkdir(dirname($logPath), 0777, true);
}

file_put_contents($logPath, $logEntry, FILE_APPEND | LOCK_EX);
echo "SUCCESS\n";
