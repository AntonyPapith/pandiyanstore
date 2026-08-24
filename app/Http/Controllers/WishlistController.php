<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $ids = array_keys($request->session()->get('wishlist', []));

        return view('wishlist.index', [
            'products' => Product::whereIn('id', $ids)->latest()->get(),
        ]);
    }

    public function toggle(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $wishlist = $request->session()->get('wishlist', []);
        $shouldAdd = $request->has('wishlisted')
            ? $request->boolean('wishlisted')
            : ! isset($wishlist[$product->id]);

        if (! $shouldAdd) {
            unset($wishlist[$product->id]);
            $message = 'Product removed from wishlist.';
        } else {
            $wishlist[$product->id] = true;
            $message = 'Product added to wishlist.';
        }
        $request->session()->put('wishlist', $wishlist);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'active' => isset($wishlist[$product->id]),
                'wishlist_count' => count($wishlist),
            ]);
        }

        return back()->with('wishlist_success', $message);
    }
}
