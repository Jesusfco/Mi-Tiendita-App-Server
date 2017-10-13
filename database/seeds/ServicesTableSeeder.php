<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            'name' => "1er mes gratis",
            'amount' => 0,
            'description'=> "Solo registrate y opten un mes gratis de prueba para usar nuestra apliación",
            'months'=> 1,
        ]);

        DB::table('services')->insert([
        'name' => "6 Meses básico",
        'amount' => 165,
        'description'=> "En este paquete podras tener 400 articulos en tu inventario 2 Usuarios(Administrador y cajero) 
                              y respaldaras tu ventas con un historial de hasta 2 meses(Tienes la opcion de descargar historial y analizis de tus ventas)",
        'months'=> 6,
    ]);

        DB::table('services')->insert([
            'name' => "12 Meses basico",
            'amount' => 300,
            'description'=> "En este paquete podras tener 500 articulos en tu inventario 2 Usuarios(Administrador y cajero) 
                              y respaldaras tu ventas con un historial de hasta 3 meses(Tienes la opcion de descargar historial y analizis de tus ventas)",
            'months'=> 12,
        ]);

        DB::table('services')->insert([
        'name' => "6 Meses Premium",
        'amount' => 275,
        'description'=> "En este paquete podras tener 800 articulos en tu inventario 5 Usuarios(Administradores y cajeros) 
                              y respaldaras tu ventas con un historial de hasta 3 meses(Tienes la opcion de descargar historial y analizis de tus ventas)",
        'months'=> 6,
    ]);

        DB::table('services')->insert([
            'name' => "12 Premium",
            'amount' => 499,
            'description'=> "En este paquete podras tener 1000 articulos en tu inventario 8 Usuarios(Administradores y cajeros) 
                              y respaldaras tu ventas con un historial de hasta 6 meses(Tienes la opcion de descargar historial y analizis de tus ventas)",
            'months'=> 12,
        ]);
    }
}
