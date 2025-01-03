<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credits', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('reference_no')->unique();
            $table->integer('amount');
            $table->boolean('unresolved')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('credit_invoice', function (Blueprint $table): void {
            $table->foreignId('credit_id')->constrained('credits')->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->integer('amount');
            $table->timestamps();
            $table->primary(['credit_id', 'invoice_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credits');
        Schema::dropIfExists('credit_invoice');
    }
};
