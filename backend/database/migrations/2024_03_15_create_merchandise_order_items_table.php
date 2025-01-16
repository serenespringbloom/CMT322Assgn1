<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('merchandise_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('merchandise_orders', 'order_id')->onDelete('cascade');
            $table->foreignId('merchandise_id')->constrained('merchandise', 'merchandise_id');
            $table->string('size');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('merchandise_order_items');
    }
};