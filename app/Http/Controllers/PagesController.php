<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Session;
use App\Cart;

class PagesController extends Controller
{
    public function index(){
        $products = Post::all();
        return view("pages.index")->with('products',$products);
    }
    public function cart(){
        if(!Session::has('cart')) {
            return view('pages.cart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        return view('pages.cart',['products' => $cart->products, 'total' => $cart->total]);
    }

    public function addToCart(Request $request, $id){
        $product = Post::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product);

        $request->session()->put('cart', $cart);

        return redirect('/')->with('success', 'Product added to cart.');
    }

    public function removeFromCart(Request $request, $id){
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $cart->remove($id);

        if($cart->totalQuantity) {
            $request->session()->put('cart', $cart);
        }
        else{
            $request->session()->forget('cart');
        }

        return redirect('/cart')->with('success', 'Product removed from cart.');
    }

    public function checkout(Request $request){
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $message = 'Your order: <br /><br />';

        foreach($cart->products as $product){
            $message = $message.$product['item']['title'].' x '.$product['quantity'].' : '.$product['price'].'$<br />';
        }

        $message = $message.'<br />'.'Total: '.$cart->total.'$';

        mail($request->input('email'), __('messages.order'), $message);
        echo $message;

        $request->session()->forget('cart');
        return redirect('/')->with('success', 'Checkout successful.');
    }

    public function updateQuantity(Request $request, $id){
        $product = Post::find($id);
        $quantity = $request->input('quantity');
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $cart->update($product,$quantity);

        if($cart->totalQuantity) {
            $request->session()->put('cart', $cart);
        }
        else{
            $request->session()->forget('cart');
        }

        return redirect('/cart')->with('success', 'Quantity updated.');
    }
}
