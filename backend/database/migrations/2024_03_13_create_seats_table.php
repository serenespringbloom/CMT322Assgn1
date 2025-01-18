<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->string('seat_id');
            $table->unsignedBigInteger('event_id');
            $table->boolean('is_booked')->default(false);
            $table->unsignedBigInteger('ticket_id')->nullable();
            $table->boolean('enabled_flag')->default(true);
            $table->string('disabled_by')->nullable();
            $table->timestamp('disabled_at')->nullable();
            $table->timestamps();

            $table->unique(['seat_id', 'event_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('seats');
    }
}; 