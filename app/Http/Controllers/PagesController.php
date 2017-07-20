<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Session;

class PagesController extends Controller
{
    public function index(){
        $cart = Session::get('cart');
        $items = Product::all();
        $products = array();
        foreach($items as $item){
            if(!isset($cart[$item['ID']])){
                $products[] = $item;
            }
        }
        return view("pages.index")->with('products',$products);
    }

    public function cart(){
        $cart = Session::get('cart');
        $items = Product::all();
        $products = array();
        $total = 0;
        foreach($items as $item){
            if(isset($cart[$item['ID']])){
                $products[] = $item;
                $total += $item['price']*$cart[$item['ID']];
            }
        }
        return view('pages.cart')->with('products',$products)
                                       ->with('cart',$cart)
                                       ->with('total',$total);
    }

    public function addToCart(Request $request, $id){
        if(!Session::has('cart')){
            $cart=[];
            $cart[$id] = 1;
        }
        else{
            $cart = Session::get('cart');
            $cart[$id] = 1;
        }
        $request->session()->put('cart', $cart);

        return redirect('/index')->with('success', 'Product added to cart.');
    }

    public function removeFromCart(Request $request, $id){
        $cart = Session::get('cart');
        unset($cart[$id]);
        $request->session()->put('cart',$cart);

        if(count($cart)) {
            $request->session()->put('cart', $cart);
        }
        else{
            $request->session()->forget('cart');
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

        $request->session()->forget('cart');
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
            $request->session()->put('cart', $cart);
        }
        else{
            $request->session()->forget('cart');
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

        if($user != config('user','admin') || $pass != config('pass','admin')){
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
