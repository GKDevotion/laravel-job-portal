<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            // Auth fields
            $table->string('company_name');
            $table->string('slug')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->rememberToken();

            // Profile fields
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->text('about')->nullable();
            $table->string('website')->nullable();
            $table->string('industry')->nullable();
            $table->string('team_size')->nullable(); // e.g. "1-10", "11-50"
            $table->year('founded_year')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Social links
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();

            // Verification / approval workflow
            $table->boolean('is_verified')->default(false);
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();

            // Status & plan
            $table->boolean('status')->default(true); // active/suspended
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('company_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_password_reset_tokens');
        Schema::dropIfExists('companies');
    }
};
