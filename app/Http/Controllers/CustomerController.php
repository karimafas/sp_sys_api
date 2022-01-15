<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function getCustomerByPhone(Request $request)
    {
        if (empty($request['phone'])) {
            return response(['error' => 'Phone number needed.'], 400);
        }

        $customer = Customer::where('phone', $request['phone'])->first();

        return $customer;
    }

    public function createCustomer(Request $request)
    {
        return Customer::create([
            'phone' => request('phone'),
            'address' => request('address'),
            'address2' => request('address2'),
            'area' => request('area'),
            'credit' => request('credit'),
            'discount' => request('discount'),
            'last_name' => request('last_name'),
            'level' => request('level'),
            'notes' => request('notes'),
        ]);
    }

    public function updateCustomer(Request $request)
    {
        if (empty($request['phone'])) {
            return response(['error' => 'Phone number needed.'], 400);
        }

        $success = Customer::where('phone', $request['phone'])->update($request->all());

        if ($success) {
            return response(Customer::where('phone', $request['phone'])->first(), 200);
        } else {
            return response(['error' => 'Error updating customer.'], 400);
        }
    }

    public function getCustomerByID($id)
    {
        return Customer::where('id', $id)->first();
    }

    public function getCustomerByName(Request $request)
    {
        if (empty($request['last_name'])) {
            return response(['error' => 'Last name needed.'], 400);
        }

        $customer = Customer::where('last_name', strtolower($request['last_name']))->first();

        if (empty($customer)) {
            $customer = Customer::where('last_name', strtoupper($request['last_name']))->first();
        }

        return $customer;
    }

    public function searchCustomer(Request $request)
    {
        if (empty($request['last_name'])) {
            return response(['error' => 'Last name needed.'], 400);
        }

        $customer = DB::select("select * from customers where lower(last_name) LIKE '%" . strtolower($request['last_name']) . "%';");

        return $customer;
    }
}
