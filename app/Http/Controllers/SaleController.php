<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Description;
use App\Shop;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class SaleController extends Controller
{
    public function storeSale(Request $request){
        $user = JWTAuth::parseToken()->authenticate();

        // return response()->json('Sale Store');

        $sale = new sale();
        $sale->setTable('sales'. $user->shop_id);
        $sale->total = $request->total;
        $sale->user_id = $user->shop_id;
        $sale->created_at = date_create();
        $sale->save();

        foreach($request->description as $x){
            $description = new Description();
            $description->setTable('sale_description' . $user->shop_id);
            $description->sale_id = $sale->id;
            $description->product_id = $x['product_id'];
            $description->price = $x['price'];
            $description->quantity = $x['quantity'];
            $description->subtotal = $x['subtotal'];
            $description->save();

            DB::table('products'. $user->shop_id)
                ->where('id', $x['product_id'])
                ->decrement('stock', $x['quantity']);
        }
        
        if($user->cash == NULL){
            $shop = Shop::find($user->shop_id);
            $shop->cash += $sale->total;
            $shop->save();
        }
        return response()->json('Sale Store');
    }

    public function storeSaleOutService(Request $request){
        
    }
}
