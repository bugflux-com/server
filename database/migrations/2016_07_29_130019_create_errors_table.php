<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('errors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash', 64);
            $table->string('name');
            $table->unsignedInteger('project_id');
            $table->foreign('project_id')
                ->references('id')->on('projects')
                ->onDelete('cascade');
            $table->integer('environment_id')->unsigned();
            $table->foreign('environment_id')
                ->references('id')->on('environments');
            $table->timestamps();

            // Hash is unique in project scope.
            // This cover case when library is used by a few projects
            // and we report error caused by the library (which produce the same hash).
            // Environment id should also not repeat in the database.
            $table->unique(['hash', 'project_id', 'environment_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('errors');
    }
}
