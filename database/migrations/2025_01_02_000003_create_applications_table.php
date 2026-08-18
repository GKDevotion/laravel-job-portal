<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_listing_id')->constrained('job_listings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // job seeker
            $table->string('resume_path'); // resume used for this specific application
            $table->text('cover_letter')->nullable();

            $table->enum('status', [
                'submitted', 'viewed', 'shortlisted', 'interview', 'offered', 'hired', 'rejected', 'withdrawn',
            ])->default('submitted');

            $table->foreignId('reviewed_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->text('internal_notes')->nullable(); // visible to company/employees only
            $table->timestamp('status_updated_at')->nullable();

            $table->timestamps();

            $table->unique(['job_listing_id', 'user_id']); // one application per job per seeker
        });

        Schema::create('saved_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('job_listing_id')->constrained('job_listings')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'job_listing_id']);
        });

        Schema::create('company_followers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'company_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_followers');
        Schema::dropIfExists('saved_jobs');
        Schema::dropIfExists('job_applications');
    }
};
