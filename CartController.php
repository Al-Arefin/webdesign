<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use Cart;

class CartController extends Controller
{
    //
    public function add_to_cart(Request $request)
    {
        $qty        = $request->qty;
        $product_id = $request->product_id;
        $product_info = DB::table('tbl_products')
                        ->where('product_id',$product_id)
                        ->first();
        $data['quantity'] = $qty;
        $data['id']  = $product_info->product_id;
        $data['name']  = $product_info->product_name;
        $data['price']  = $product_info->product_price;
        $data['attributes']['image']  = $product_info->product_image;

        Cart::add($data);
        return Redirect::to('/show_cart');

    }
    public function show_cart()
    {
        $all_published_category = DB::table('tbl_category')
                                    ->where('publication_status',1)
                                    ->get();
        $manage_published_category = view('pages.add_to_cart')
                                    ->with('all_published_category', $all_published_category);
        return view('layout')
            ->with('pages.add_to_cart', $manage_published_category);
    }

    public function delete_from_cart($id)
    {
        Cart::remove($id);
        return Redirect::to('/show_cart');
    }

    public function update_plus(Request $request)
    {
        $qty = $request->quantity;
        $rowid = $request->id;

        Cart::update($rowid, array(
            'quantity' => 1, // so if the current product has a quantity of 4, another 2 will be added so this will result to 6
          ));
        return Redirect::to('/show_cart');
    }
    public function update(Request $request)
    {
        if (isset($_POST['submit1']))
        {
            $qty = $request->quantity;
            $rowid = $request->id;

            Cart::update($rowid, array(
                'quantity' => -1, // so if the current product has a quantity of 4, another 2 will be added so this will result to 6
            ));
            return Redirect::to('/show_cart');
        }
        elseif (isset($_POST['submit2']))
        {
            $qty = $request->quantity;
            $rowid = $request->id;

            Cart::update($rowid, array(
                'quantity' => +1, // so if the current product has a quantity of 4, another 2 will be added so this will result to 6
            ));
            return Redirect::to('/show_cart');
        }


        
    }
}
