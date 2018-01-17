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

    public function getSales(){
        $user = JWTAuth::parseToken()->authenticate();  
        // $date = getDay();
        $date = $this->today();
        // $date = $date->year . "-" . $date->mon . '-' . $date->
        // return response()->json($date);
        // return response()->json(DB::table('sales'. $user->shop_id)->where('created_at', '<', "2018-01-19")->where('created_at', '>', "2018-01-15")->get());
        return response()->json(DB::table('sales'. $user->shop_id)
                                ->where('created_at', 'LIKE', $date . "%")
                                ->orderBy('created_at', 'desc')
                                ->get());
    }

    public function postSales(Request $request){

    }
    public function showSale($id){
        $user = JWTAuth::parseToken()->authenticate();  
        $sale = DB::table('sales'. $user->shop_id)
                                    ->where('id', $id)
                                    ->get();
        $sale[0]->description = DB::table('sale_description'. $user->shop_id)
                                    ->where('sale_id', $id)
                                    ->get();                                 
        return response()->json($sale[0]);
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
