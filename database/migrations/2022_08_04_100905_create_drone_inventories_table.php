<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDroneInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drone_inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('drone_model_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('serial_number');
            $table->date('purchase_date');
            $table->date('registration_date');
            $table->boolean('insurance_status');
            $table->enum('physical_status', ['Airworthy', 'Maintenance', 'Retired']);
            $table->boolean('activation');
            $table->timestamps();

            $table->foreign('drone_model_id')->references('id')->on('drone_models')->onDelete('cascade');
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
        Schema::dropIfExists('drone_inventories');
    }
}
