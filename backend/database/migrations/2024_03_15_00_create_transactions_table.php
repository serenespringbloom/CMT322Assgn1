<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->decimal('total_price', 10, 2);
            $table->string('transaction_type'); // 'TICKET' or 'MERCHANDISE'
            $table->string('payment_status')->default('PENDING'); // 'PENDING', 'COMPLETED', 'FAILED'
            $table->string('payment_method')->nullable(); // 'FPX', 'CREDIT_CARD', etc.
            $table->string('cust_email');
            $table->string('cust_name');
            $table->string('cust_phone');
            $table->string('status')->default('PENDING');
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};