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
        
        $sale = $this->newSale($request, $user);        
        $this->updateCash($user, $sale);        
                
        foreach($request->description as $x){
            $this->newSaleDescription($x, $user, $sale);
            $this->decrementStock($x, $user);            
        }
                
        return response()->json($sale);
    }

    public function newSale($request, $user){
        $sale = new sale();
        $sale->setTable('sales'. $user->shop_id);
        $sale->total = $request->total;
        $sale->user_id = $user->shop_id;
        $sale->created_at = $request->created_at;
        $sale->save();
        return $sale;
    }

    public function newSaleDescription($descriptionRequest, $user, $sale){
            $description = new Description();
            $description->setTable('sale_description' . $user->shop_id);
            $description->sale_id = $sale->id;
            $description->product_id = $descriptionRequest['product_id'];
            $description->price = $descriptionRequest['price'];
            $description->quantity = $descriptionRequest['quantity'];
            $description->subtotal = $descriptionRequest['subtotal'];
            $description->save();
    }

    public function updateCash($user, $sale){
        if($user->cash == NULL){
            $shop = Shop::find($user->shop_id);
            $shop->cash += $sale->total;
            $shop->save();
        } else {
            $updateUser =  User::find($user->id);
            $updateUser->cash += $sale->total;
            $updateUser->save();
        }
    }

    public function decrementStock($x, $user){
        DB::table('products'. $user->shop_id)
                ->where('id', $x['product_id'])
                ->decrement('stock', $x['quantity']);
    }

    public function storeSaleOutService(Request $request){
        $user = JWTAuth::parseToken()->authenticate(); 
        // $salesReturn[];
        foreach($request->sales as $sale){

            $description = $sale['description'];
            $sale = json_decode(json_encode($sale), FALSE);
            $sale = $this->newSale($sale, $user);
            $this->updateCash($user, $sale);
            // array_push($salesReturn, $sale);
            
            foreach($description as $x){
         
                $this->newSaleDescription($x, $user, $sale);                
                $this->decrementStock($x, $user);

            }
            
        }
        return response()->json('sale description');
        // return response()->json('sales', $salesReturn);
    }
}
