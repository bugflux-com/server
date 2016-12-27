<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorDuplicatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('error_duplicates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('error_id');
            $table->foreign('error_id')
                ->references('id')->on('errors')
                ->onDelete('cascade');
            $table->string('code', 8);
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
        Schema::drop('error_duplicates');
    }
}
