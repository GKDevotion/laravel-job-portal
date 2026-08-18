<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('posted_by')->nullable()->constrained('employees')->nullOnDelete(); // which employee posted it
            $table->foreignId('job_category_id')->constrained('job_categories')->restrictOnDelete();
            $table->foreignId('job_type_id')->constrained('job_types')->restrictOnDelete();
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();

            // Core content (translatable JSON columns so each job can be authored per-locale)
            $table->json('title');
            $table->string('slug')->unique();
            $table->json('description');
            $table->json('responsibilities')->nullable();
            $table->json('requirements')->nullable();
            $table->json('benefits')->nullable();

            // Compensation
            $table->decimal('salary_min', 12, 2)->nullable();
            $table->decimal('salary_max', 12, 2)->nullable();
            $table->enum('salary_period', ['hourly', 'daily', 'weekly', 'monthly', 'yearly'])->default('monthly');
            $table->boolean('is_salary_negotiable')->default(false);

            // Location
            $table->enum('work_mode', ['onsite', 'remote', 'hybrid'])->default('onsite');
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Requirements
            $table->string('experience_level')->nullable(); // entry, mid, senior, lead
            $table->integer('vacancies')->default(1);
            $table->date('application_deadline')->nullable();

            // Publication workflow & status
            $table->enum('status', ['draft', 'pending_review', 'published', 'closed', 'expired', 'rejected'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();

            // Engagement counters (denormalized for fast listing pages)
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('applications_count')->default(0);

            // SEO
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'published_at']);
        });

        // Many-to-many: jobs <-> skills
        Schema::create('job_listing_skill', function (Blueprint $table) {
            $table->foreignId('job_listing_id')->constrained('job_listings')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->primary(['job_listing_id', 'skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_listing_skill');
        Schema::dropIfExists('job_listings');
    }
};
