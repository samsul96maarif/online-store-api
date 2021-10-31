<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComLogDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('com_log_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('com_log_id')->unsigned();
            $table->foreign('com_log_id')->references('id')->on('com_logs')->onDelete('cascade');
            $table->bigInteger('com_activity_table_id')->unsigned();
            $table->foreign('com_activity_table_id')->references('id')->on('com_activity_tables')->onDelete('cascade');
            $table->enum('operation', ['create', 'update', 'delete']);
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->unsignedInteger('sequence')->default(1);
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
        Schema::dropIfExists('com_log_details');
    }
}
