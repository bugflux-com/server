<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlatReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flat_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('project');
            $table->string('version');
            $table->string('system');
            $table->string('language');
            $table->string('hash', 64);
            $table->string('name');
            $table->string('environment');
            $table->text('stack_trace');
            $table->text('message')->nullable();
            $table->string('client_id', 64);
            $table->string('client_ip', 45);
            $table->dateTime('imported_at')->nullable();
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
        Schema::drop('flat_reports');
    }
}
