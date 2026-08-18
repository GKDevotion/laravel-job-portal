<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();

            // Auth fields
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->rememberToken();

            // HR fields
            $table->string('avatar')->nullable();
            $table->string('job_title')->nullable(); // e.g. "HR Manager", "Recruiter"
            $table->enum('department', ['hr', 'recruitment', 'management', 'other'])->default('recruitment');

            // Invited-by / onboarding workflow
            $table->foreignId('invited_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->boolean('is_owner')->default(false); // the employee record mirroring the company's primary contact
            $table->boolean('status')->default(true);    // active/suspended by company admin

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employee_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_password_reset_tokens');
        Schema::dropIfExists('employees');
    }
};
