<?php

namespace App\Console\Commands;

use App\Models\PushMessage;
use App\Services\WebPushSender;
use Illuminate\Console\Command;
use Throwable;

class SendWebPush extends Command
{
    protected $signature = 'webpush:send {message}';

    protected $description = 'Send one stored Web Push message';

    public function handle(WebPushSender $sender): int
    {
        try {
            $message = PushMessage::query()->findOrFail($this->argument('message'));
            $result = $sender->send(
                $message->title,
                $message->body,
                $message->target_url ?: url('/'),
            );
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->output->write(json_encode($result, JSON_THROW_ON_ERROR));

        return self::SUCCESS;
    }
}
