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
        Schema::create('salary_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paycheck_id')->index();
            $table->unsignedFloat('amount');
            $table->string('description', 255);
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
        Schema::dropIfExists('salary_items');
    }
};
