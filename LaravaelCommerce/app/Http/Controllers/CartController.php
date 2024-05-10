<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use stdClass;

class CartController extends Controller
{
    private function data() : \Illuminate\Support\Collection
    {
        if (!Session::has('cart'))
        {
            return collect([]);
        }

        $data = Session::get('cart');

        foreach ($data as $key => $d) {
            $d['item'] = DB::table('product')
                ->where('id', '=', $d['id'])
                ->first();
                $d['item_subtotal'] = $d['item']->price*$d['total'];
            $data[$key] = $d;
            
        }

        return collect($data);
    }

    private function dataPush(array $d)
    {
        $data = $this->data();

        if ($data->where('id', '=', $d['id'])->count() > 0)
        {
            foreach ($data as $k => $e) {
                if ($e['id'] == $d['id'])
                {
                    $e['total'] = $e['total'] + $d['total'];
                    // This is hacky, but this works
                    $data[$k] = $e;
                }
            }
        }
        else
        {
            $data[] = $d;
        }
        // dd($data);
        Session::put('cart', $data->toArray());

        return $data;
    }

    private function calculateTotal()
    {
        $total = 0;
        $cartData = $this->data();

        foreach ($cartData as $cartItem) {
            $price = $cartItem['item']->price;
            $quantity = $cartItem['total'];
            $totalperItem = $price * $quantity;
            $cartItem['item_subtotal'] = $totalperItem;
            $total += $totalperItem;
        }

        Session::put('cart', $cartData);

        return $total;
    }

    public function Index()
    {
        $cart = new stdClass();
        $cart->CartTotal = $this->calculateTotal();

        return \view('cart', [
            "data" => $this->data(),
            "cart" => $cart
        ]);
    }

    public function CartAddAction(int $id, Request $request)
    {

        $quantity = $request->input('quantity', 1);

        $d = DB::table('product')
            ->where('id', '=', $id)
            ->first();

        if ($d == null) return \response()
            ->json([
                "statusCode" => 404,
                "message" => "Item not found!"
            ]);

        $this->dataPush([
            'id' => $id,
            'total' => $quantity
        ]);

        return \response()->json([
            'statusCode' => 201,
            "message" => "Item added!"
        ]);
    }

    public function CartRemoveAction($id)
    {
        $cart = Session::get('cart', []);

        foreach ($cart as $index => $product) 
        {
            if ($product['id'] == $id) 
            {
                unset($cart[$index]);
            }
        }

        Session::put('cart', $cart);
        return redirect('/cart');
    }
}
