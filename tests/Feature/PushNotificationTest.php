<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\PushMessage;
use App\Models\PushSubscription;
use App\Models\User;
use App\Services\WebPushSender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class PushNotificationTest extends TestCase
{
    use RefreshDatabase;

    private array $subscription = [
        'endpoint' => 'https://push.example.test/subscription/123',
        'keys' => ['p256dh' => 'public-key', 'auth' => 'auth-secret'],
    ];

    public function test_client_can_create_and_update_subscription_by_endpoint(): void
    {
        $this->postJson(route('push.subscribe'), $this->subscription)->assertOk();
        $this->postJson(route('push.subscribe'), [
            ...$this->subscription,
            'keys' => ['p256dh' => 'updated-key', 'auth' => 'updated-secret'],
        ])->assertOk();

        $this->assertDatabaseCount('push_subscriptions', 1);
        $this->assertSame('updated-key', PushSubscription::first()->keys['p256dh']);
    }

    public function test_invalid_subscription_is_rejected(): void
    {
        $this->postJson(route('push.subscribe'), ['endpoint' => 'invalid'])->assertUnprocessable();
    }

    public function test_client_can_unsubscribe(): void
    {
        PushSubscription::create($this->subscription);
        $this->postJson(route('push.unsubscribe'), ['endpoint' => $this->subscription['endpoint']])->assertOk();
        $this->assertDatabaseCount('push_subscriptions', 0);
    }

    public function test_guest_cannot_manage_notifications(): void
    {
        $this->get(route('admin.notifications.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_render_notification_list_with_messages(): void
    {
        $admin = User::factory()->create();
        PushMessage::create([
            'title' => 'Thông báo kiểm tra',
            'body' => str_repeat('Nội dung dài ', 20),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.notifications.index'))
            ->assertOk()
            ->assertSee('Thông báo kiểm tra');
    }

    public function test_public_card_asks_visitor_to_receive_notifications(): void
    {
        config(['services.web_push.public_key' => 'test-public-key']);
        $employee = Employee::create([
            'code' => 'PUSH01',
            'name' => 'Push Test',
            'active' => true,
        ]);

        $this->get(route('card.show', $employee->code))
            ->assertOk()
            ->assertSee('Bạn có muốn nhận thông báo?')
            ->assertSee('pushPromptYes', false);
    }

    public function test_admin_can_create_and_send_notification(): void
    {
        $admin = User::factory()->create();
        $response = $this->actingAs($admin)->post(route('admin.notifications.store'), [
            'title' => 'Tin mới', 'body' => 'Nội dung thông báo', 'target_url' => 'https://example.test/news',
        ]);
        $message = PushMessage::firstOrFail();
        $response->assertRedirect(route('admin.notifications.edit', $message));

        $this->mock(WebPushSender::class, function (MockInterface $mock): void {
            $mock->shouldReceive('send')->once()->andReturn(['sent' => 2, 'failed' => 1, 'expired' => 1]);
        });
        $this->actingAs($admin)->post(route('admin.notifications.send', $message))->assertSessionHas('success');

        $message->refresh();
        $this->assertSame('sent', $message->status);
        $this->assertSame(2, $message->sent_count);
        $this->assertNotNull($message->sent_at);
    }
}
