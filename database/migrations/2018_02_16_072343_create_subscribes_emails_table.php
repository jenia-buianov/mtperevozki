<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribesEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribes_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subscribe_id')->unsigned();
            $table->integer('email_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('subscribes_emails', function (Blueprint $table) {
            $table->foreign('email_id')->references('id')->on('emails')->onDelete('cascade');
            $table->foreign('subscribe_id')->references('id')->on('subscribes')->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribes_emails');
    }
}
