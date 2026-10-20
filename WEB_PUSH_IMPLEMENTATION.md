# Hệ thống Web Push Notification trong Laravel

Tài liệu này đóng gói toàn bộ logic Web Push đang được sử dụng trong dự án: client đăng ký/hủy nhận thông báo, admin quản lý nội dung, backend gửi notification, Service Worker hiển thị thông báo và cơ chế dọn subscription hết hạn.

## 1. Kiến trúc tổng thể

```text
Trình duyệt client
    │
    ├─ đăng ký Service Worker: /sw.js
    ├─ xin quyền Notification
    ├─ tạo PushSubscription bằng VAPID public key
    │
    └─ POST /push-subscribe
             │
             ▼
      push_subscriptions

Admin đăng nhập
    │
    ├─ tạo/sửa/lưu bản nháp
    ├─ POST /admin/notifications/{id}/send
    │
    ▼
WebPushSender
    │
    ├─ đọc khóa VAPID
    ├─ lấy toàn bộ subscription
    ├─ gửi payload qua push service của trình duyệt
    ├─ cập nhật số thành công/thất bại
    └─ xóa subscription đã hết hạn
             │
             ▼
Service Worker nhận push và hiển thị notification
```

Web Push không gửi trực tiếp từ Laravel tới trình duyệt. Laravel gửi request đã mã hóa tới push service tương ứng của Chrome, Edge hoặc Firefox. Push service sau đó chuyển notification tới Service Worker của client.

## 2. Yêu cầu

- PHP 8.3 hoặc tương thích với phiên bản Laravel đang sử dụng.
- Laravel 13.
- Extension OpenSSL của PHP.
- HTTPS trên production. `localhost` được trình duyệt coi là secure context khi phát triển.
- Trình duyệt hỗ trợ `ServiceWorker`, `PushManager` và Notification API.

Cài thư viện gửi Web Push:

```bash
composer require minishlink/web-push
```

## 3. Cấu hình VAPID

VAPID xác định application server gửi notification. Một cặp khóa phải được tạo một lần, lưu cố định và tái sử dụng. Không tạo khóa mới mỗi lần ứng dụng khởi động vì subscription cũ sẽ không còn khớp.

```env
VAPID_SUBJECT=mailto:admin@example.com
VAPID_PUBLIC_KEY=your_public_key
VAPID_PRIVATE_KEY=your_private_key
```

Chỉ public key được đưa xuống frontend. Không bao giờ trả private key về trình duyệt hoặc commit `.env` vào source control.

Khai báo trong `config/services.php`:

```php
'web_push' => [
    'subject' => env('VAPID_SUBJECT', 'mailto:admin@example.com'),
    'public_key' => env('VAPID_PUBLIC_KEY'),
    'private_key' => env('VAPID_PRIVATE_KEY'),
],
```

Sau khi thay đổi `.env` trên môi trường có cache cấu hình:

```bash
php artisan config:clear
php artisan config:cache
```

## 4. Cấu trúc database

### Bảng `push_subscriptions`

Mỗi record đại diện cho một browser profile trên một thiết bị.

```php
Schema::create('push_subscriptions', function (Blueprint $table): void {
    $table->id();
    $table->text('endpoint');
    $table->json('keys');
    $table->timestamps();
});
```

- `endpoint`: URL push service do trình duyệt cấp.
- `keys.p256dh`: public key dùng mã hóa payload cho subscription.
- `keys.auth`: authentication secret của subscription.

Model `PushSubscription`:

```php
class PushSubscription extends Model
{
    protected $fillable = ['endpoint', 'keys'];

    protected function casts(): array
    {
        return ['keys' => 'array'];
    }
}
```

### Bảng `push_messages`

Lưu nội dung do admin soạn và thống kê lần gửi gần nhất.

```php
Schema::create('push_messages', function (Blueprint $table): void {
    $table->id();
    $table->string('title', 120);
    $table->text('body');
    $table->text('target_url')->nullable();
    $table->string('status', 20)->default('draft')->index();
    $table->unsignedInteger('sent_count')->default(0);
    $table->unsignedInteger('failed_count')->default(0);
    $table->unsignedInteger('expired_count')->default(0);
    $table->timestamp('sent_at')->nullable();
    $table->timestamps();
});
```

Model `PushMessage` cho phép fill các trường nội dung, trạng thái và thống kê; `sent_at` được cast thành `datetime`.

Chạy migration:

```bash
php artisan migrate
```

## 5. Logic đăng ký client

Trang client nhận public key từ backend:

```php
public function index(): View
{
    return view('client.home', [
        'vapidPublicKey' => config('services.web_push.public_key'),
    ]);
}
```

Frontend thực hiện lần lượt:

1. Kiểm tra browser có hỗ trợ Web Push.
2. Đăng ký `/sw.js`.
3. Gọi `Notification.requestPermission()` từ thao tác click trực tiếp của người dùng.
4. Tìm subscription hiện có bằng `getSubscription()`.
5. Nếu chưa có, gọi `pushManager.subscribe()` với VAPID public key.
6. Gửi JSON subscription lên backend.

VAPID public key dạng Base64 URL-safe cần được đổi sang `Uint8Array`:

```javascript
function keyBytes(value) {
    const padding = '='.repeat((4 - value.length % 4) % 4);
    const base64 = (value + padding).replace(/-/g, '+').replace(/_/g, '/');
    const binary = atob(base64);

    return Uint8Array.from(binary, character => character.charCodeAt(0));
}
```

Đăng ký subscription:

```javascript
const registration = await navigator.serviceWorker.register('/sw.js');
await navigator.serviceWorker.ready;

const permission = await Notification.requestPermission();
if (permission !== 'granted') {
    throw new Error('Người dùng chưa cấp quyền hiển thị thông báo.');
}

let subscription = await registration.pushManager.getSubscription();

subscription ??= await registration.pushManager.subscribe({
    userVisibleOnly: true,
    applicationServerKey: keyBytes(vapidPublicKey),
});

await fetch('/push-subscribe', {
    method: 'POST',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
    },
    body: JSON.stringify(subscription.toJSON()),
});
```

Backend validate và lưu theo endpoint. `updateOrCreate` tránh tạo thêm record khi cùng browser đăng ký lại:

```php
public function subscribe(Request $request): JsonResponse
{
    $validated = $request->validate([
        'endpoint' => ['required', 'url:http,https', 'max:2048'],
        'keys' => ['required', 'array'],
        'keys.p256dh' => ['required', 'string'],
        'keys.auth' => ['required', 'string'],
    ]);

    PushSubscription::query()->updateOrCreate(
        ['endpoint' => $validated['endpoint']],
        ['keys' => $validated['keys']],
    );

    return response()->json([
        'message' => 'Đăng ký nhận thông báo thành công.',
    ]);
}
```

## 6. Logic hủy đăng ký

Frontend cần xóa cả hai phía:

1. Gửi endpoint cho Laravel để xóa record trong database.
2. Gọi `subscription.unsubscribe()` để hủy subscription tại browser.

```javascript
const registration = await navigator.serviceWorker.ready;
const subscription = await registration.pushManager.getSubscription();

if (subscription) {
    await fetch('/push-unsubscribe', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ endpoint: subscription.endpoint }),
    });

    await subscription.unsubscribe();
}
```

Backend:

```php
public function unsubscribe(Request $request): JsonResponse
{
    $validated = $request->validate([
        'endpoint' => ['required', 'url:http,https', 'max:2048'],
    ]);

    PushSubscription::query()
        ->where('endpoint', $validated['endpoint'])
        ->delete();

    return response()->json([
        'message' => 'Đã hủy nhận thông báo trên thiết bị này.',
    ]);
}
```

## 7. Service gửi notification

Toàn bộ logic giao tiếp với `minishlink/web-push` được đặt trong `App\Services\WebPushSender`. Controller admin chỉ truyền nội dung và nhận kết quả thống kê.

```php
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

        $payload = json_encode(
            compact('title', 'body', 'url'),
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE,
        );

        $result = ['sent' => 0, 'failed' => 0, 'expired' => 0];

        PushSubscription::query()->each(
            function (PushSubscription $stored) use ($webPush, $payload, &$result): void {
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
                            'endpoint' => $stored->endpoint,
                            'reason' => $report->getReason(),
                        ]);
                    }
                } catch (Throwable $exception) {
                    $result['failed']++;
                    report($exception);
                }
            },
        );

        return $result;
    }
}
```

### Ý nghĩa kết quả

- `sent`: push service chấp nhận request. Điều này không đảm bảo người dùng đã đọc notification.
- `failed`: request gửi thất bại nhưng subscription chưa được xác định là hết hạn.
- `expired`: push service xác nhận subscription không còn hợp lệ; record được xóa ngay.

## 8. Logic admin tạo và gửi thông báo

Admin tạo `PushMessage` ở trạng thái mặc định `draft`. Khi nhấn gửi:

```php
public function send(PushMessage $message, WebPushSender $sender): RedirectResponse
{
    try {
        $result = $sender->send(
            $message->title,
            $message->body,
            $message->target_url ?: route('client.home'),
        );
    } catch (RuntimeException $exception) {
        return back()->withErrors(['send' => $exception->getMessage()]);
    }

    $message->update([
        'status' => 'sent',
        'sent_count' => $result['sent'],
        'failed_count' => $result['failed'],
        'expired_count' => $result['expired'],
        'sent_at' => now(),
    ]);

    return back()->with(
        'success',
        "Đã gửi tới {$result['sent']} thiết bị; "
        ."lỗi {$result['failed']}; "
        ."xóa hết hạn {$result['expired']}.",
    );
}
```

Một thông báo đã gửi vẫn có thể gửi lại. Các cột thống kê hiện lưu kết quả của lần gửi gần nhất, không phải tổng tích lũy của mọi lần gửi.

## 9. Service Worker

File phải nằm trực tiếp tại `public/sw.js` để scope mặc định bao phủ toàn bộ website.

```javascript
self.addEventListener('push', (event) => {
    let data = {};

    if (event.data) {
        try {
            data = event.data.json();
        } catch (_error) {
            data = { body: event.data.text() };
        }
    }

    const title = data.title || 'Thông báo mới';
    const options = {
        body: data.body || 'Bạn có một thông báo mới.',
        icon: '/favicon.ico',
        badge: '/favicon.ico',
        data: { url: data.url || '/' },
    };

    event.waitUntil(
        self.registration.showNotification(title, options),
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const targetUrl = new URL(
        event.notification.data?.url || '/',
        self.location.origin,
    ).href;

    event.waitUntil((async () => {
        const windows = await clients.matchAll({
            type: 'window',
            includeUncontrolled: true,
        });

        const existingWindow = windows.find(
            client => client.url === targetUrl,
        );

        if (existingWindow) {
            await existingWindow.focus();
            return;
        }

        await clients.openWindow(targetUrl);
    })());
});
```

`event.waitUntil()` giữ Service Worker sống cho đến khi tác vụ bất đồng bộ hoàn tất. Khi người dùng nhấn notification, hệ thống ưu tiên focus tab đã mở đúng URL; nếu không có thì mở tab mới.

## 10. Routes

```php
Route::get('/', [PushController::class, 'index'])->name('client.home');
Route::post('/push-subscribe', [PushController::class, 'subscribe'])->name('push.subscribe');
Route::post('/push-unsubscribe', [PushController::class, 'unsubscribe'])->name('push.unsubscribe');

Route::middleware('guest')->group(function (): void {
    Route::get('/admin/login', [AuthController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthController::class, 'store'])->name('login.store');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function (): void {
    Route::redirect('/', '/admin/notifications');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::resource('notifications', PushMessageController::class)
        ->parameters(['notifications' => 'message'])
        ->except('show')
        ->names('messages');

    Route::post(
        '/notifications/{message}/send',
        [PushMessageController::class, 'send'],
    )->name('messages.send');

    Route::get(
        '/subscriptions',
        [SubscriptionController::class, 'index'],
    )->name('subscriptions.index');

    Route::delete(
        '/subscriptions/{subscription}',
        [SubscriptionController::class, 'destroy'],
    )->name('subscriptions.destroy');
});
```

Route gửi notification nằm trong middleware `auth` và sử dụng `POST`. Không dùng `GET` cho thao tác gửi vì đây là thao tác làm thay đổi trạng thái và có tác động bên ngoài.

## 11. Xác thực admin

Ứng dụng dùng session authentication sẵn có của Laravel:

```php
if (! Auth::attempt($credentials, $request->boolean('remember'))) {
    return back()
        ->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])
        ->onlyInput('email');
}

$request->session()->regenerate();
```

Khi đăng xuất:

```php
Auth::logout();
$request->session()->invalidate();
$request->session()->regenerateToken();
```

Tất cả form thay đổi dữ liệu đều cần `@csrf`. Mật khẩu admin phải được thay trước khi đưa lên production.

## 12. Quy trình sử dụng

1. Chạy `php artisan migrate --seed`.
2. Chạy `php artisan serve`.
3. Client mở `/`, chọn **Bật thông báo** và cấp quyền.
4. Admin mở `/admin`, đăng nhập.
5. Admin tạo thông báo, nhập tiêu đề, nội dung và URL đích.
6. Admin lưu bản nháp rồi chọn **Gửi tới tất cả thiết bị**.
7. Backend gửi tới từng subscription và lưu kết quả.
8. Service Worker của client hiển thị notification.
9. Khi client nhấn notification, trình duyệt focus hoặc mở URL đích.

## 13. Kiểm thử quan trọng

Các trường hợp cần có test tự động:

- Trang client truy cập được.
- Subscription hợp lệ được tạo và cập nhật theo endpoint.
- Subscription sai định dạng bị từ chối với HTTP 422.
- Client hủy đăng ký thì record bị xóa.
- Guest truy cập admin bị chuyển tới login.
- Admin đăng nhập đúng và session được thiết lập.
- Admin tạo, sửa và xóa bản nháp.
- Admin gửi thông báo khi chưa có subscriber vẫn trả kết quả hợp lệ với số lượng bằng 0.
- Thông báo chuyển sang trạng thái `sent` và có `sent_at` sau khi gửi.

Chạy kiểm thử:

```bash
php artisan test
```

## 14. Lưu ý khi triển khai production

- Bắt buộc dùng HTTPS.
- Không commit `.env` hoặc VAPID private key.
- Dùng mật khẩu admin mạnh và cân nhắc bổ sung phân quyền nếu hệ thống có nhiều user.
- Với số lượng subscriber lớn, không gửi đồng bộ trong HTTP request. Hãy dispatch Laravel Job vào queue, chia batch và cập nhật trạng thái tiến trình.
- Có thể dùng `queueNotification()` và `flush()` của `minishlink/web-push` để tối ưu gửi theo lô.
- Rate-limit các endpoint đăng ký và hủy đăng ký nếu website công khai.
- Có thể lưu thêm user ID, user agent và thời điểm hoạt động cuối để quản lý thiết bị tốt hơn; tránh thu thập dữ liệu không cần thiết.
- Theo dõi log lỗi gửi nhưng không công khai toàn bộ endpoint subscription.
- Nếu thay VAPID key, các client hiện tại thường phải đăng ký subscription mới.
- iOS/iPadOS yêu cầu website được thêm vào Home Screen đối với một số phiên bản và chỉ hỗ trợ Web Push trên các phiên bản hệ điều hành phù hợp.

## 15. Các file tương ứng trong dự án

```text
app/Http/Controllers/PushController.php
app/Http/Controllers/AuthController.php
app/Http/Controllers/Admin/PushMessageController.php
app/Http/Controllers/Admin/SubscriptionController.php
app/Models/PushSubscription.php
app/Models/PushMessage.php
app/Services/WebPushSender.php
config/services.php
database/migrations/*_create_push_subscriptions_table.php
database/migrations/*_create_push_messages_table.php
public/sw.js
resources/views/client/home.blade.php
resources/views/admin/messages/*.blade.php
resources/views/admin/subscriptions/index.blade.php
routes/web.php
tests/Feature/PushNotificationTest.php
tests/Feature/AdminNotificationTest.php
```

Tài liệu này mô tả logic của phiên bản gửi tức thời hiện tại. Nếu bổ sung lịch gửi, segment người nhận hoặc queue, nên giữ `WebPushSender` làm lớp hạ tầng và đặt orchestration vào Job/Service riêng để controller luôn gọn.
