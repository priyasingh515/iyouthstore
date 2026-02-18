<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        // Get Categories
        $categories = \App\Models\Category::where('parent_id', 0)->get();

        // Base Query 
        $query = Product::where('added_by','admin')
            ->isApprovedPublished();

        // Category Filter
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->get();

        return view('seller.buy_product.index', compact('products', 'categories'));
    }


    // public function sellerAddToCart(Request $request)
    // {

    //     $product = Product::findOrFail($request->product_id);

    //     $qty = $request->qty ?? 1;

    //     $stock = $product->stocks->sum('qty');

    //     if ($qty > $stock) {
    //         return back()->with('error', 'Quantity exceeds available stock');
    //     }

    //     Cart::updateOrCreate(
    //         [
    //             'user_id' => auth()->id(),
    //             'product_id' => $product->id,
    //         ],
    //         [
    //             'price' => $product->unit_price,
    //             'quantity' => $qty,
    //         ]
    //     );
    //     return back()->with('success', 'Product added to cart');
    // }


    // public function sellerAddToCart(Request $request)
    // {
    //     $product = Product::findOrFail($request->product_id);

    //     $qty = $request->qty ?? 1;

    //     $stock = $product->stocks->sum('qty');

    //     // Stock check
    //     if ($qty > $stock) {
    //         return back()->with('error', 'Quantity exceeds available stock');
    //     }

    //     // Check if already in cart
    //     $cart = Cart::where('user_id', auth()->id())
    //         ->where('product_id', $product->id)
    //         ->first();

    //     if ($cart) {
    //         return back()->with('error', 'Product already in cart. Please update quantity from cart page.');
    //     }

    //     // Create new cart item
    //     Cart::create([
    //         'user_id' => auth()->id(),
    //         'product_id' => $product->id,
    //         'price' => $product->unit_price,
    //         'quantity' => $qty,
    //     ]);

    //     return back()->with('success', 'Product added to cart');
    // }

    // public function sellerAddToCart(Request $request)
    // {
    //     $product = Product::findOrFail($request->product_id);

    //     $qty = $request->qty ?? 1;

    //     $stock = $product->stocks->sum('qty');

    //     if ($qty > $stock) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Quantity exceeds available stock'
    //         ]);
    //     }

    //     $cart = Cart::where('user_id', auth()->id())
    //         ->where('product_id', $product->id)
    //         ->first();

    //     if ($cart) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Already in cart. Update from cart page.'
    //         ]);
    //     }

    //     Cart::create([
    //         'user_id' => auth()->id(),
    //         'product_id' => $product->id,
    //         'price' => $product->unit_price,
    //         'quantity' => $qty,
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product added to cart'
    //     ]);
    // }


    public function sellerAddToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $qty = $request->qty ?? 1;

        $stock = $product->stocks->sum('qty');

        if ($qty > $stock) {
            return response()->json([
                'status' => false,
                'message' => 'Quantity exceeds available stock'
            ]);
        }

        $cart = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {

            $cartCount = Cart::where('user_id', auth()->id())->count();

            return response()->json([
                'status' => false,
                'message' => 'Already in cart. Update from cart page.',
                'cart_count' => $cartCount
            ]);
        }

        Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'owner_id' => $product->user_id,
            'price' => $product->unit_price,
            'quantity' => $qty,
        ]);

        $cartCount = Cart::where('user_id', auth()->id())->count();

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart',
            'cart_count' => $cartCount
        ]);
    }



    public function cart()
    {
        $carts = Cart::where('user_id', auth()->id())
            ->with('product.thumbnail')
            ->get();

        return view('seller.buy_product.cart', compact('carts'));
    }

    public function updateCart(Request $request)
    {
        $cart = Cart::where('user_id', auth()->id())
            ->where('id', $request->cart_id)
            ->first();

        if (!$cart) {
            return response()->json([
                'status' => false,
                'message' => 'Cart item not found'
            ]);
        }

        $product = $cart->product;
        $stock = $product->stocks->sum('qty');

        if ($request->qty > $stock) {
            return response()->json([
                'status' => false,
                'message' => 'Stock not available'
            ]);
        }

        $cart->update([
            'quantity' => $request->qty
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cart updated'
        ]);
    }

    public function deleteCart(Request $request)
    {
        $cart = Cart::where('user_id', auth()->id())
            ->where('id', $request->cart_id)
            ->first();

        if (!$cart) {
            return response()->json([
                'status' => false,
                'message' => 'Cart item not found'
            ]);
        }

        $cart->delete();

        return response()->json([
            'status' => true,
            'message' => 'Removed from cart'
        ]);
    }
}
