<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Services;
use Illuminate\Http\Request;


class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {
        $product=Services::findOrFail($id);
        $color = Color::findOrFail($request->color);

        $item = [
            'product' => $product,
            'quantity' => $request->quantity,
            'color' => $color,
        ];

        if(session()->has('cart'))
        {
             //already exists, increment the quantity
            $cart = session()->get('cart');
            $key = $this->checkItemInCart($item);

            if($key != -1)
            {
                $cart[$key]['quantity'] += $request -> quantity;
                session()->put('cart', $cart);
            }
            else
            {
                session()->push('cart', $item);
             }
        }
        else
        {
            session()->push('cart', $item);
        }
        return back()->with('addedToCart', 'Success! Order has been added to cart');
    }

    public function checkItemInCart($item)
    {
        foreach(session()->get('cart') as $key => $val)
        {
            if($val['product']['id'] == $item['product']['id'] && $val['color']['id'] == $item['color']['id']  )
            {
                return $key;
            }
        }
        return -1;
    }






public function removeFromCart($key)
    {
        
       

        if(session()->has('cart'))
        {
             //already exists, increment the quantity
            $cart = session()->get('cart');
            array_splice($cart, $key, 1);
            session()->put('cart', $cart);
            return back()->with('success', 'Success! Order rempved from cart');
        }
        
        return back();
   


    }
}
