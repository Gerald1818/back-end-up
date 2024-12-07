<?php
namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'barcode' => 'required|integer|unique:products,barcode',
            'product_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer',
            'available_quantity' => 'required|integer',
            'category' => 'required|string',
        ]);

        $product = Product::create($validatedData);

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }


    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }


    public function update(Request $request, $id)
    {
        $product = Product::find($id);
    
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        $validatedData = $request->validate([
            'barcode' => 'integer|unique:products,barcode,' . $product->id,
            'product_name' => 'string|max:255', 
            'description' => 'string',
            'price' => 'integer',
            'available_quantity' => 'integer',
        ]);
    
        $product->update($validatedData);
    
        return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }
    


    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function getAllBarcodes()
    {
        return response()->json(Product::pluck('barcode'));
    }

}