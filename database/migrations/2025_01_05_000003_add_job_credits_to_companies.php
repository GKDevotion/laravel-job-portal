<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Nullable = unlimited/untracked (e.g. for companies onboarded before credits existed).
            // Once a company buys a credit pack for the first time, this becomes a real number
            // and starts being enforced when they try to submit a job for review.
            $table->unsignedInteger('job_posting_credits')->nullable()->after('currency_id');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('job_posting_credits');
        });
    }
};
