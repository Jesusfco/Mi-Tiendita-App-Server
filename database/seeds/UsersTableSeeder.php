<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

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
            'name' => "Jesús Fco Cortés",
            'email' => 'jfcr@live.com',
            'phone'=> '9611221222',
            'shop_id'=> -1,
            'user_type' => 10,
            'password' => bcrypt('secret'),
        ]);
    }
}
