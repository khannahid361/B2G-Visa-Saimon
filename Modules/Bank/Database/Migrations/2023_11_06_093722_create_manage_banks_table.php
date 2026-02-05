<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManageBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_banks', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name');
            $table->enum('bank_type',['1','2'])->default('1')->comment = "1=bank, 2=mobile bank , 3=condition";
            $table->enum('status',['1','2'])->default('1')->comment = "1=Active, 2=Inactive";
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
        Schema::dropIfExists('manage_banks');
    }
}
