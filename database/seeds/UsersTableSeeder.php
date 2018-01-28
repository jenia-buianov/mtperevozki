<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Evghenii',
            'lastname' => 'Cuceruc',
            'email' => 'evghenii_cuceruc@gmail.com',
            'password' => bcrypt('123456'),
			'group_id'=>1
        ]);    
	}
}
