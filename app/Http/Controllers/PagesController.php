<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Session;

class PagesController extends Controller
{
    public function index(){
        $cart = Session::get('cart');
        if(count(Session::get('cart'))){
            $products = Product::wherenotin('ID', array_keys($cart))->get();
        }
        else{
            $products = Product::all();
        }
        return view("pages.index")->with('products',$products);
    }

    public function cart(){
        $cart = Session::get('cart');
        $total = 0;
        if(count(Session::get('cart'))){
            $products = Product::wherein('ID', array_keys($cart))->get();
        }
        else{
            $products = Product::all();
        }

        foreach($products as $product){
            $total += $product['price'] * $cart[$product['ID']];
        }

        return view('pages.cart')->with('products',$products)
                                       ->with('cart',$cart)
                                       ->with('total',$total);
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

        return redirect('/index')->with('success', 'Product added to cart.');
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

        return redirect('/cart')->with('success', 'Product removed from cart.');
    }

    public function checkout(Request $request){
        $cart = Session::get('cart');
        $total = 0;
        $message = 'Your order: <br /><br />';

        foreach($cart as $key=>$value){
            $product = Product::find($key);
            $message = $message.$product['title'].' '.$product['price'].'x'.$value.' : '.$product['price']*$value.'$<br />';
            $total += $product['price']*$value;
        }

        $message = $message.'<br />'.'Total: '.$total.'$';

        mail($request->input('email'), __('messages.order'), $message);

        Session::forget('cart');
        return redirect('/index')->with('success', 'Checkout successful.');
    }

    public function updateQuantity(Request $request, $id){
        $this->validate($request,[
           'quantity' => 'required|min:0|max:100'
        ]);

        $quantity = $request->input('quantity');
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

        return redirect('/cart')->with('success', 'Quantity updated.');
    }

    public function login(Request $request){

        $this->validate($request,[
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = $request->input('email');
        $pass = $request->input('password');

        if($user != config('app.user','admin') || $pass != config('app.pass','admin')){
            return redirect('/login')->with('error','The email or password are invalid.');
        }
        $request->session()->put('logged in','true');
        return redirect('/index')->with('success','Login successful');
    }
    public function logout(Request $request){
        $request->session()->forget('logged in');
        return redirect('/index')->with('success', 'Logged out');
    }
}
