<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalkinAppChecklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('walkin_app_checklists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('walkin_app_info_id');
            $table->foreign('walkin_app_info_id')->references('id')->on('walkin_app_infos');
//            $table->unsignedBigInteger('checklist_id');
//            $table->foreign('checklist_id')->references('id')->on('checklists');
//            $table->integer('walkin_app_info_id')->nullable();
            $table->integer('check_list_id')->nullable();
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
        Schema::dropIfExists('walkin_app_checklists');
    }
}
