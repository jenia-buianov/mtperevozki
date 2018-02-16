<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('permisions', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn('url');
            $table->dropColumn('group_id');
        });
    }

}
