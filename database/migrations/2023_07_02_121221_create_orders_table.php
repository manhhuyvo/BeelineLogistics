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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('staff_id')->index();
            $table->string('sender_name', 255);
            $table->string('sender_phone', 50);
            $table->string('sender_address', 255);
            $table->string('receiver_name', 255);
            $table->string('receiver_phone', 50);
            $table->string('receiver_address', 255);
            $table->json('config_type');
            $table->string('tracking_numbers', 255); // 1092739013,12031203,102371283s
            $table->unsignedFloat('total_amount');
            $table->string('status', 20);
            $table->string('note', 255);
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
        Schema::dropIfExists('orders');
    }
};
