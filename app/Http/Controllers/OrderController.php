<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        request()->validate([
            "customer_id" => "required",
            "delivery_time" => "required",
            "order_json" => "required",
        ]);

        $customerCheck = Customer::where('id', request('customer_id'))->first();

        if (empty($customerCheck)) {
            return response(['error' => 'No customer found with the given id.'], 400);
        }

        return Order::create([
            "customer_id" => request("customer_id"),
            "delivery_time" => request("delivery_time"),
            "order_json" => request("order_json"),
        ]);
    }

    public function getOrdersByDate(Request $request)
    {
        return Order::whereDate('created_at', '2021-12-21')->get();
    }
}
