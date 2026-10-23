<?php

$logPath = 'D:\\Code\\logs\\cursor-task-log.md';

$logEntry = "\n\n## 2026-10-23 09:04 - Thêm checkbox chọn nhân viên để xuất mã QR hàng loạt\n\n"
    . "- **Project:** TheHBG-NFC\n"
    . "- **Đã làm:**\n"
    . "  - Bổ sung cột checkbox trong bảng danh sách nhân viên (`index.blade.php`), hỗ trợ nút 'Chọn tất cả' (Select All) và tính năng bỏ chọn.\n"
    . "  - Hiển thị thanh thông báo số lượng đang chọn và tự động đổi trạng thái nút thành `⬇ Xuất QR đã chọn (ZIP) (X)` khi có checkbox được tích chọn.\n"
    . "  - Cập nhật route và controller `exportQrZip` tiếp nhận danh sách `ids` gửi lên để đóng gói ZIP chính xác những nhân viên được chọn; bổ sung 2 test case trong `QrCodeTest`, toàn bộ 43/43 tests pass 100%.\n";

if (!file_exists(dirname($logPath))) {
    mkdir(dirname($logPath), 0777, true);
}

file_put_contents($logPath, $logEntry, FILE_APPEND | LOCK_EX);
echo "LOGGED_SUCCESSFULLY\n";
