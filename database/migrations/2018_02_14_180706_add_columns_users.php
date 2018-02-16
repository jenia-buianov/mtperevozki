<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone',15)->nullable();
            $table->string('phone2',15)->nullable();
            $table->string('phone3',15)->nullable();
            $table->string('skype',64)->nullable();
        });
    }
}
