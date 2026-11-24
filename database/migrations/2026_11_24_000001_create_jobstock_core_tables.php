<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->text('description')->nullable();
            $table->string('icon', 100)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 180);
            $table->string('slug', 200)->unique();
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->string('location', 180)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('job_categories')->nullOnDelete();
            $table->string('title', 180);
            $table->string('slug', 220)->unique();
            $table->string('location', 180)->nullable();
            $table->string('job_type', 40)->default('Full Time');
            $table->string('workplace_type', 30)->default('On-site');
            $table->string('experience_level', 40)->nullable();
            $table->decimal('salary_min', 12, 2)->nullable();
            $table->decimal('salary_max', 12, 2)->nullable();
            $table->string('salary_currency', 3)->default('USD');
            $table->longText('description')->nullable();
            $table->longText('requirements')->nullable();
            $table->longText('responsibilities')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->string('status', 24)->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->date('expires_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'published_at']);
            $table->index(['category_id', 'job_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_listings');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('job_categories');
    }
};
