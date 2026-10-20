<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushMessage;
use App\Models\PushSubscription;
use App\Services\WebPushSender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\Process\Process;

class PushMessageController extends Controller
{
    public function index(): View
    {
        return view('admin.notifications.index', [
            'messages' => PushMessage::query()->latest()->paginate(15),
            'subscriptionCount' => PushSubscription::query()->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.notifications.form', ['pushMessage' => new PushMessage]);
    }

    public function store(Request $request): RedirectResponse
    {
        $message = PushMessage::create($this->validated($request));

        return redirect()->route('admin.notifications.edit', $message)->with('success', 'Đã lưu bản nháp thông báo.');
    }

    public function edit(PushMessage $notification): View
    {
        return view('admin.notifications.form', ['pushMessage' => $notification]);
    }

    public function update(Request $request, PushMessage $notification): RedirectResponse
    {
        $notification->update($this->validated($request));

        return back()->with('success', 'Đã cập nhật thông báo.');
    }

    public function destroy(PushMessage $notification): RedirectResponse
    {
        $notification->delete();

        return redirect()->route('admin.notifications.index')->with('success', 'Đã xóa thông báo.');
    }

    public function send(PushMessage $notification, WebPushSender $sender): RedirectResponse
    {
        try {
            $result = $this->sendNotification($notification, $sender);
        } catch (RuntimeException $exception) {
            return back()->withErrors(['send' => $exception->getMessage()]);
        }

        $notification->update([
            'status' => 'sent',
            'sent_count' => $result['sent'],
            'failed_count' => $result['failed'],
            'expired_count' => $result['expired'],
            'sent_at' => now(),
        ]);

        return back()->with('success', "Đã gửi tới {$result['sent']} thiết bị; lỗi {$result['failed']}; xóa hết hạn {$result['expired']}.");
    }

    /** @return array{sent: int, failed: int, expired: int} */
    private function sendNotification(PushMessage $notification, WebPushSender $sender): array
    {
        $opensslConf = config('services.web_push.openssl_conf');

        if (PHP_OS_FAMILY === 'Windows' && PHP_SAPI !== 'cli' && filled($opensslConf)) {
            $php = dirname(PHP_BINARY).DIRECTORY_SEPARATOR.'php.exe';
            $process = new Process(
                [$php, base_path('artisan'), 'webpush:send', (string) $notification->id],
                base_path(),
                ['OPENSSL_CONF' => $opensslConf],
            );
            $process->setTimeout(120)->run();

            if (! $process->isSuccessful()) {
                throw new RuntimeException(trim($process->getErrorOutput()) ?: 'Tiến trình gửi thông báo thất bại.');
            }

            $result = json_decode(trim($process->getOutput()), true);
            if (! is_array($result) || ! isset($result['sent'], $result['failed'], $result['expired'])) {
                throw new RuntimeException('Không đọc được kết quả gửi thông báo.');
            }

            return $result;
        }

        return $sender->send(
            $notification->title,
            $notification->body,
            $notification->target_url ?: url('/'),
        );
    }

    /** @return array{title: string, body: string, target_url: ?string} */
    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string', 'max:1000'],
            'target_url' => ['nullable', 'url:http,https', 'max:2048'],
        ]);
    }
}
