<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComActivityTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('com_activity_tables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('com_table_id')->unsigned();
            $table->foreign('com_table_id')->references('id')->on('com_tables')->onDelete('cascade');
            $table->bigInteger('com_activity_id')->unsigned();
            $table->foreign('com_activity_id')->references('id')->on('com_activities')->onDelete('cascade');
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
        Schema::dropIfExists('com_activity_tables');
    }
}
