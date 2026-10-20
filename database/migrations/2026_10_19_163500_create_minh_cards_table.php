<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('minh_cards', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique();
            $table->string('name', 150);
            $table->string('job_title', 150)->nullable();
            $table->string('company', 150)->nullable();
            $table->string('email', 190)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('zalo', 100)->nullable();
            $table->string('telegram', 100)->nullable();
            $table->string('facebook', 255)->nullable();
            $table->string('github', 255)->nullable();
            $table->string('whatsapp', 100)->nullable();
            $table->string('viber', 100)->nullable();
            $table->string('linkedin', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->text('bio')->nullable();
            $table->string('photo_path', 255)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('minh_cards');
    }
};
