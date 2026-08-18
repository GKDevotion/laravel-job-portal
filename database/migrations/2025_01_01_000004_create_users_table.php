<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Auth fields
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->rememberToken();

            // Profile
            $table->string('avatar')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('designation')->nullable(); // current job title
            $table->text('bio')->nullable();
            $table->string('experience_level')->nullable(); // entry, mid, senior
            $table->decimal('expected_salary', 12, 2)->nullable();
            $table->string('resume')->nullable(); // default/primary resume file path

            // Social links
            $table->string('linkedin')->nullable();
            $table->string('github')->nullable();
            $table->string('portfolio_url')->nullable();

            // Visibility & status
            $table->boolean('is_profile_public')->default(true);
            $table->boolean('is_open_to_work')->default(true);
            $table->boolean('status')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
