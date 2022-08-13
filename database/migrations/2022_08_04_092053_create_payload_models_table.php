<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayloadModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payload_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('brand_name');
            $table->string('model_name');
            $table->string('type');
            $table->binary('image')->nullable();
            $table->timestamps();
        });

        // Schema::create('drone_payload', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->unsignedBigInteger('drone_id');
        //     $table->unsignedBigInteger('payload_id');
        //     $table->timestamps();

        //     $table->unique(['drone_id', 'payload_id']);

        //     $table->foreign('drone_id')
        //         ->references('id')
        //         ->on('drone_models');

        //     $table->foreign('payload_id')
        //         ->references('id')
        //         ->on('payload_models');
        // });
        Schema::create('drone_payload_attachment', function (Blueprint $table) {
            $table->primary(['drone_id', 'payload_id']);
            $table->unsignedBigInteger('drone_id');
            $table->unsignedBigInteger('payload_id');
            $table->timestamps();

            // $table->unique(['drone_id', 'payload_id']);

            $table->foreign('drone_id')
                ->references('id')
                ->on('drone_models')
                ->onDelete('cascade');

            $table->foreign('payload_id')
                ->references('id')
                ->on('payload_models')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payload_models');
        Schema::dropIfExists('drone_payload_attachment');
    }
}
