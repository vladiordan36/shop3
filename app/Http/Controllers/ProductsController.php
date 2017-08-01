<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use DB;
use Session;
use Illuminate\Support\Facades\Input;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id, $status){
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id){
    $product = Product::find($id);

    return response()->json($product);
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id, $status)
    {
       $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        //Handle file upload
        $imagePath = time().'.'.$request->file('image')->getClientOriginalExtension();
        $request->file('image')->storeAs('public/media',$imagePath);
        if($status == 'update'){
            $msg = "Product Updated";
            $product = Product::find($id);
        }
        else{
            $msg = "Product Created";
            $product = new Product;
        }
        $product->title = $request->input('title');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->image = 'storage/media/'.$imagePath;
        $product->save();

        #  retrun response()->json(['success'] => 'true');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
    }
}
