<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('candidate_profiles', function (Blueprint $table) { $table->id(); $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete(); $table->string('phone',30)->nullable(); $table->string('headline',180)->nullable(); $table->string('location',180)->nullable(); $table->text('bio')->nullable(); $table->json('skills')->nullable(); $table->string('resume_path')->nullable(); $table->string('avatar_path')->nullable(); $table->string('linkedin_url')->nullable(); $table->string('portfolio_url')->nullable(); $table->timestamps(); });
  Schema::create('employer_profiles', function (Blueprint $table) { $table->id(); $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete(); $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete(); $table->string('job_title',120)->nullable(); $table->string('phone',30)->nullable(); $table->string('location',180)->nullable(); $table->timestamps(); });
 }
 public function down(): void { Schema::dropIfExists('employer_profiles'); Schema::dropIfExists('candidate_profiles'); }
};
