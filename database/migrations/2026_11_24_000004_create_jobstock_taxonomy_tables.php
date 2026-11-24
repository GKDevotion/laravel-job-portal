<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('skills', function(Blueprint $table){ $table->id(); $table->string('name',100)->unique(); $table->string('slug',120)->unique(); $table->boolean('is_active')->default(true)->index(); $table->timestamps(); });
  Schema::create('job_skill', function(Blueprint $table){ $table->foreignId('job_id')->constrained('job_listings')->cascadeOnDelete(); $table->foreignId('skill_id')->constrained()->cascadeOnDelete(); $table->primary(['job_id','skill_id']); });
  Schema::create('job_locations', function(Blueprint $table){ $table->id(); $table->string('city',120); $table->string('state',120)->nullable(); $table->string('country',120)->default('India'); $table->string('slug',180)->unique(); $table->boolean('is_active')->default(true)->index(); $table->timestamps(); });
 }
 public function down(): void { Schema::dropIfExists('job_locations'); Schema::dropIfExists('job_skill'); Schema::dropIfExists('skills'); }
};
