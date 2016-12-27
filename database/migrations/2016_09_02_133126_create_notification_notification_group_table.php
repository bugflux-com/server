<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationNotificationGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_notification_group', function (Blueprint $table) {
            $table->unsignedInteger('notification_id');
            $table->foreign('notification_id')
                ->references('id')->on('notifications')
                ->onDelete('cascade');
            $table->unsignedInteger('notification_group_id');
            $table->foreign('notification_group_id')
                ->references('id')->on('notification_groups')
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
        Schema::drop('notification_notification_group');
    }
}
