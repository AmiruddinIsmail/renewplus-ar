<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('status')->default('active');

            $table->integer('tenure');
            $table->integer('subscription_fee');
            $table->dateTime('contract_at')->nullable();

            $table->string('payment_gateway')->nullable();
            $table->string('payment_reference')->nullable();

            $table->dateTime('completed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
