<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesSitemapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages_sitemap', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id')->unsigned();
            $table->integer('sitemap_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('pages_sitemap', function (Blueprint $table) {
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('sitemap_id')->references('id')->on('sitemap')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages_sitemap');
    }
}
