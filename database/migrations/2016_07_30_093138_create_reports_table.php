<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id', 64);
            $table->string('client_ip', 45);
            $table->unsignedInteger('error_id');
            $table->foreign('error_id')
                ->references('id')->on('errors')
                ->onDelete('cascade');
            $table->integer('system_id')->unsigned();
            $table->foreign('system_id')
                ->references('id')->on('systems')
                ->onDelete('cascade');
            $table->integer('language_id')->unsigned();
            $table->foreign('language_id')
                ->references('id')->on('languages')
                ->onDelete('cascade');
            $table->integer('version_id')->unsigned();
            $table->foreign('version_id')
                ->references('id')->on('versions')
                ->onDelete('cascade');
            $table->integer('flat_report_id')->unsigned()->nullable();
            $table->foreign('flat_report_id')
                ->references('id')->on('flat_reports')
                ->onDelete('set null');
            $table->string('name');
            $table->text('stack_trace');
            $table->text('message')->nullable();
            $table->date('reported_at_date'); // report can be sent (reported_at) and processed after a few days (created_at)
            $table->datetime('reported_at'); // same context as above, but full datetime
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
        Schema::drop('reports');
    }
}
