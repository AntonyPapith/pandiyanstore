<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $data = $this->cartData($request);
        $fallbackCategory = $data['products']->first()?->category_id;

        return view('cart.index', [
            ...$data,
            'continueShoppingUrl' => $request->session()->get(
                'continue_shopping_url',
                $fallbackCategory ? route('categories.products', $fallbackCategory) : route('home'),
            ),
        ]);
    }

    public function add(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $quantity = $request->validate(['quantity' => ['nullable', 'integer', 'min:1']])['quantity'] ?? 1;
        $cart = $request->session()->get('cart', []);
        $cart[$product->id] = min($product->quantity, ($cart[$product->id] ?? 0) + $quantity);
        if ($product->quantity > 0) {
            $request->session()->put('cart', $cart);
            $request->session()->put('continue_shopping_url', route('categories.products', $product->category_id));
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $product->quantity > 0 ? 'Product added to cart.' : 'This product is out of stock.',
                'cart_count' => count(array_filter($request->session()->get('cart', []), fn($quantity) => $quantity > 0)),
            ], $product->quantity > 0 ? 200 : 422);
        }

        return back()->with('cart_success', 'Product added to cart.');
    }

    public function update(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $quantity = $request->validate(['quantity' => ['required', 'integer', 'min:1']])['quantity'];
        $cart = $request->session()->get('cart', []);
        $cart[$product->id] = min($quantity, $product->quantity);
        $request->session()->put('cart', $cart);

        if ($request->expectsJson()) {
            $data = $this->cartData($request);

            return response()->json([
                'quantity' => $cart[$product->id],
                'line_amount' => $product->cartPrice() * $cart[$product->id],
                'total' => $data['total'],
                'cart_count' => count(array_filter($cart, fn($cartQuantity) => $cartQuantity > 0)),
            ]);
        }

        return back();
    }

    public function remove(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product->id]);
        $request->session()->put('cart', $cart);

        return back();
    }

    public function checkout(Request $request): View|RedirectResponse
    {
        if ($request->user()->customerDetail && ! $request->boolean('edit')) {
            return redirect()->route('payment');
        }

        return view('cart.checkout', [...$this->cartData($request), 'detail' => $request->user()->customerDetail]);
    }

    public function saveAddress(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone,' . $request->user()->id],
            'city' => ['required', 'string', 'max:100'],
            'area' => ['required', 'string', 'max:150'],
            'nearby_landmark' => ['nullable', 'string', 'max:150'],
            'address' => ['required', 'string', 'max:1000'],
        ]);
        $request->user()->update(['name' => $data['name'], 'email' => $data['email'], 'phone' => $data['phone']]);
        $request->user()->customerDetail()->updateOrCreate([], collect($data)->except(['name', 'email', 'phone'])->all());

        return redirect()->route('payment');
    }

    public function payment(Request $request): View|RedirectResponse
    {
        if (! $request->user()->customerDetail) {
            return redirect()->route('checkout');
        }

        $cartData = $this->cartData($request);
        if ($cartData['products']->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Your cart is empty.']);
        }

        return view('cart.payment', [
            ...$cartData,
            'detail' => $request->user()->customerDetail,
            'razorpayAvailable' => filled(config('services.razorpay.key_id')) && filled(config('services.razorpay.key_secret')),
            'codAvailable' => (bool) config('services.payment.cod_available', false)
                && $cartData['products']->every(fn(Product $product): bool => $product->cod_available),
        ]);
    }

    private function cartData(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get()->map(function (Product $product) use ($cart) {
            $product->cart_quantity = min($cart[$product->id], $product->quantity);
            $product->cart_price = (float) ($product->discount_price ?? $product->price);
            $product->cart_amount = $product->cart_price * $product->cart_quantity;

            return $product;
        });

        return ['products' => $products, 'total' => $products->sum('cart_amount')];
    }
}
