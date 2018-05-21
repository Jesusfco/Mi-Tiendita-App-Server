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
        $productos = DB::table('products'. $user->shop_id)->orderBy('name', 'ASC')->get();

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
        $product->cost_price = $request->cost_price;
        $product->reorder = $request->reorder;
        $product->stock = $request->stock;
        $product->department =  $request->department;
        $product->created_at = date_create();
        $product->updated_at = date_create();
        $product->save();


        $product = DB::table('products'. $user->shop_id)
                            ->where('id', $product->id)->get();

        return response()->json($product[0]);

    }

    public function update(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        DB::table('products'. $user->shop_id)
                            ->where('id', $request->id)
                            ->update([
                                'name' => $request->name,
                                'code' => $request->code,
                                'price' => $request->price,
                                'cost_price' => $request->cost_price,
                                'stock' => $request->stock,
                                'reorder' => $request->reorder,
                                'department' => $request->department,
                                'updated_at' => date_create()
                            ]);

        $product = DB::table('products'. $user->shop_id)
                            ->where('id', $request->id)->get();
        return response()->json($product[0]);
    }

    public function delete(Request $request, $id){
        $user = JWTAuth::parseToken()->authenticate();
        $descriptions = DB::table('sale_description' . $user->shop_id)->where('product_id', $id)->get();
        foreach($descriptions as $x){
            $x->product_id = NULL;
            $x->product_name =  $request->name;
            $x->save();
        }
        $product = DB::table('products'. $user->shop_id)
            ->where('id', $request->id)
            ->delete();

        return response()->json('deleted');
    }

    public function syncUp(Request $request) {

        $user = JWTAuth::parseToken()->authenticate();
        $inventory = DB::table('products'. $user->shop_id)
                            ->where('updated_at', '>', $request->last_updated)->get();

        return response()->json($inventory);

    }
}
