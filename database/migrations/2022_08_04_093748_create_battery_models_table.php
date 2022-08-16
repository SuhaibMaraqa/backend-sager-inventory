<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatteryModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('battery_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('drone_model_id');
            $table->string('brand_name');
            $table->string('model_name');
            $table->integer('maximum_num_of_cycles');
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('drone_model_id')->references('id')->on('drone_models')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('battery_models');
    }
}
