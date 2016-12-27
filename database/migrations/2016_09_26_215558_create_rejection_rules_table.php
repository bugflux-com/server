<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRejectionRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rejection_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->unsignedInteger('project_id');
            $table->foreign('project_id')
                ->references('id')->on('projects')
                ->onDelete('cascade');
            $columns = [
                'version',
                'system',
                'language',
                'hash',
                'name',
                'environment',
                'stack_trace',
                'message',
                'client_id',
                'client_ip',
            ];
            foreach ($columns as $column) {
                $table->string($column);
            }
            // in future action
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
        Schema::drop('rejection_rules');
    }
}
