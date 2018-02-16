<?php

use Illuminate\Database\Seeder;

class EmailsFromTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\EmailsFrom::create([
            'type'=>465,
            'host'=>'s1.magicnet.md',
            'login'=>'registration@mtperevozki.ru',
            'password'=>'I^D*B48,A(8=',
            'security'=>'tls'
        ]);
    }
}
