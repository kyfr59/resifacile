<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sendings', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at', 'executed_at']);

            $table->timestamp('waiting_at')->nullable()->index();
            $table->timestamp('sended_at')->nullable()->index();
            $table->timestamp('accepted_at')->nullable()->index();
            $table->timestamp('processed_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('sendings', function (Blueprint $table) {
            $table->dropColumn(['waiting_at', 'sended_at', 'accepted_at', 'processed_at']);

            $table->timestamp('executed_at')->nullable()->index();
            $table->timestamps();
        });
    }
};