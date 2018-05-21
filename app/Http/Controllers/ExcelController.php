<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\DB;

use Tymon\JWTAuth\Facades\JWTAuth;

use Excel;

class ExcelController extends Controller 
{    

    public function getInventory(Request $request) {
        
        $user = JWTAuth::parseToken()->authenticate();
        $products = DB::table('products'. $user->shop_id)->orderBy('name', 'ASC')->get();

        Excel::create('Inventario', function($excel) use ($products){

            $excel->sheet('hoja 1', function($sheet) use ($products){

                $sheet->loadView('excel/inventory')->with(['products' => $products]);

            });

        })->export('xls');


        return view('excel.inventory', [
            'products' => $products
        ]);
    }
}
