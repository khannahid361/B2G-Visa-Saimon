<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalkinAppDetailsChecklestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('walkin_app_details_checklests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('walkin_app_details_id');
            $table->foreign('walkin_app_details_id')->references('id')->on('walkin_app_details');
//            $table->unsignedBigInteger('c_check_list_id');
//            $table->foreign('c_check_list_id')->references('id')->on('checklists');
//            $table->integer('walkin_app_details_id');
            $table->integer('c_check_list_id');
            $table->string('file')->nullable();
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
        Schema::dropIfExists('walkin_app_details_checklests');
    }
}
