<?php

use App\Models\Invoice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('reference_no')->nullable();
            $table->date('issue_at');
            $table->date('due_at');
            $table->integer('subscription_fee');
            $table->integer('charge_fee')->default(0);
            $table->integer('credit_paid')->default(0);
            $table->integer('over_paid')->default(0);

            $table->boolean('unresolved')->default(true);
            $table->integer('unresolved_amount')->nullable();
            $table->string('status')->default(Invoice::STATUS_PENDING);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('reference_no');
            $table->dateTime('paid_at');
            $table->integer('amount');
            $table->boolean('unresolved')->default(true);
            $table->integer('unresolved_amount')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('invoice_payment', function (Blueprint $table): void {
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->integer('amount');
            $table->timestamps();
            $table->primary(['invoice_id', 'payment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_payment');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payments');
    }
};
