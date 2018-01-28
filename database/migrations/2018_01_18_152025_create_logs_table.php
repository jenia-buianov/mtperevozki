<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('page',255)->default('');
            $table->string('method',5)->default('');
            $table->longText('post');
            $table->string('country')->default('');
            $table->string('city')->default('');
            $table->string('os')->default('');
            $table->string('browser')->default('');
            $table->string('lang',3)->default('');
            $table->string('latitue',15)->default('');
            $table->string('long',15)->default('');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
