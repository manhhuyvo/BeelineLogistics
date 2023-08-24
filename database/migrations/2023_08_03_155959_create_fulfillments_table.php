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
        Schema::create('fulfillments', function (Blueprint $table) {       
            $table->id();
            $table->json('product_configs');
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('staff_id')->index();
            $table->string('name', 255);
            $table->string('phone', 50);
            $table->string('address', 255);
            $table->string('address2', 255);
            $table->string('suburb', 255);
            $table->string('state', 100);
            $table->string('postcode', 10);
            $table->string('country', 100);
            $table->string('shipping_type', 50);
            $table->unsignedFloat('postage');
            $table->string('postage_unit', 10);
            $table->string('tracking_number', 255);
            $table->unsignedFloat('total_product_amount');
            $table->string('product_unit', 10);
            $table->unsignedFloat('total_labour_amount');
            $table->string('labour_unit', 10);
            $table->string('fulfillment_status', 20);
            $table->string('product_payment_status', 20);
            $table->string('labour_payment_status', 20);
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
        Schema::dropIfExists('fulfillments');
    }
};
