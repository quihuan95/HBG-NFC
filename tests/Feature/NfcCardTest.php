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
            'facebook' => 'nguyenvana.hbg',
            'whatsapp' => '0901234567',
            'linkedin' => 'nguyenvana',
            'website' => 'https://thehbg.com',
            'bio' => 'Kỹ sư phần mềm cao cấp',
            'active' => '1',
        ]);

        $employee = Employee::first();
        $this->assertNotNull($employee);
        $this->assertEquals('HBG001', $employee->code);
        $this->assertEquals('Nguyễn Văn A', $employee->name);
        $this->assertEquals('nguyenvana.hbg', $employee->facebook);
        $this->assertEquals('0901234567', $employee->whatsapp);
        $this->assertEquals('nguyenvana', $employee->linkedin);
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
            'zalo' => '0901234567',
            'facebook' => 'hbg.ceo',
            'whatsapp' => '0901234567',
            'linkedin' => 'hbg-ceo',
            'active' => true,
        ]);

        $response = $this->get('/HBG001');
        $response->assertStatus(200);
        $response->assertSee('Nguyễn Văn A');
        $response->assertSee('Giám đốc Điều hành');
        $response->assertSee('Ban Giám Đốc');
        $response->assertSee('https://zalo.me/0901234567');
        $response->assertSee('https://facebook.com/hbg.ceo');
        $response->assertSee('https://wa.me/84901234567');
        $response->assertSee('https://linkedin.com/in/hbg-ceo');
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
            'facebook' => 'lethic',
            'whatsapp' => '0912345678',
            'linkedin' => 'le-thi-c',
            'active' => true,
        ]);

        $response = $this->get('/HBG003?download=vcard');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/vcard; charset=utf-8');
        $this->assertStringContainsString('BEGIN:VCARD', $response->getContent());
        $this->assertStringContainsString('FN:Lê Thị C', $response->getContent());
        $this->assertStringContainsString('X-SOCIALPROFILE;TYPE=facebook:https://facebook.com/lethic', $response->getContent());
        $this->assertStringContainsString('X-SOCIALPROFILE;TYPE=whatsapp:https://wa.me/84912345678', $response->getContent());
        $this->assertStringContainsString('X-SOCIALPROFILE;TYPE=linkedin:https://linkedin.com/in/le-thi-c', $response->getContent());
        $this->assertStringContainsString('END:VCARD', $response->getContent());
    }

    public function test_public_card_renders_with_minimal_data(): void
    {
        Employee::create([
            'code' => 'HBG004',
            'name' => 'Hoàng Văn D',
            'active' => true,
        ]);

        $response = $this->get('/HBG004');
        $response->assertStatus(200);
        $response->assertSee('Hoàng Văn D');
        $response->assertSee('H'); // Initials
    }

    public function test_admin_can_update_employee_social_links(): void
    {
        $admin = User::factory()->create();
        $employee = Employee::create([
            'code' => 'HBG005',
            'name' => 'Ngô Thị E',
            'active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.employees.update', $employee), [
            'name' => 'Ngô Thị E Updated',
            'facebook' => 'https://facebook.com/ngothie',
            'whatsapp' => '+84988776655',
            'linkedin' => 'https://linkedin.com/in/ngothie',
            'active' => '1',
        ]);

        $response->assertRedirect();
        $employee->refresh();
        $this->assertEquals('Ngô Thị E Updated', $employee->name);
        $this->assertEquals('https://facebook.com/ngothie', $employee->facebook);
        $this->assertEquals('+84988776655', $employee->whatsapp);
        $this->assertEquals('https://linkedin.com/in/ngothie', $employee->linkedin);
    }
}
