<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalkinAppInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('walkin_app_infos', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueKey')->unique();
            $table->tinyInteger('walkIn_app_type')->comment = "1=General, 2=Travel Agent, 3=Corporates";
            $table->string('name')->nullable();
            $table->text('information')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('p_name');
            $table->string('passport_number');
            $table->tinyInteger('pp_number_status')->default(0)->comment = "1 = Ready for delivery, 2= Delivered";
            $table->string('pp_number_two')->nullable();
            $table->tinyInteger('pp_number_two_status')->default(0)->comment = "1 = Ready for delivery, 2= Delivered";
            $table->string('pp_number_three')->nullable();
            $table->tinyInteger('pp_number_three_status')->default(0)->comment = "1 = Ready for delivery, 2= Delivered";
            $table->string('phone');
            $table->string('email');
            $table->tinyInteger('app_status')->default(1)->comment = "1=save App, 2= Hold App, 3 = Online App";
            $table->tinyInteger('status')->default(1)->comment = "1 ='Application Received', 2 ='Application in Processing', 3 = 'Required Missing Documents', 4 = 'Submitted to embassy', 5 = 'Ready for delivery', 6 = 'Delivered', 7 = 'Reject'";
            $table->unsignedBigInteger('visaType_id');
            $table->foreign('visaType_id')->references('id')->on('visa_types');
            $table->unsignedBigInteger('visa_category');
            $table->foreign('visa_category')->references('id')->on('checklists');
            $table->date('app_received_date')->nullable();
            $table->string('app_received_time')->nullable();
            $table->integer('app_received_user')->nullable();
            $table->date('app_processing_date')->nullable();
            $table->string('app_processing_time')->nullable();
            $table->integer('app_processing_user')->nullable();
            $table->date('app_missing_documents_date')->nullable();
            $table->string('app_missing_documents_time')->nullable();
            $table->integer('app_missing_documents_user')->nullable();
            $table->date('app_submitted_embassy_date')->nullable();
            $table->string('app_submitted_embassy_time')->nullable();
            $table->integer('app_submitted_embassy_user')->nullable();
            $table->date('app_ready_for_delivery_date')->nullable();
            $table->string('app_ready_for_delivery_time')->nullable();
            $table->integer('app_ready_for_delivery_user')->nullable();
            $table->date('app_delivered_date')->nullable();
            $table->string('app_delivered_time')->nullable();
            $table->integer('app_delivered_user')->nullable();
            $table->date('app_reject_date')->nullable();
            $table->string('app_reject_time')->nullable();
            $table->integer('app_reject_user')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('payment_status')->default(1)->comment = "3 = Partial,2 = Full,1 = Due";
            $table->double('paid_amount')->nullable();
            $table->double('due_amount')->nullable();
            $table->double('discount')->nullable();
            $table->double('payment_note')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->integer('payment_by')->nullable();
            $table->double('scheduleDate')->nullable();
            $table->string('scheduleTime')->nullable();
            $table->date('date')->nullable();
            $table->string('barcode')->nullable();
            $table->string('screen_short')->nullable();
            $table->string('discounted_by')->nullable();
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
        Schema::dropIfExists('walkin_app_infos');
    }
}
