<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\DB;

use Tymon\JWTAuth\Facades\JWTAuth;

class ProductController extends Controller
{
    public function __construct(){

//        $this->middleware('user1');
    }
    public function getProducts(Request $request){

        $user = JWTAuth::parseToken()->authenticate();
        $productos = DB::table('products'. $user->shop_id)->get();

        return response()->json($productos);
//        return response()->json($request->ips());
    }

    public function show($id){
        $user = JWTAuth::parseToken()->authenticate();
        $product = DB::table('products'. $user->shop_id)
                            ->where('id', $request->id)
                            ->get();
        return response()->json($product[0]);
    }

    public function store(Request $request){
        $user = JWTAuth::parseToken()->authenticate();

        $product = new Product();
        $product->setTable('products'. $user->shop_id);

        $product->name = $request->name;
        $product->code = $request->code;
        $product->price = $request->price;
        $product->reorder = $request->reorder;
        $product->stock = $request->stock;
        $product->created_at = date_create();
        $product->save();

        return response()->json($product);

    }

    public function update(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        DB::table('products'. $user->shop_id)
                            ->where('id', $request->id)
                            ->update([
                                'name' => $request->name,
                                'code' => $request->code,
                                'price' => $request->price,
                                'stock' => $request->stock,
                                'reorder' => $request->reorder,
                            ]);

        return response()->json('product ' .$request->id . ' edited');
    }

    public function delete(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $product = DB::table('products'. $user->shop_id)
            ->where('id', $request->id)
            ->delete();

        return response()->json('deleted');
    }
}
