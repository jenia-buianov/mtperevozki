<?php

use Illuminate\Database\Seeder;

class LandingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('landing')->insert([
            ['title' => 'Заказ транспорта','view'=>'cargo','order'=>1,'active'=>1],
            ['title' => 'Категории','view'=>'categories','order'=>1,'active'=>1],
            ['title' => 'Почему мы','view'=>'why_we','order'=>1,'active'=>1],
            ['title' => 'Актуальные предложения','view'=>'actual','order'=>1,'active'=>1],
            ['title' => 'Статистика','view'=>'statistics','order'=>1,'active'=>1],
        ]);
    }
}
