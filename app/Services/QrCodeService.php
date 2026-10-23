<?php

namespace App\Services;

use App\Models\Employee;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Collection;
use ZipArchive;

class QrCodeService
{
    /**
     * Tạo nội dung mã QR dạng binary PNG.
     */
    public function generatePng(
        string $data,
        int $size = 400,
        int $margin = 10,
        Color $foregroundColor = new Color(0, 0, 0),
        Color $backgroundColor = new Color(255, 255, 255)
    ): string {
        $qrCode = new QrCode(
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: $size,
            margin: $margin,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: $foregroundColor,
            backgroundColor: $backgroundColor
        );

        $writer = new PngWriter();

        return $writer->write($qrCode)->getString();
    }

    /**
     * Tạo nội dung mã QR dạng SVG string.
     */
    public function generateSvg(
        string $data,
        int $size = 400,
        int $margin = 10,
        Color $foregroundColor = new Color(0, 0, 0),
        Color $backgroundColor = new Color(255, 255, 255)
    ): string {
        $qrCode = new QrCode(
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: $size,
            margin: $margin,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: $foregroundColor,
            backgroundColor: $backgroundColor
        );

        $writer = new SvgWriter();

        return $writer->write($qrCode)->getString();
    }

    /**
     * Tạo Data URI (dùng nhúng trực tiếp vào thẻ <img src="...">).
     */
    public function generateDataUri(string $data, int $size = 300, string $format = 'png'): string
    {
        if ($format === 'svg') {
            $svg = $this->generateSvg($data, $size);

            return 'data:image/svg+xml;utf8,'.rawurlencode($svg);
        }

        $png = $this->generatePng($data, $size);

        return 'data:image/png;base64,'.base64_encode($png);
    }

    /**
     * Lấy URL công khai của thẻ nhân viên.
     */
    public function getEmployeeCardUrl(Employee $employee): string
    {
        return route('card.show', $employee->code);
    }

    /**
     * Tạo mã QR cho một nhân viên cụ thể.
     */
    public function generateForEmployee(Employee $employee, string $format = 'png', int $size = 400): string
    {
        $url = $this->getEmployeeCardUrl($employee);

        return $format === 'svg'
            ? $this->generateSvg($url, $size)
            : $this->generatePng($url, $size);
    }

    /**
     * Tên file chuẩn định dạng: Tên_Chức vụ_Mã.ext (đã lọc ký tự cấm của hệ điều hành).
     */
    public function getEmployeeFileName(Employee $employee, string $format = 'png'): string
    {
        $name = trim($employee->name);
        $title = trim($employee->job_title ?: 'Nhân viên');
        $code = trim($employee->code);

        $rawName = "{$name}_{$title}_{$code}.{$format}";

        // Loại bỏ các ký tự cấm trong tên file Windows/Linux: \ / : * ? " < > |
        return preg_replace('~[\\\\/:*?"<>|]~u', '_', $rawName);
    }

    /**
     * Xuất danh sách QR nhân viên ra một thư mục cục bộ.
     *
     * @param iterable<Employee> $employees
     * @return array<int, string> Danh sách đường dẫn file đã tạo
     */
    public function exportEmployeesToDirectory(
        iterable $employees,
        string $outputDir,
        string $format = 'png',
        int $size = 400
    ): array {
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $createdFiles = [];

        foreach ($employees as $employee) {
            $fileName = $this->getEmployeeFileName($employee, $format);
            $filePath = rtrim($outputDir, '/\\') . DIRECTORY_SEPARATOR . $fileName;

            $content = $this->generateForEmployee($employee, $format, $size);
            file_put_contents($filePath, $content);

            $createdFiles[] = $filePath;
        }

        return $createdFiles;
    }

    /**
     * Đóng gói mã QR của toàn bộ nhân viên vào 1 file ZIP và trả về đường dẫn file ZIP.
     *
     * @param iterable<Employee> $employees
     */
    public function exportEmployeesToZip(
        iterable $employees,
        ?string $zipPath = null,
        string $format = 'png',
        int $size = 400
    ): string {
        if (!$zipPath) {
            $tempDir = storage_path('app/temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0777, true);
            }
            $zipPath = $tempDir . DIRECTORY_SEPARATOR . 'HBG_NFC_QRCodes_' . date('Ymd_His') . '.zip';
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException("Không thể tạo file zip tại: {$zipPath}");
        }

        foreach ($employees as $employee) {
            $fileName = $this->getEmployeeFileName($employee, $format);
            $content = $this->generateForEmployee($employee, $format, $size);
            $zip->addFromString($fileName, $content);
        }

        $zip->close();

        return $zipPath;
    }
}
