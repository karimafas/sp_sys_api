<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderedItem;
use App\Models\OrderedProduct;
use App\Models\OrderedVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        request()->validate([
            "customer_id" => "required",
            "delivery_time" => "required",
            "order_json" => "required",
            "total" => "required",
            "items_number" => "required",
            "order_number" => "required",
            "mobile_order" => "required",
            "collection" => "required"
        ]);

        $customerCheck = Customer::where('id', request('customer_id'))->first();

        if (empty($customerCheck)) {
            return response(['error' => 'No customer found with the given id.'], 400);
        }

        $order = json_decode(request('order_json'));

        $createdOrder = Order::create([
            "customer_id" => request("customer_id"),
            "delivery_time" => request("delivery_time"),
            "order_json" => request("order_json"),
            "total" => request("total"),
            "items_number" => request("items_number"),
            "order_number" => request("order_number"),
            "mobile_order" => request("mobile_order"),
            "collection" => request("collection")
        ]);

        foreach ($order as $item) {
            $product = DB::select("SELECT id, name, description, price, number, category_id FROM products WHERE number = {$item->product->number}");

            for ($i = 0; $i < $item->quantity; $i++) {
                OrderedProduct::create(
                    [
                        "order_id" => $createdOrder->id,
                        "product_id" => $product[0]->id,
                    ]
                );
            }

            foreach ($item->selectedVariations as $variation) {
                $foundVariation = DB::select("SELECT id, name, code, price FROM variations WHERE code = '{$variation->code}'");

                for ($i = 0; $i < $item->quantity; $i++) {
                    OrderedVariation::create([
                        "order_id" => $createdOrder->id,
                        "product_id" => $product[0]->id,
                        "variation_id" => $foundVariation[0]->id
                    ]);
                }
            }
        }
    }

    public function getOrdersByDate(Request $request)
    {
        if (trim($request['date']) == '') {
            return Order::orderBy('created_at', 'desc')->get();
        } else {
            return Order::whereDate('created_at', $request['date'])->orderBy('created_at', 'desc')->get();
        }
    }

    public function update(Request $request, $id)
    {
        $updateOrder = Order::where('id', $id)->first();
        $success = $updateOrder->update($request->all());

        $order = json_decode(request('order_json'));

        $updateOrder = Order::where('id', $id)->first();

        DB::table('ordered_products')->where('order_id', '=', $updateOrder->id)->delete();
        DB::table('ordered_variations')->where('order_id', '=', $updateOrder->id)->delete();

        foreach ($order as $item) {
            $product = DB::select("SELECT id, name, description, price, number, category_id FROM products WHERE number = {$item->product->number}");

            for ($i = 0; $i < $item->quantity; $i++) {
                OrderedProduct::create(
                    [
                        "order_id" => $updateOrder->id,
                        "product_id" => $product[0]->id,
                    ]
                );
            }

            foreach ($item->selectedVariations as $variation) {
                $foundVariation = DB::select("SELECT id, name, code, price FROM variations WHERE code = '{$variation->code}'");

                for ($i = 0; $i < $item->quantity; $i++) {
                    OrderedVariation::create([
                        "order_id" => $updateOrder->id,
                        "product_id" => $product[0]->id,
                        "variation_id" => $foundVariation[0]->id
                    ]);
                }
            }
        }

        if ($success) {
            return response($updateOrder, 200);
        } else {
            return response('There was an error updating this order.', 400);
        }
    }

    public function assignRider(Request $request, $id)
    {
        $updateOrder = Order::where('id', $id)->first();
        $success = $updateOrder->update($request->all());

        if ($success) {
            return response($updateOrder, 200);
        } else {
            return response('There was an error updating this order.', 400);
        }
    }

    public function getOrderNumber()
    {
        $lastOrderToday = Order::whereDate('created_at', date('Y-m-d H:i:s'))->orderBy('created_at', 'desc')->first();

        if (empty($lastOrderToday)) {
            return response(['order_number' => 1], 200);
        } else {
            return response(['order_number' => $lastOrderToday['order_number'] + 1], 200);
        }
    }

    public function getBestSellerProducts()
    {
        return DB::select('SELECT op.product_id, p.name, p.description, p.price, p.number, p.category_id, COUNT(*) FROM ordered_products op LEFT JOIN products p ON op.product_id = p.id GROUP BY op.product_id, p.name, p.description, p.price, p.number, p.category_id ORDER BY COUNT(*) DESC');
    }

    public function getBestSellerVariations()
    {
        return DB::select('SELECT ov.variation_id, v.name, v.price, v.code, COUNT(*) FROM ordered_variations ov LEFT JOIN variations v ON ov.variation_id = v.id GROUP BY ov.variation_id, v.name, v.price, v.code ORDER BY COUNT(*) DESC');
    }

    public function index($id) {
        return Order::where('id', $id)->first();
    }
}
