<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
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
		
		$translations = [
            ['key' => 'admin_group','lang'=>'ru','text'=>'Администраторы','file'=>'main'],
            ['key' => 'moder_group','lang'=>'ru','text'=>'Модераторы','file'=>'main'],
            ['key' => 'user_group','lang'=>'ru','text'=>'Пользователи','file'=>'main'],
            ['key' => 'acp_translations','lang'=>'ru','text'=>'Переводы','file'=>'admin'],
            ['key' => 'acp_dashboard','lang'=>'ru','text'=>'Админ-панель','file'=>'admin'],
            ['key' => 'acp_search','lang'=>'ru','text'=>'Поиск','file'=>'admin'],
            ['key' => 'acp_edit','lang'=>'ru','text'=>'Редактировать','file'=>'admin'],
            ['key' => 'acp_delete','lang'=>'ru','text'=>'Удалить','file'=>'admin'],
            ['key' => 'acp_actions','lang'=>'ru','text'=>'Действия','file'=>'admin'],
        ];
		
        DB::table('translations')->insert($translations);
		
		$files = [];
		
		for($i=0;$i<count($translations);$i++){
			$path = dirname(__FILE__).'/../../resources/lang/'.$translations[$i]['lang'].'/'.$translations[$i]['file'].'.php';
			if (!file_exists($path)){
				$files[] = $path;
				$handle = fopen($path, "w");
				fwrite($handle, "<?php \n\n return [\n \t'".$translations[$i]['key']."' => '".$translations[$i]['text']."',");
				fclose($handle);
			}
			else{
				$handle = fopen($path, "a+");
				fwrite($handle, "\n \t'".$translations[$i]['key']."' => '".$translations[$i]['text']."',");
				fclose($handle);
			}	
		}
		
		for($i=0;$i<count($files);$i++){
			$handle = fopen($path, "a+");
				fwrite($handle, "\n ]; \n");
				fclose($handle);
		}
		
		DB::table('groups')->insert([
            ['titleKey' => 'admin_group'],
			['titleKey' => 'moder_group'],
			['titleKey' => 'user_group'],
        ]);
		
		 DB::table('users')->insert([
            'name' => 'Evghenii',
            'lastname' => 'Cuceruc',
            'email' => 'evghenii_cuceruc@gmail.com',
            'password' => bcrypt('123456'),
			'group_id'=>1
        ]);   
		
    }
}
