<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function store(Request $request, OrderService $orders): RedirectResponse
    {
        $data = $request->validate([
            'payment_method' => ['required', Rule::in(['cod'])],
        ]);
        $cart = $request->session()->get('cart', []);
        $order = $orders->place($request->user(), $cart, $data['payment_method']);

        $request->session()->forget('cart');
        $request->session()->flash('order_success', 'Your order has been placed successfully.');
        $request->session()->flash('placed_order_id', $order->id);

        return redirect()->route('customer.account')->withFragment('orders');
    }

    public function success(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load('items');
        $adminPhone = User::where('is_admin', true)->whereNotNull('phone')->value('phone');

        return view('orders.success', compact('order', 'adminPhone'));
    }
}
