<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Session;

class PagesController extends Controller
{
    public function mainPage(){
        return redirect('index.html');
    }

    public function index(){
        $cart = Session::get('cart');
        if(count(Session::get('cart'))){
            $products = Product::wherenotin('ID', array_keys($cart))->get();
        }
        else{
            $products = Product::all();
        }
        return response()->json($products);
    }

    public function cart(){
        $cart = Session::get('cart');
        $total = 0;
        if(count(Session::get('cart'))){
            $products = Product::wherein('ID', array_keys($cart))->get();
            foreach($products as $product){
                $total += $product['price'] * $cart[$product['ID']];
            }
        }
        else{
            $products = null;
        }

        $results = [
            'products' => $products,
            'total' => $total,
            'cart' => $cart
        ];
        return response()->json($results);
    }

    public function addToCart($id){
        if(!Session::has('cart')){
            $cart=[];
            $cart[$id] = 1;
        }
        else{
            $cart = Session::get('cart');
            $cart[$id] = 1;
        }
        Session::put('cart', $cart);

        return $this->index();
    }

    public function removeFromCart($id){
        $cart = Session::get('cart');
        unset($cart[$id]);

        if(count($cart)) {
           Session::put('cart', $cart);
        }
        else{
            Session::forget('cart');
        }

        return $this->cart();
    }

    public function checkout($mail){
        $cart = Session::get('cart');
        $total = 0;
        $message = 'Your order: <br /><br />';

        foreach($cart as $key=>$value){
            $product = Product::find($key);
            $message = $message.$product['title'].' '.$product['price'].'x'.$value.' : '.$product['price']*$value.'$<br />';
            $total += $product['price']*$value;
        }

        $message = $message.'<br />'.'Total: '.$total.'$';

        mail($mail, __('messages.order'), $message);

        Session::forget('cart');
        return $this->cart();
    }

    public function updateQuantity($quantity, $id){

        $cart = Session::get('cart');
        if($quantity){
            $cart[$id] = $quantity;
        }
        else{
            unset($cart[$id]);
        }

        if(count($cart)) {
            Session::put('cart', $cart);
        }
        else{
            Session::forget('cart');
        }

        return $this->cart();
    }

    public function login(Request $request){
        $response = [
            'success' => 'true'
        ];

        $user = $request->input('user');
        $pass = $request->input('pass');

        if($user != config('app.user','admin') || $pass != config('app.pass','admin')){
           $response['success'] = 'false';
        }
        Session::put('logged in','true');
        return response()->json($response);
    }
    public function logout(Request $request){
        $request->session()->forget('logged in');
        return redirect('/index')->with('success', 'Logged out');
    }
}
