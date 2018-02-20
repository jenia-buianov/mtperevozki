<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToSubscribes2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscribes',function (Blueprint $table){
            $table->integer('import')->nullable()->after('title');
            $table->integer('export')->nullable()->after('import');
        });
    }
}
