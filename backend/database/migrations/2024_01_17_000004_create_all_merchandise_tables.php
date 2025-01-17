<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop all existing tables first
        Schema::dropIfExists('merchandise_refunds');
        Schema::dropIfExists('merchandise_order_items');
        Schema::dropIfExists('merchandise_orders');
        Schema::dropIfExists('merchandises');

        // 1. Create merchandises table first
        Schema::create('merchandises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image_url')->nullable();
            $table->json('sizes_inventory')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Create merchandise_orders table
        Schema::create('merchandise_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchandise_id')->constrained('merchandises');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('size');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('PENDING');
            $table->timestamps();
        });

        // 3. Create merchandise_order_items table
        Schema::create('merchandise_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('merchandise_orders')->onDelete('cascade');
            $table->foreignId('merchandise_id')->constrained('merchandises')->onDelete('cascade');
            $table->string('size');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        });

        // 4. Create merchandise_refunds table
        Schema::create('merchandise_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('merchandise_orders')->onDelete('cascade');
            $table->decimal('refund_amount', 10, 2);
            $table->string('reason');
            $table->string('status')->default('PENDING');
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('merchandise_refunds');
        Schema::dropIfExists('merchandise_order_items');
        Schema::dropIfExists('merchandise_orders');
        Schema::dropIfExists('merchandises');
    }
}; 