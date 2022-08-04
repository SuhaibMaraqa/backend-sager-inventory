<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatteryInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('battery_inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('battery_model_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('serial_number');
            $table->date('purchase_date');
            $table->date('registration_date');
            $table->enum('physical_status', ['Airworthy', 'Maintenance', 'Retired']);
            $table->integer('number_of_cycles');
            $table->boolean('activation');
            $table->timestamps();

            $table->foreign('battery_model_id')->references('id')->on('battery_models')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('battery_inventories');
    }
}
