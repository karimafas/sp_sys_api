<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

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
        $customer = Customer::where('phone', $request['phone'])->first();

        if (empty($customer)) {
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
        } else {
            return response(
                ['error' => 'A customer with this phone number already exists.'],
                400
            );
        }
    }

    public function updateCustomer(Request $request)
    {
        if (empty($request['phone'])) {
            return response(['error' => 'Phone number needed.'], 400);
        }

        $success = Customer::where('phone', $request['phone'])->update($request->all());

        if($success) {
            return response(Customer::where('phone', $request['phone'])->first(), 200);
        } else {
            return response(['error' => 'Error updating customer.'], 400);
        }
    }
}
