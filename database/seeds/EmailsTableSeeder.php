<?php

use Illuminate\Database\Seeder;

class EmailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Emails::create([
            'template_id'=>1,
            'type'=>'smtp',
            'email_fk'=>1
        ]);
    }
}
