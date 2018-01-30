<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Description;
use App\Shop;
use App\User;
use App\Payment;
use App\Services;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class PerfilController extends Controller
{
    public function getPerfil(){

        $user = JWTAuth::parseToken()->authenticate();

        $shop = Shop::find($user->shop_id);        
        $payment =  Payment::where('shop_id', $shop->id)->first();
        $service = Services::find($payment->service_id);
        $sales = DB::table('sales'. $user->shop_id)
                        ->where('created_at', 'LIKE', $this->today() . "%")
                        ->orderBy('created_at', 'DESC')
                        ->get();
        if(isset($sales[0]))            
            $sales = $this->pushDescription($sales, $user);

        return response()->json([
            'user' => $user,
            'payment' => $payment,
            'service' => $service,
            'sales'=> $sales,
            'shop' => $shop,
        ]);

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
}
