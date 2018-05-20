<?php

namespace App\Http\Controllers;

use App\User;
use App\Shop;
use App\Payment;
use App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'email' => 'required',
            'password' => 'required'
        ]);


        $credentials = $request->only('email', 'password');
        return $this->authProcediment($credentials);
        
    }

    public function authProcediment($credentials){
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

        $shop = Shop::find(Auth::user()->shop_id);
        $inventory = DB::table('products'. $shop->id)->orderBy('name', 'ASC')->get();

        foreach($inventory as $i) {
            // DB::table('products'. $shop->id)->where('id', $i->id)->update(['updated_at' => $i->created_at]);
        }

        $service = $this->getLimitService($shop->id);

        return response()->json([
            'token' => $token,
            'user' => Auth::user(),
            'shop' => $shop,
            'inventory' => $inventory,
            'service' => $service
        ],200);
    }

    public function checkAuth(){

//        $this->middleware('user1');$user

        $user = JWTAuth::parseToken()->authenticate();

        $service = $this->getLimitService($user->shop_id);

        return response()->json([
            'user' => $user,
            'shop' => Shop::find(Auth::user()->shop_id),
            'service' => $service
        ]);

    }

    public function register(Request $request){
        
        $user= $request->user;
        $shop =  $request->shop;

        $newShop = $this->createShop($shop);            
        $this->createSuperAdminUser($user, $newShop);
        $this->createFirstPayment($newShop);        
        $this->createDescriptionTable($newShop);
        $this->createProductsTable($newShop);
        $this->createSalesTable($newShop);

        return response()->json("Shop and user creation with success");      
    }

    public function createShop($shop){
        $newShop = new Shop();

        $newShop->name = $shop['name'];
        $newShop->cash = 0;
        $newShop->street = $shop['street'];
        $newShop->number = $shop['number'];
        $newShop->colony =  $shop['colony'];
        $newShop->postalCode = $shop['postalCode'];
        $newShop->city = $shop['city'];
        $newShop->state =  $shop['state'];
        #$newShop->country = $shop->country;
        $newShop->save();
        return $newShop;
    }

    public function createSuperAdminUser($user, $newShop){
        $newUser =  new User();
        $newUser->name =  $user['name'];
        $newUser->shop_id = $newShop->id;
        $newUser->email = $user['email'];
        $newUser->phone = $user['phone'];
        $newUser->password = bcrypt($user['password']);
        $newUser->user_type_id = 3;
        #$newUser->cash =  $user['cash'];
        $newUser->save();

    }

    public function createFirstPayment($newShop){

        $payment =  new Payment();
        $payment->shop_id = $newShop->id;
        $payment->service_id = 1;
        $payment->amount = 0;

        $payment->save();
    }

    public function createProductsTable($shop){

        Schema::create('products'. $shop->id, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('code')->nullable()->unique();
            $table->double('price');
            $table->double('cost_price')->nullable();
            $table->integer('reorder')->nullable(0);
            $table->integer('stock')->nullable(0);
            $table->string('department')->nullable(0);
            $table->timestamps();
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

        Schema::create('sale_description'. $shop->id, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sale_id');
            $table->integer('product_id')->nullable();
            $table->string('product_name')->nullable();
            $table->double('price');
            $table->integer('quantity');
            $table->double('subtotal');
        });

    }

    public function uniqueEmail(Request $request){
        $user =  User::where('email', $request->email)->first();
        if($user == NULL) return response()->json(true);
        else return response()->json(false);
    }

    public function uniquePhone(Request $request){
        $user =  User::where('phone', $request->phone)->first();
        if($user == NULL) return response()->json(true);
        else return response()->json(false);
    }

    public function getLimitService($shop_id){        
        
        // return Payment::where('shop_id', $shop_id)->orderBy('id', 'DESC')->first();
        $payment = Payment::where('shop_id', $shop_id)->orderBy('id', 'DESC')->first();
        return Services::find($payment->service_id);
    }
}
