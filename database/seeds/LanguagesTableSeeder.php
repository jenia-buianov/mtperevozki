<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$langs = [
            ['title' => 'Русский','code'=>'ru','active'=>1,'default'=>1],
            ['title' => 'English','code'=>'en','active'=>0,'default'=>0],
        ];
		
        DB::table('languages')->insert($langs);
		
		for($i=0;$i<count($langs);$i++){
			$path = dirname(__FILE__).'/../../resources/lang/'.$langs[$i]['code'];
			if (!file_exists($path)){
				mkdir($path);
			}
		}
		
    }
}
