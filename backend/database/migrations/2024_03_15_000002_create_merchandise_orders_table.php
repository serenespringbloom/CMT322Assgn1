<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchandiseOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('merchandise_orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_status')->default('PENDING');
            $table->string('order_status')->default('PENDING');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('merchandise_orders');
    }
}