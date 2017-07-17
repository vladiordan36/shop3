<?php

namespace App;

class Cart
{
    public $products = null;
    public $total = 0;
    public $totalQuantity = 0;

    public function __construct($oldCart){
        if($oldCart){
            $this->products = $oldCart->products;
            $this->total = $oldCart->total;
            $this->totalQuantity = $oldCart->totalQuantity;
        }
    }


    public function add($product){
        $aux = [
            'quantity' => 0,
            'price' => $product->price,
            'item' =>$product
        ];
        if($this->products){
            if(array_key_exists($product->ID,$this->products)){
                $aux = $this->products[$product->ID];
            }
        }

        $aux['quantity']++;
        $aux['price'] = $product->price*$aux['quantity'];
        $this->products[$product->ID] = $aux;
        $this->totalQuantity++;
        $this->total += $product->price;
    }
    public function remove($id){
        $this->total -= $this->products[$id]['price'];
        $this->totalQuantity -= $this->products[$id]['quantity'];
        unset($this->products[$id]);
    }
    public function update($product, $quantity){
        $this->remove($product['ID']);
        $counter = 0;
        while($counter++ < $quantity){
            $this->add($product);
        }
    }
}
