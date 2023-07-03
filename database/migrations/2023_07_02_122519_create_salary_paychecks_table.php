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
        Schema::create('salary_paychecks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id')->index();
            $table->unsignedFloat('amount');
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
        Schema::dropIfExists('salary_paychecks');
    }
};
