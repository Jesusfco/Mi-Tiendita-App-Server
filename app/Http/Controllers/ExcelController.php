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

    public function getSales(Request $request) {

        $user = JWTAuth::parseToken()->authenticate();

        if(isset($request->to))
        $sales = DB::table('sales'. $user->shop_id)
                        ->whereBetween('created_at', [$request->from, $request->to . " 23:59:59"])
                        ->orderBy('created_at', 'DESC')
                        ->get();
        else {
            $sales = DB::table('sales'. $user->shop_id)
                        ->where('created_at', 'LIKE', $request->from . "%")
                        ->orderBy('created_at', 'DESC')
                        ->get();
        } 

        $sales = $this->pushDescription($sales, $user);


        $productos = DB::table('products'. $user->shop_id)->orderBy('name', 'ASC')->get();

        for($i = 0; $i < count($sales); $i++) {
            for($x = 0 ; $x < count($sales[$i]->description); $x++){
                
                foreach($productos as $pro) {
                    if($pro->id == $sales[$i]->description[$x]->product_id) {
                        $sales[$i]->description[$x]->product_name = $pro->name;
                    }
                }
            }
        }

        Excel::create('VENTAS', function($excel) use ($sales){

            $excel->sheet('Ventas', function($sheet) use ($sales){

                $sheet->loadView('excel/sales')->with(['sales' => $sales]);

            });

            $excel->sheet('DescripcionDeVentas', function($sheet) use ($sales){

                $sheet->loadView('excel/salesDescription')->with(['sales' => $sales]);

            });

        })->export('xls');

    }

    public function pushDescription($sales, $user){
        $z = count($sales);
        // $y = key($sales);
        $description = DB::table('sale_description' . $user->shop_id)
                                    ->whereBetween('sale_id', [$sales[$z-1]->id, $sales[0]->id])                                    
                                    ->get();
        
        for($x = 0; $x < count($sales); $x++){
            $sales[$x]->description = [];
            foreach($description as $desc){

                if( $sales[$x]->id == $desc->sale_id){
                    $sales[$x]->description[] = $desc;
                }

            }
        }

        return $sales;
    }

    public function today(){
        $date = getdate()['year'] . '-';

        if(getdate()['mon'] < 10) 
            $date .= '0' . getdate()['mon'] . '-';
        else { $date .= getdate()['mon'] . '-'; } 

        if(getdate()['mday'] < 10 )
            $date .= '0' . getdate()['mday'];
        else { $date .= getdate()['mday']; }

        return $date;

    }
    
}


