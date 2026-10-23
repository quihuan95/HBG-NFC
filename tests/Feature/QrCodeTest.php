<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QrCodeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->employee = Employee::create([
            'code' => 'HBG00101',
            'name' => 'Trần Văn Nam',
            'job_title' => 'Chuyên viên Sales',
            'email' => 'nam.tv@hoabinh-group.com',
            'phone' => '0912345678',
            'active' => true,
        ]);
    }

    public function test_public_card_qr_route_serves_valid_png(): void
    {
        $response = $this->get(route('card.qr', $this->employee->code));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/png');
        $this->assertNotEmpty($response->getContent());
    }

    public function test_inactive_employee_qr_returns_404(): void
    {
        $this->employee->update(['active' => false]);

        $response = $this->get(route('card.qr', $this->employee->code));
        $response->assertNotFound();
    }

    public function test_guest_cannot_access_admin_qr_download(): void
    {
        $response = $this->get(route('admin.employees.download_qr', $this->employee));
        $response->assertRedirect(route('login'));

        $zipResponse = $this->get(route('admin.employees.export_qr_zip'));
        $zipResponse->assertRedirect(route('login'));
    }

    public function test_admin_can_download_single_employee_qr(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.employees.download_qr', $this->employee));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/png');
        $this->assertTrue(str_contains($response->headers->get('Content-Disposition'), 'Trần Văn Nam_Chuyên viên Sales_HBG00101.png'));
    }

    public function test_admin_can_download_bulk_qr_zip(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.employees.export_qr_zip'));

        $response->assertOk();
        $this->assertTrue(str_contains($response->headers->get('Content-Disposition'), 'HBG_NFC_QRCodes_'));
    }

    public function test_artisan_export_qr_command_generates_files(): void
    {
        $tempDir = storage_path('framework/testing/qr_test_' . uniqid());

        $this->artisan('employees:export-qr', [
            '--out' => $tempDir,
        ])->assertSuccessful();

        $this->assertFileExists($tempDir . '/Trần Văn Nam_Chuyên viên Sales_HBG00101.png');

        // Cleanup
        @unlink($tempDir . '/Trần Văn Nam_Chuyên viên Sales_HBG00101.png');
        @rmdir($tempDir);
    }

    public function test_artisan_export_qr_zip_command_generates_zip(): void
    {
        $tempZip = storage_path('framework/testing/test_export_' . uniqid() . '.zip');

        $this->artisan('employees:export-qr', [
            '--zip' => true,
            '--out' => $tempZip,
        ])->assertSuccessful();

        $this->assertFileExists($tempZip);
        $this->assertGreaterThan(0, filesize($tempZip));

        // Cleanup
        @unlink($tempZip);
    }

    public function test_admin_can_export_selected_employees_via_post(): void
    {
        $employee2 = Employee::create([
            'code' => 'HBG00102',
            'name' => 'Lê Thị Mai',
            'job_title' => 'Trưởng phòng Tour',
            'email' => 'mai.lt@hoabinhtourist.com',
            'phone' => '0987654321',
            'active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.employees.export_qr_zip'), [
            'ids' => [$employee2->id],
        ]);

        $response->assertOk();
        $this->assertTrue(str_contains($response->headers->get('Content-Disposition'), 'HBG_NFC_QRCodes_'));
    }

    public function test_admin_export_invalid_ids_returns_error(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.employees.export_qr_zip'), [
            'ids' => [99999],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
