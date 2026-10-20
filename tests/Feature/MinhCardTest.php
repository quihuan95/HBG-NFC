<?php

namespace Tests\Feature;

use App\Models\MinhCard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MinhCardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_minh_login_from_minh_admin(): void
    {
        $response = $this->get('/admin/minh');
        $response->assertRedirect('/admin/minh/login');
    }

    public function test_minh_login_page_renders_cleanly(): void
    {
        $response = $this->get('/admin/minh/login');
        $response->assertStatus(200);
        $response->assertSee('Đăng nhập quản trị');
        $response->assertSee('MINH');
        $response->assertSee('PRIVATE NFC ADMIN');
        $response->assertDontSee('HoaBinh Group');
    }

    public function test_minh_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'minh@thehbg-nfc.test',
            'password' => bcrypt('password12345'),
        ]);

        $response = $this->post('/admin/minh/login', [
            'email' => 'minh@thehbg-nfc.test',
            'password' => 'password12345',
        ]);

        $response->assertRedirect('/admin/minh');
        $this->assertAuthenticatedAs($user);
    }

    public function test_minh_cannot_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'minh@thehbg-nfc.test',
            'password' => bcrypt('password12345'),
        ]);

        $response = $this->from('/admin/minh/login')->post('/admin/minh/login', [
            'email' => 'minh@thehbg-nfc.test',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/admin/minh/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_minh_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/minh/logout');
        $response->assertRedirect('/admin/minh/login');
        $this->assertGuest();
    }

    public function test_authenticated_user_visiting_minh_login_redirects_to_minh_admin(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/minh/login');
        $response->assertRedirect('/admin/minh');
    }

    public function test_admin_can_view_minh_index(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->get('/admin/minh');
        $response->assertStatus(200);
        $response->assertSee('Quản lý Thẻ Cá Nhân');
        $response->assertSee('/admin/minh');
    }

    public function test_admin_can_create_minh_card(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post('/admin/minh', [
            'code' => 'minh01',
            'name' => 'Phạm Quang Minh',
            'job_title' => 'Webmaster & Developer',
            'company' => 'Personal Tech',
            'email' => 'minh@example.com',
            'phone' => '0901234567',
            'telegram' => '@quangminh',
            'github' => 'minhdev',
            'active' => '1',
        ]);

        $card = MinhCard::first();
        $this->assertNotNull($card);
        $this->assertSame('MINH01', $card->code);
        $this->assertSame('Phạm Quang Minh', $card->name);
        $this->assertSame('https://t.me/quangminh', $card->telegram_url);
        $this->assertSame('https://github.com/minhdev', $card->github_url);
        $response->assertRedirect(route('admin.minh.edit', $card));
    }

    public function test_public_minh_card_is_accessible_when_active(): void
    {
        $card = MinhCard::create([
            'code' => 'MINH',
            'name' => 'Phạm Quang Minh',
            'job_title' => 'Developer',
            'company' => 'Creative Hub',
            'phone' => '0901234567',
            'email' => 'minh@example.com',
            'active' => true,
        ]);

        $response = $this->get('/minh/MINH');
        $response->assertStatus(200);
        $response->assertSee('Phạm Quang Minh');
        $response->assertSee('Developer');
        $response->assertSee('PERSONAL CARD');
        $response->assertDontSee('HoaBinh Group');
    }

    public function test_public_minh_card_returns_404_when_inactive(): void
    {
        MinhCard::create([
            'code' => 'INACTIVE',
            'name' => 'Người Dùng Tạm Ẩn',
            'active' => false,
        ]);

        $response = $this->get('/minh/INACTIVE');
        $response->assertStatus(404);
    }

    public function test_minh_card_vcard_download(): void
    {
        MinhCard::create([
            'code' => 'MINHVIP',
            'name' => 'Phạm Quang Minh',
            'job_title' => 'Tech Lead',
            'company' => 'My Private Brand',
            'phone' => '0901234567',
            'email' => 'minh@test.com',
            'telegram' => '@minh_tg',
            'active' => true,
        ]);

        $response = $this->get('/minh/MINHVIP?download=vcard');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/vcard; charset=utf-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="Minh-MINHVIP.vcf"');
        $response->assertSee('FN:Phạm Quang Minh', false);
        $response->assertSee('ORG:My Private Brand', false);
        $response->assertSee('TITLE:Tech Lead', false);
        $response->assertSee('X-SOCIALPROFILE;TYPE=telegram:https://t.me/minh_tg', false);
    }

    public function test_admin_can_update_minh_card(): void
    {
        $admin = User::factory()->create();
        $card = MinhCard::create([
            'code' => 'MINH02',
            'name' => 'Quang Minh',
            'active' => true,
        ]);

        $response = $this->actingAs($admin)->put('/admin/minh/' . $card->id, [
            'name' => 'Phạm Quang Minh (Updated)',
            'job_title' => 'Lead Architect',
            'active' => '1',
        ]);

        $response->assertRedirect();
        $card->refresh();
        $this->assertSame('Phạm Quang Minh (Updated)', $card->name);
        $this->assertSame('Lead Architect', $card->job_title);
    }

    public function test_admin_can_delete_minh_card(): void
    {
        $admin = User::factory()->create();
        $card = MinhCard::create([
            'code' => 'MINHTODELETE',
            'name' => 'Thẻ Chuẩn Bị Xóa',
            'active' => true,
        ]);

        $response = $this->actingAs($admin)->delete('/admin/minh/' . $card->id);
        $response->assertRedirect(route('admin.minh.index'));
        $this->assertDatabaseMissing('minh_cards', ['code' => 'MINHTODELETE']);
    }
}
