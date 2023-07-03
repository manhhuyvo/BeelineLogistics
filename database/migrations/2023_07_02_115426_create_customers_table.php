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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id', 255)->unique();
            $table->unsignedBigInteger('staff_id')->index();
            $table->string('full_name', 255);
            $table->string('phone', 50);
            $table->string('address', 255);
            $table->json('default_sender');
            $table->json('default_receiver');
            $table->string('type', 20);
            $table->string('company', 100);
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
        Schema::dropIfExists('customers');
    }
};
