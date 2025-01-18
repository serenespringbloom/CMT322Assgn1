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
        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id('event_id'); // Primary key
                $table->string('event_title');
                $table->string('event_subtitle')->nullable();
                $table->string('event_venue');
                $table->dateTime('event_datetime');
                $table->text('event_about')->nullable();
                $table->string('event_email');
                $table->string('event_phone');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('last_updated_by')->nullable();
                $table->timestamp('created_at')->nullable(); // Manually add created_at
                $table->timestamp('last_updated_at')->nullable(); // Manually add last_updated_at
            });
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
