<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_fulfillment_maps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fulfillment_id');
            $table->unsignedBigInteger('ticket_id');

            // Foreign Keys
            $table->foreign('fulfillment_id')->references('id')->on('fulfillments')->onDelete('cascade');
            $table->foreign('ticket_id')->references('id')->on('support_tickets')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_fulfillment_maps');
    }
};
