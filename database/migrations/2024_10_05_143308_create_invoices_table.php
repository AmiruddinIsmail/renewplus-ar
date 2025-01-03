<?php

use App\Models\Enums\InvoiceStatus;
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
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('reference_no')->unique();
            $table->date('issue_at');
            $table->date('due_at');
            $table->integer('subscription_amount');
            $table->integer('charge_amount')->default(0);
            $table->integer('credit_paid')->default(0);
            $table->integer('over_paid')->default(0);
            $table->bigInteger('amount')->default(0);
            $table->string('status')->default(InvoiceStatus::PENDING);
            $table->boolean('unresolved')->default(true);
            $table->integer('unresolved_amount')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('reference_no')->unique();
            $table->dateTime('paid_at');
            $table->integer('amount');
            $table->boolean('unresolved')->default(true);
            $table->integer('unresolved_amount')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
