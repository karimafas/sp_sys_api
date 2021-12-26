<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ContentController extends Controller
{
    public function getJson()
    {
        $categories = DB::select('SELECT id, name FROM categories');
        $variations = DB::select('SELECT id, name, code, price FROM variations');
        $products = DB::select('SELECT id, name, description, price, number, category_id FROM products');

        return array_merge(['categories' => $categories], ['variations' => $variations], ['products' => $products]);
    }
}
