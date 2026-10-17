<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('facebook', 255)->nullable()->after('zalo');
            $table->string('whatsapp', 100)->nullable()->after('facebook');
            $table->string('linkedin', 255)->nullable()->after('whatsapp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['facebook', 'whatsapp', 'linkedin']);
        });
    }
};
