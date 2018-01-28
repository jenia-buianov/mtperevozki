<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            ['titleKey' => 'admin_group'],
			['titleKey' => 'moder_group'],
			['titleKey' => 'user_group'],
        ]);
    }
}
