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
        Schema::create('fulfillment_product_payment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fulfillment_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('staff_id');
            $table->unsignedFloat('amount');
            $table->string('description', 255);
            $table->longText('payment_receipt'); // Path where payment receipt image is stored
            $table->unsignedInteger('payment_method');
            $table->string('status', 20);
            $table->date('payment_date')->useCurrent();
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
        Schema::dropIfExists('fulfillment_product_payment');
    }
};
