<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Description;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class SaleController extends Controller
{
    public function storeSale(Request $request){
        $user = JWTAuth::parseToken()->authenticate();

        $sale = new sale();
        $sale->setTable('sales'. $user->shop_id);
        $sale->total = $request->total;
        $sale->user_id = $user->shop_id;
        $sale->created_at = date_create();
        $sale->save();

        foreach($x as $request->description){
            $description = new Description();
            $description->setTable('sale_description' . $user->shop_id);
            $description->sale_id = $sale->id;
            $description->product_id = $x['id_product'];
            $description->product_id = $x['product_id'];
            $description->product_id = $x['product_id'];
        }
    }

    public function storeSale(Request $request){
        
    }
}
