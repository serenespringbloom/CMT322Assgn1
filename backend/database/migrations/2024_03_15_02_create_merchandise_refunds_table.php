<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchandiseRefundsTable extends Migration
{
    public function up()
    {
        Schema::create('merchandise_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('merchandise_orders', 'order_id');
            $table->text('reason');
            $table->decimal('refund_amount', 10, 2);
            $table->string('status')->default('PENDING');
            $table->foreignId('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('merchandise_refunds');
    }
}