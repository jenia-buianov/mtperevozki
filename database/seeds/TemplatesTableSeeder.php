<?php

use Illuminate\Database\Seeder;

class TemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Templates::create([
            'title' => '{"ru":"Шаблон регистрации"}',
            'path' => 'templates.registration',
            'params' => json_encode([
                'name','email','lastname','token','subscribe_url','password'
            ])
        ]);
    }
}
