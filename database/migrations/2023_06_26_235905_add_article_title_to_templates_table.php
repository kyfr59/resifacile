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
        Schema::table('templates', function (Blueprint $table) {
            $table->string('object')->nullable();
            $table->string('title')->nullable();
            $table->mediumText('article')->nullable();
            $table->string('seo_title')->nullable();
            $table->mediumText('seo_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn('object');
            $table->dropColumn('title');
            $table->dropColumn('article');
            $table->dropColumn('seo_title');
            $table->dropColumn('seo_description');
        });
    }
};
