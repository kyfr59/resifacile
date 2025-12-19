<?php

use App\Models\Customer;
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
        Schema::create('mail_receives', function (Blueprint $table) {
            $table->id();
            $table->string('message_id');
            $table->string('from');
            $table->string('subject');
            $table->longText('body');
            $table->string('type')->nullable();
            $table->foreignIdFor(Customer::class)
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_receives');
    }
};
