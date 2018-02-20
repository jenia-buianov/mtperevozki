<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToSubscribes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscribes',function (Blueprint $table){
            $table->integer('days')->default(3)->after('transport_type');
            $table->string('title')->nullable()->after('url');
        });
    }
}
