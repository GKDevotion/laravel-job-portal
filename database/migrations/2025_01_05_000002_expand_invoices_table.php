<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('pricing_plan_id')->nullable()->after('company_id')->constrained('pricing_plans')->nullOnDelete();
            $table->foreignId('job_listing_id')->nullable()->after('pricing_plan_id')->constrained('job_listings')->nullOnDelete();

            $table->decimal('subtotal', 12, 2)->default(0)->after('amount');
            $table->decimal('tax_rate', 5, 2)->default(0)->after('subtotal');   // percentage, e.g. 10.00 = 10%
            $table->decimal('tax_amount', 12, 2)->default(0)->after('tax_rate');

            $table->string('billing_name')->nullable()->after('tax_amount');
            $table->text('billing_address')->nullable()->after('billing_name');
            $table->text('notes')->nullable()->after('billing_address');
            $table->date('due_date')->nullable()->after('notes');

            $table->foreignId('marked_paid_by')->nullable()->after('paid_at')->constrained('admins')->nullOnDelete();
            $table->string('payment_reference')->nullable()->after('marked_paid_by'); // bank transfer ref, manual note, etc.
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->string('description');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('amount', 12, 2); // quantity * unit_price, denormalized for easy PDF rendering
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pricing_plan_id');
            $table->dropConstrainedForeignId('job_listing_id');
            $table->dropConstrainedForeignId('marked_paid_by');
            $table->dropColumn([
                'subtotal', 'tax_rate', 'tax_amount', 'billing_name',
                'billing_address', 'notes', 'due_date', 'payment_reference',
            ]);
        });
    }
};
