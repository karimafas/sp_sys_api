<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PHPUnit\Runner\Exception;


class ContentController extends Controller
{
    public function getJson()
    {
        $categories = DB::select('SELECT id, name FROM categories');
        $variations = DB::select('SELECT id, name, code, price FROM variations');
        $products = DB::select('SELECT id, name, description, price, number, category_id FROM products');

        return array_merge(['categories' => $categories], ['variations' => $variations], ['products' => $products]);
    }

    // Products.
    public function getProduct(Request $request, $id)
    {
        $product = DB::select('SELECT * FROM products WHERE id = ?;', [$id]);

        if (count($product) == 0) {
            return response(['error' => 'No product found.'], 400);
        }

        return response($product, 200);
    }

    public function deleteProduct(Request $request, $id)
    {
        try {
            DB::statement('DELETE FROM products WHERE id = ?;', [$id]);
            return response(['success' => true], 200);
        } catch (Exception $e) {
            return response(['success' => false], 400);
        }
    }

    public function storeProduct(Request $request)
    {
        $fields = request()->validate([
            "name" => "required",
            "description" => "required",
            "price" => "required",
            "category_id" => "required",
            "number" => "required"
        ]);

        $productChk = DB::select('SELECT * FROM products where name = ?', [$fields['name']]);

        if (count($productChk) > 0) {
            $response = [
                'error' => 'A product with this name already exists.'
            ];

            return response($response, 400);
        }

        $codeChk = DB::select('select * from products where number = ?', [$fields['number']]);

        if (count($codeChk) > 0) {
            $response = [
                'error' => 'A product with this number already exists.'
            ];

            return response($response, 400);
        }

        $insert = DB::SELECT(
            'insert into products(name, description, price, category_id, number, created_at, updated_at) values(:name, :description, :price, :category_id, :number, now(), now()) returning *',
            [
                'name' => $fields['name'],
                'description' => $fields['description'],
                'price' => $fields['price'],
                'category_id' => $fields['category_id'],
                'number' => $fields['number']
            ]
        );

        return response($insert, 201);
    }

    public function updateProduct(Request $request, $id)
    {
        $productChk = DB::select('SELECT * FROM products where id = ?', [$id]);

        if (count($productChk) == 0) {
            $response = [
                'error' => 'A product with this id does not exist.'
            ];

            return response($response, 400);
        }

        try {
            DB::statement(
                'UPDATE products SET name = COALESCE(:name, name), description = COALESCE(:description, description), price = COALESCE(:price, price), category_id = COALESCE(:category_id, category_id), number = COALESCE(:number, number);',
                [
                    'name' => $request['name'],
                    'description' => $request['description'],
                    'price' => $request['price'],
                    'category_id' => $request['category_id'],
                    'number' => $request['number'],
                ]
            );
        } catch (Exception $e) {
            return response(['error' => 'Update statement failed.'], 400);
        }

        $updated = DB::select('select * from products where id = ?', [$id]);

        return response($updated, 200);
    }

    // Variations.
    public function getVariation(Request $request, $id)
    {
        $variation = DB::select('SELECT * FROM variations WHERE id = ?;', [$id]);

        if (count($variation) == 0) {
            return response(['error' => 'No variation found.'], 400);
        }

        return response($variation, 200);
    }

    public function deleteVariation(Request $request, $id)
    {
        try {
            DB::statement('DELETE FROM variations WHERE id = ?;', [$id]);
            return response(['success' => true], 200);
        } catch (Exception $e) {
            return response(['success' => false], 400);
        }
    }

    public function storeVariation(Request $request)
    {
        $fields = request()->validate([
            "name" => "required",
            "price" => "required",
        ]);

        if (!isset($request['code'])) {
            return response(['error' => 'Missing parameter: code.'], 400);
        }

        $variationChk = DB::select('SELECT * FROM variations where name = ?', [$fields['name']]);

        if (count($variationChk) > 0) {
            $response = [
                'error' => 'A variation with this name already exists.'
            ];

            return response($response, 400);
        }

        $codeChk = DB::select('select * from variations where code = ?', [$request['code']]);

        if (count($codeChk) > 0) {
            $response = [
                'error' => 'A variation with this code already exists.'
            ];

            return response($response, 400);
        }

        $insert = DB::SELECT(
            'insert into variations(name, code, price, created_at, updated_at) values(:name, :code, :price, now(), now()) returning *',
            [
                'name' => $fields['name'],
                'code' => $request['code'],
                'price' => $fields['price']
            ]
        );

        return response($insert, 201);
    }

    public function updateVariation(Request $request, $id)
    {
        $variationChk = DB::select('SELECT * FROM variations where id = ?', [$id]);

        if (count($variationChk) == 0) {
            $response = [
                'error' => 'A variation with this id does not exist.'
            ];

            return response($response, 400);
        }

        try {
            DB::statement(
                'UPDATE variations SET name = COALESCE(:name, name), code = COALESCE(:code, code), price = COALESCE(:price, price);',
                [
                    'name' => $request['name'],
                    'code' => $request['code'],
                    'price' => $request['price']
                ]
            );
        } catch (Exception $e) {
            return response(['error' => 'Update statement failed.'], 400);
        }

        $updated = DB::select('select * from variations where id = ?', [$id]);

        return response($updated, 200);
    }
}
