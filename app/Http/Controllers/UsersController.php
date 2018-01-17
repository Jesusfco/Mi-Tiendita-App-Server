<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Shop;
use App\Payment;
use App\Services;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends Controller
{
    public function getUsers(){
        $user = JWTAuth::parseToken()->authenticate();
           
        return response()->json(User::where('shop_id', $user->shop_id)->get());
    }

    public function create(Request $request){
        $user = JWTAuth::parseToken()->authenticate();   
        if($this->validateNumberOfUser($user->shop_id)){

            $newUser =  new User();

            $newUser->shop_id = $user->shop_id;
            $newUser->name = $request->name;
            $newUser->email = $request->email;
            $newUser->phone = $request->phone;
            $newUser->user_type_id = $request->user_type_id;
            $newUser->password = bcrypt($request->password);
            $newUser->save();
   
            return response()->json($newUser);

        } else { return response()->json(['error' =>'Numero de usuarios limite alcanzado'], 503); }
         
    }

    public function validateNumberOfUser($shop_id){        
        $payment = Payment::where('shop_id', $shop_id)->orderBy('id', 'DESC')->first();
        $service = Services::find($payment->service_id);
        $users =  User::where('shop_id', $shop_id)->get();
        if($users->count() >= $service->users_limit) {
            return false;
        } else {

        } return true;

    }

    public function update(Request $request){
        $user = JWTAuth::parseToken()->authenticate();   
         $newUser =  User::find($request->id);
         
         $newUser->name = $request->name;
         $newUser->email = $request->email;
         $newUser->phone = $request->phone;
         $newUser->user_type_id = $request->user_type_id;
         $newUser->cash = $request->cash;
         if($request->password != NULL || $request->password != '')
         $newUser->password = bcrypt($request->password);
         $newUser->save();

         return response()->json($newUser);
    }

    public function updateGlobalCash(Request $request){
        
        $user = JWTAuth::parseToken()->authenticate();  
        $shop =  Shop::find($user->shop_id);
        $shop->cash = $request->money;
        $shop->save();
        return response()->json('success');
    }
}
