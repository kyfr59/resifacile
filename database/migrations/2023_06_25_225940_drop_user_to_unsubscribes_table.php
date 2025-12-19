<?php

use App\Models\Customer;
use App\Models\User;
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
        Schema::table('unsubscribes', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->foreignIdFor(Customer::class)
                ->constrained()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unsubscribes', function (Blueprint $table) {
            $table->dropColumn('customer_id');
            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();
        });
    }
};
