<?php

namespace App\Services;

use App\Models\PushSubscription;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use RuntimeException;
use Throwable;

class WebPushSender
{
    /** @return array{sent: int, failed: int, expired: int} */
    public function send(string $title, string $body, string $url): array
    {
        $vapid = config('services.web_push');

        if (filled($vapid['openssl_conf'] ?? null)) {
            putenv('OPENSSL_CONF='.$vapid['openssl_conf']);
        }

        if (blank($vapid['public_key']) || blank($vapid['private_key'])) {
            throw new RuntimeException('Chưa cấu hình khóa VAPID.');
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => $vapid['subject'],
                'publicKey' => $vapid['public_key'],
                'privateKey' => $vapid['private_key'],
            ],
        ]);
        $payload = json_encode(compact('title', 'body', 'url'), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        $result = ['sent' => 0, 'failed' => 0, 'expired' => 0];

        PushSubscription::query()->each(function (PushSubscription $stored) use ($webPush, $payload, &$result): void {
            try {
                $subscription = Subscription::create([
                    'endpoint' => $stored->endpoint,
                    'keys' => [
                        'p256dh' => $stored->keys['p256dh'],
                        'auth' => $stored->keys['auth'],
                    ],
                ]);
                $report = $webPush->sendOneNotification($subscription, $payload);

                if ($report->isSuccess()) {
                    $result['sent']++;
                } elseif ($report->isSubscriptionExpired()) {
                    $stored->delete();
                    $result['expired']++;
                } else {
                    $result['failed']++;
                    Log::warning('Không thể gửi web push.', [
                        'subscription_id' => $stored->id,
                        'reason' => $report->getReason(),
                    ]);
                }
            } catch (Throwable $exception) {
                $result['failed']++;
                report($exception);
            }
        });

        return $result;
    }
}
