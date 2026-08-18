<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');        // e.g. "English"
            $table->string('code', 10)->unique(); // e.g. "en"
            $table->string('locale', 10);  // e.g. "en_US"
            $table->string('flag')->nullable();   // flag icon path/class
            $table->boolean('is_rtl')->default(false);
            $table->boolean('is_default')->default(false);
            $table->boolean('status')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');        // e.g. "US Dollar"
            $table->string('code', 10)->unique(); // e.g. "USD"
            $table->string('symbol', 10);  // e.g. "$"
            $table->decimal('exchange_rate', 15, 6)->default(1); // relative to base currency
            $table->enum('symbol_position', ['before', 'after'])->default('before');
            $table->boolean('is_default')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('languages');
    }
};
