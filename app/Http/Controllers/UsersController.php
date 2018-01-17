<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Shop;
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
         $newUser =  new User();

         $newUser->shop_id = $user->shop_id;
         $newUser->name = $request->name;
         $newUser->email = $request->email;
         $newUser->phone = $request->phone;
         $newUser->user_type_id = $request->user_type_id;
         $newUser->password = bcrypt($request->password);
         $newUser->save();

         return response()->json($newUser);
    }

    public function validateNumberOfUser(){
        $user = JWTAuth::parseToken()->authenticate();   
        $users =  User::where('shop_id', $user->shop_id)->get();
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
}
