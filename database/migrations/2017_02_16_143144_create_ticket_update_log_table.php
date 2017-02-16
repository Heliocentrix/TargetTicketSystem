<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketUpdateLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_update_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id');
            $table->decimal('cost',10,2);
            $table->timestamp('created_at');
            $table->integer('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ticket_update_log');
    }
}
