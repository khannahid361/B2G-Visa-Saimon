<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMissingStatusLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('missing_status_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('walkin_app_info_id');
            $table->integer('changer_id')->nullable();
            $table->integer('status')->nullable();
            $table->date('status_date')->nullable();
            $table->string('status_time')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('missing_status_logs');
    }
}
