<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;
use Throwable;

class GenerateVapidKeys extends Command
{
    protected $signature = 'webpush:vapid';

    protected $description = 'Generate a VAPID key pair for Web Push';

    public function handle(): int
    {
        if (filled(config('services.web_push.openssl_conf'))) {
            putenv('OPENSSL_CONF='.config('services.web_push.openssl_conf'));
        }

        try {
            $keys = VAPID::createVapidKeys();
        } catch (Throwable $exception) {
            $this->error('Không thể tạo khóa VAPID. Hãy kiểm tra PHP OpenSSL và cấu hình openssl.cnf.');
            $this->line($exception->getMessage());

            return self::FAILURE;
        }
        $this->line('VAPID_PUBLIC_KEY='.$keys['publicKey']);
        $this->line('VAPID_PRIVATE_KEY='.$keys['privateKey']);
        $this->warn('Lưu hai khóa này vào .env và không công khai private key.');

        return self::SUCCESS;
    }
}
