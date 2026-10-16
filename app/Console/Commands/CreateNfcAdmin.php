<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateNfcAdmin extends Command
{
    protected $signature = 'nfc:admin {email : Email đăng nhập} {name : Tên admin}';

    protected $description = 'Tạo tài khoản quản trị NFC (hỏi password, không lưu lịch sử shell)';

    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email')));
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Email không hợp lệ.');

            return self::FAILURE;
        }
        if (User::where('email', $email)->exists()) {
            $this->error('Email đã tồn tại.');

            return self::FAILURE;
        }
        $password = $this->secret('Nhập mật khẩu (>= 12 ký tự)');
        $confirm = $this->secret('Nhập lại mật khẩu');
        if (! is_string($password) || strlen($password) < 12 || $password !== $confirm) {
            $this->error('Mật khẩu không khớp hoặc dưới 12 ký tự.');

            return self::FAILURE;
        }
        User::create(['name' => $this->argument('name'), 'email' => $email, 'password' => Hash::make($password)]);
        $this->info('Đã tạo admin: '.$email);

        return self::SUCCESS;
    }
}
