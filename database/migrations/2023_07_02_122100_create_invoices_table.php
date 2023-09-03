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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('staff_id')->index();
            $table->string('reference', 255);
            $table->unsignedFloat('total_amount');
            $table->unsignedFloat('outstanding_amount');
            $table->string('unit', 10);
            $table->timestamp('due_date')->useCurrent();
            $table->string('status', 20);
            $table->string('payment_status', 20);
            $table->longText('note');
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
        Schema::dropIfExists('invoices');
    }
};
