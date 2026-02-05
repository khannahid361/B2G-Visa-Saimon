<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('walkin_app_info_id');
            $table->integer('payment_status');
            $table->double('paid_amount')->nullable();
            $table->double('due_amount')->nullable();
            $table->double('discount')->nullable();
            $table->text('payment_note')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_time')->nullable();
            $table->integer('payment_by')->nullable();
            $table->double('service_charge');
            $table->double('visa_fee');
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
        Schema::dropIfExists('payments');
    }
}
