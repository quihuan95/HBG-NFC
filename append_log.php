<?php

$logPath = 'D:\\Code\\logs\\cursor-task-log.md';

$logEntry = "\n\n## 2026-10-23 09:35 - Thêm chức năng xóa nhân viên (đơn lẻ và hàng loạt)\n\n"
    . "- **Project:** TheHBG-NFC\n"
    . "- **Đã làm:**\n"
    . "  - Bổ sung nút 'Xóa' trực tiếp tại từng dòng nhân viên kèm hộp thoại xác nhận (confirm dialog), tự động dọn dẹp file ảnh đại diện trong bộ nhớ lưu trữ.\n"
    . "  - Thêm nút '🗑 Xóa nhân viên đã chọn' trên thanh tác vụ hàng loạt khi người dùng tích chọn checkbox, xác nhận xóa nhiều người cùng lúc.\n"
    . "  - Khai báo route `employees.bulk_destroy` và phương thức `destroy`, `bulkDestroy` trong `EmployeeController`.\n"
    . "  - Bổ sung 3 test case kiểm thử bảo mật và chức năng xóa trong `NfcCardTest`, toàn bộ 46/46 tests pass 100% (172 assertions).\n";

if (!file_exists(dirname($logPath))) {
    mkdir(dirname($logPath), 0777, true);
}

file_put_contents($logPath, $logEntry, FILE_APPEND | LOCK_EX);
echo "LOGGED_SUCCESSFULLY\n";
