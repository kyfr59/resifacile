<?php

use App\Enums\SubscriptionStatus;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('designation');
            $table->unsignedInteger('price');
            $table->timestamp('cancellation_request_at')
                ->nullable();
            $table->timestamp('current_period_end_at')
                ->nullable();
            $table->unsignedInteger('attempts')
                ->default(0);
            $table->string('status')
                ->default(SubscriptionStatus::TRIAL->value);
            $table->jsonb('meta_data');
            $table->foreignIdFor(Customer::class)
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
