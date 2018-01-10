<?php

use Illuminate\Database\Seeder;

class UserTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_type')->insert([
            'name' => "Cajero/a",                        
        ]);

        DB::table('user_type')->insert([
            'name' => "Administrador",                        
        ]);

        DB::table('user_type')->insert([
            'name' => "Super Administrador",                        
        ]);

        DB::table('user_type')->insert([
            'name' => "Distribuidor",                        
        ]);
        DB::table('user_type')->insert([
            'name' => "Desarrollador",                        
        ]);
    }
}
