<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Services\QrCodeService;
use Illuminate\Console\Command;

class ExportEmployeeQRCodes extends Command
{
    protected $signature = 'employees:export-qr
                            {--format=png : Định dạng file xuất (png hoặc svg)}
                            {--size=400 : Kích thước ảnh QR (pixel)}
                            {--out= : Thư mục xuất file hoặc tên file zip (mặc định: data/qr_exports)}
                            {--zip : Đóng gói toàn bộ mã QR vào file ZIP}
                            {--all : Xuất toàn bộ cả nhân viên tạm ẩn (mặc định chỉ xuất nhân viên đang hoạt động)}';

    protected $description = 'Xuất hàng loạt mã QR danh thiếp nhân viên theo định dạng Tên_Chức vụ_Mã';

    public function handle(QrCodeService $qrService): int
    {
        $format = strtolower($this->option('format')) === 'svg' ? 'svg' : 'png';
        $size = (int) ($this->option('size') ?: 400);
        $exportAll = (bool) $this->option('all');
        $isZip = (bool) $this->option('zip');

        $query = Employee::query()->orderBy('id');
        if (!$exportAll) {
            $query->where('active', true);
        }

        $employees = $query->get();

        if ($employees->isEmpty()) {
            $this->warn('Không tìm thấy nhân viên nào để xuất mã QR.');
            return self::SUCCESS;
        }

        $this->info("Bắt đầu xuất mã QR cho {$employees->count()} nhân viên (Định dạng: {$format}, Size: {$size}px)...");

        $defaultDir = base_path('data/qr_exports');

        if ($isZip) {
            $outPath = $this->option('out');
            if ($outPath) {
                if (!str_ends_with(strtolower($outPath), '.zip')) {
                    if (!is_dir($outPath)) {
                        mkdir($outPath, 0777, true);
                    }
                    $outPath = rtrim($outPath, '/\\') . DIRECTORY_SEPARATOR . 'HBG_QRCodes_' . date('Ymd_His') . '.zip';
                }
            } else {
                if (!is_dir($defaultDir)) {
                    mkdir($defaultDir, 0777, true);
                }
                $outPath = $defaultDir . DIRECTORY_SEPARATOR . 'HBG_QRCodes_' . date('Ymd_His') . '.zip';
            }

            $zipFile = $qrService->exportEmployeesToZip($employees, $outPath, $format, $size);

            $this->info("✔ Đã xuất và đóng gói thành công: {$zipFile}");
            $this->line("Dung lượng file ZIP: " . round(filesize($zipFile) / 1024, 2) . " KB");

            return self::SUCCESS;
        }

        $targetDir = $this->option('out') ?: $defaultDir;
        $createdFiles = $qrService->exportEmployeesToDirectory($employees, $targetDir, $format, $size);

        $this->table(
            ['Mã NV', 'Họ tên', 'Tên file đã tạo'],
            $employees->map(function ($emp) use ($qrService, $format) {
                return [
                    $emp->code,
                    $emp->name,
                    $qrService->getEmployeeFileName($emp, $format),
                ];
            })
        );

        $this->info("✔ Đã xuất thành công " . count($createdFiles) . " file vào thư mục: {$targetDir}");

        return self::SUCCESS;
    }
}
