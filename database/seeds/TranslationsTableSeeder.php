<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
    }
}
