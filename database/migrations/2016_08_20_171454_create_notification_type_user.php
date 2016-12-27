<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTypeUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_type_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('notification_type_id');
            $table->foreign('notification_type_id')
                ->references('id')->on('notification_types')
                ->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            // Due to #13628, #3123 and #2815 nullable morphs isn't
            // officially supported (we cannot use morphs function).
            $table->unsignedInteger('wantable_id')->nullable();
            $table->string('wantable_type')->nullable();
            $table->index(['wantable_type', 'wantable_id']);

            $table->unique(['notification_type_id', 'user_id', 'wantable_id'], 'unique_key');
            $table->boolean('internal');
            $table->boolean('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notification_type_user');
    }
}
