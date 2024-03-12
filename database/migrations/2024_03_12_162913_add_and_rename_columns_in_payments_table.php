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
        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('invoice_id', 'transaction_id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('staff_id')->index();
            $table->longText('payment_receipt');
            $table->string('status', 20);

            $table->date('payment_date')->useCurrent()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('transaction_id', 'invoice_id');
            $table->dropColumn('user_id');
            $table->dropColumn('staff_id');
            $table->dropColumn('payment_receipt');
            $table->dropColumn('status');
            
            $table->date('payment_date')->change();
        });
    }
};
