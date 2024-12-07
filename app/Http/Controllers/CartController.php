<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('product')->where('user_id', Auth::id())->get();
        return response()->json($cart);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        // Check if the product exists and has enough stock before adding to cart
        $product = Product::find($validatedData['product_id']);
    
        if ($product->available_quantity <= 0) {
            return response()->json(['message' => 'Product out of stock'], 400);
        }
    
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $validatedData['product_id'])
            ->first();
    
        if ($cartItem) {
            $cartItem->update(['quantity' => $cartItem->quantity + $validatedData['quantity']]);
        } else {
            $validatedData['user_id'] = Auth::id();
            Cart::create($validatedData);
        }
    
        return response()->json(['message' => 'Item added to cart successfully']);
    }
    

    public function updateQuantity(Request $request, $cartId) {
        
        $validatedData = $request->validate([
            'quantity_change' => 'required|integer', 
        ]);
        
        $cartItem = Cart::where('id', $cartId)->where('user_id', Auth::id())->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Item not found in your cart'], 404);
        }
    
        $product = Product::findOrFail($cartItem->product_id);
    
       
        $newQuantity = $cartItem->quantity + $validatedData['quantity_change'];
    
        if ($newQuantity <= 0) {
            return response()->json(['message' => 'Quantity must be at least 1'], 400);
        }
    
        if ($newQuantity > $product->available_quantity) {
            return response()->json(['message' => 'Not enough stock available'], 400);
        }
    
        $cartItem->update(['quantity' => $newQuantity]);
    
        return response()->json([
            'message' => 'Cart item quantity updated successfully',
            'cart_item' => $cartItem,
        ]);
    }
    

    public function destroy($id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();
    
        if (!$cartItem) {
            return response()->json(['message' => 'Item not found in your cart'], 404);
        }
    
        $product = $cartItem->product;
    
        if (!$product) {
            return response()->json(['message' => 'Product no longer available'], 404);
        }
    
        $cartItem->delete();
    
        return response()->json(['message' => 'Item removed from cart successfully']);
    }

    // Clear the cart call, this medthod after checking out
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'Cart cleared successfully']);
    }
}
