<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // translatable, e.g. {"en": "Featured Listing Boost"}
            $table->json('description')->nullable();
            $table->enum('type', ['featured_job', 'job_credits'])->default('job_credits');
            $table->decimal('price', 12, 2); // stored in base currency
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();

            // What purchasing this plan grants:
            $table->unsignedInteger('credits')->nullable();       // for type = job_credits
            $table->unsignedInteger('featured_days')->nullable(); // for type = featured_job

            $table->boolean('status')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
    }
};
