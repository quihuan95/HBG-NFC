<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('push_messages');
    }
};
