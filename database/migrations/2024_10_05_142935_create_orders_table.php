<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('reference_no')->unique();
            $table->integer('tenure');
            $table->integer('subscription_amount');
            $table->date('contract_at')->nullable();

            $table->string('payment_gateway')->nullable();
            $table->string('payment_reference')->nullable();
            $table->boolean('active')->default(true);
            $table->dateTime('completed_at')->nullable();
            $table->boolean('notify')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
