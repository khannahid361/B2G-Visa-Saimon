<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalkinAppDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('walkin_app_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('walkin_app_info_id');
            $table->foreign('walkin_app_info_id')->references('id')->on('walkin_app_infos');
            $table->string('c_name');
            $table->string('c_passport_number');
            $table->tinyInteger('c_pp_number_status')->default(0)->comment = "1 = Ready for delivery, 2= Delivered";
            $table->string('c_passport_number_two')->nullable();
            $table->tinyInteger('c_pp_number_two_status')->default(0)->comment = "1 = Ready for delivery, 2= Delivered";
            $table->string('c_passport_number_three')->nullable();
            $table->tinyInteger('c_pp_number_three_status')->default(0)->comment = "1 = Ready for delivery, 2= Delivered";
            $table->string('c_phone');
            $table->string('c_email');
            $table->unsignedBigInteger('c_visaType_id');
            $table->foreign('c_visaType_id')->references('id')->on('visa_types');
            $table->unsignedBigInteger('c_visa_category');
            $table->foreign('c_visa_category')->references('id')->on('checklists');
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
        Schema::dropIfExists('walkin_app_details');
    }
}
