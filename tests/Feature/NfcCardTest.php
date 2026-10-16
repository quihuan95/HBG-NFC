<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NfcCardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $response = $this->get('/admin/employees');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@thehbg.com',
            'password' => bcrypt('password12345'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@thehbg.com',
            'password' => 'password12345',
        ]);

        $response->assertRedirect('/admin/employees');
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_cannot_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'admin@thehbg.com',
            'password' => bcrypt('password12345'),
        ]);

        $response = $this->from('/admin/login')->post('/admin/login', [
            'email' => 'admin@thehbg.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_can_create_employee(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post('/admin/employees', [
            'code' => 'hbg001',
            'name' => 'Nguyễn Văn A',
            'job_title' => 'Trưởng phòng Kỹ thuật',
            'department' => 'Công nghệ',
            'email' => 'a.nguyen@thehbg.com',
            'phone' => '0901234567',
            'zalo' => '0901234567',
            'website' => 'https://thehbg.com',
            'bio' => 'Kỹ sư phần mềm cao cấp',
            'active' => '1',
        ]);

        $employee = Employee::first();
        $this->assertNotNull($employee);
        $this->assertEquals('HBG001', $employee->code);
        $this->assertEquals('Nguyễn Văn A', $employee->name);
        $response->assertRedirect(route('admin.employees.edit', $employee));
    }

    public function test_employee_code_rejects_reserved_keywords(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post('/admin/employees', [
            'code' => 'admin-boss',
            'name' => 'Admin Boss',
            'active' => '1',
        ]);

        $response->assertSessionHasErrors('code');
        $this->assertEquals(0, Employee::count());
    }

    public function test_public_card_is_accessible_when_active(): void
    {
        $employee = Employee::create([
            'code' => 'HBG001',
            'name' => 'Nguyễn Văn A',
            'job_title' => 'Giám đốc Điều hành',
            'department' => 'Ban Giám Đốc',
            'email' => 'ceo@thehbg.com',
            'phone' => '+84901234567',
            'active' => true,
        ]);

        $response = $this->get('/HBG001');
        $response->assertStatus(200);
        $response->assertSee('Nguyễn Văn A');
        $response->assertSee('Giám đốc Điều hành');
        $response->assertSee('Ban Giám Đốc');
    }

    public function test_public_card_returns_404_when_inactive(): void
    {
        Employee::create([
            'code' => 'HBG002',
            'name' => 'Trần Văn B',
            'active' => false,
        ]);

        $response = $this->get('/HBG002');
        $response->assertStatus(404);
    }

    public function test_vcard_download_returns_vcf(): void
    {
        Employee::create([
            'code' => 'HBG003',
            'name' => 'Lê Thị C',
            'job_title' => 'Chuyên viên Nhân sự',
            'email' => 'c.le@thehbg.com',
            'phone' => '0912345678',
            'active' => true,
        ]);

        $response = $this->get('/HBG003?download=vcard');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/vcard; charset=utf-8');
        $this->assertStringContainsString('BEGIN:VCARD', $response->getContent());
        $this->assertStringContainsString('FN:Lê Thị C', $response->getContent());
        $this->assertStringContainsString('END:VCARD', $response->getContent());
    }
}
