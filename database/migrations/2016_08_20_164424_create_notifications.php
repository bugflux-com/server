<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->text('json')->nullable();

            // Due to #13628, #3123 and #2815 nullable morphs isn't
            // officially supported (we cannot use morphs function).
            $table->unsignedInteger('notificable_id')->nullable();
            $table->string('notificable_type')->nullable();
            $table->index(['notificable_type', 'notificable_id']);

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
        Schema::drop('notifications');
    }
}
