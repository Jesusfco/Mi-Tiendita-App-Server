<?php

namespace App\Http\Controllers;

use App\User;
use App\Shop;
use App\Payment;
use App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
//use JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{

    public function signin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        try {
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json([
                    'error' => 'Credenciales Invalidas'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not create token!'
            ], 500);
        }

        return response()->json([
            'token' => $token,
            'user' => Auth::user()->active
        ],200);
    }

    public function checkAuth(){

//        $this->middleware('user1');$user

        $user = JWTAuth::parseToken()->authenticate();

        return response()->json([
            'user' => $user,
        ]);

    }

    public function register(Request $request){

        $user= $request->user;
        $shop =  $request->shop;


        $newShop = new Shop();

        $newShop->name = $shop['name'];
        $newShop->cash = 0;
        $newShop->street = $shop['street'];
        $newShop->colony =  $shop['colony'];
        $newShop->postalCode = $shop['postalCode'];
        $newShop->city = $shop['city'];
        $newShop->state =  $shop['state'];
//        $newShop->country = $shop->country;
        $newShop->save();

//        return response()->json($newShop);

        $newUser =  new User();
        $newUser->name =  $user['name'];
        $newUser->shop_id = $newShop->id;
        $newUser->email = $user['email'];
        $newUser->phone = $user['phone'];
        $newUser->password = bcrypt($user['password']);
        $newUser->user_type = 3;
//        $newUser->cash =  $user['cash'];
        $newUser->save();

        $payment =  new Payment();
        $payment->shop_id = $newShop->id;
        $payment->service_id = 1;
        $payment->amount = 0;

        $payment->save();

        $this->createDescriptionTable($newShop);
        $this->createProductsTable($newShop);
        $this->createSalesTable($newShop);

        return response()->json(['message' => "Shop is enable and working"]);
    }

    public function createProductsTable($shop){

        Schema::create('products'. $shop->id, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('code')->nullable()->unique();
            $table->double('price');
            $table->string('reorder')->nullable();
            $table->string('stock')->nullable();
            $table->timestamp('created_at');
        });

    }

    public function createSalesTable($shop){

        Schema::create('sales'. $shop->id, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->double('total');
            $table->timestamp('created_at');
        });

    }

    public function createDescriptionTable($shop){

        Schema::create('descriptions'. $shop->id, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sale_id');
            $table->integer('product_id');
            $table->double('price');
            $table->integer('quantity');
            $table->double('subtotal');
        });

    }

    public function test(){
        return response()->json('Exito Conexion con el BackEnd');
    }
}
