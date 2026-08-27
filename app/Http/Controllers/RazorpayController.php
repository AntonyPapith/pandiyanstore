<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RazorpayController extends Controller
{
    public function create(Request $request, OrderService $orders): JsonResponse
    {
        $cart = $request->session()->get('cart', []);
        abort_if($cart === [], 422, 'Your cart is empty.');
        abort_unless($request->user()->customerDetail, 422, 'Please add a delivery address.');
        $amount = (int) round($orders->total($cart) * 100);
        abort_if($amount < 100, 422, 'Order amount must be at least ₹1.');

        $response = Http::withBasicAuth(config('services.razorpay.key_id'), config('services.razorpay.key_secret'))
            ->acceptJson()->post('https://api.razorpay.com/v1/orders', [
                'amount' => $amount,
                'currency' => 'INR',
                'receipt' => 'cart_'.now()->format('YmdHis'),
                'notes' => ['customer_id' => (string) $request->user()->id],
            ]);
        if ($response->failed()) {
            return response()->json(['message' => $response->json('error.description', 'Razorpay could not create the payment order.')], 502);
        }

        $razorpayOrderId = $response->json('id');
        $contact = preg_replace('/\D+/', '', (string) $request->user()->phone);
        $contact = strlen($contact) === 10 ? '+91'.$contact : '+'.ltrim($contact, '+');
        $request->session()->put('razorpay_checkout', ['order_id' => $razorpayOrderId, 'amount' => $amount]);

        return response()->json([
            'key' => config('services.razorpay.key_id'),
            'order_id' => $razorpayOrderId,
            'amount' => $amount,
            'currency' => 'INR',
            'name' => 'Pandiyan Store',
            'customer' => ['name' => $request->user()->name, 'email' => $request->user()->email, 'contact' => $contact],
        ]);
    }

    public function verify(Request $request, OrderService $orders): JsonResponse
    {
        $data = $request->validate([
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);
        $expected = $request->session()->get('razorpay_checkout');
        abort_unless($expected && hash_equals($expected['order_id'], $data['razorpay_order_id']), 422, 'Invalid payment order.');
        $signature = hash_hmac('sha256', $expected['order_id'].'|'.$data['razorpay_payment_id'], config('services.razorpay.key_secret'));
        abort_unless(hash_equals($signature, $data['razorpay_signature']), 422, 'Payment signature verification failed.');

        $payment = Http::withBasicAuth(config('services.razorpay.key_id'), config('services.razorpay.key_secret'))
            ->acceptJson()->get('https://api.razorpay.com/v1/payments/'.$data['razorpay_payment_id']);
        abort_unless($payment->successful(), 422, 'Unable to verify the payment. Please contact support before paying again.');
        abort_unless((int) $payment->json('amount') === (int) $expected['amount'], 422, 'Payment amount verification failed.');

        if ($payment->json('status') === 'authorized') {
            $payment = Http::withBasicAuth(config('services.razorpay.key_id'), config('services.razorpay.key_secret'))
                ->acceptJson()->post('https://api.razorpay.com/v1/payments/'.$data['razorpay_payment_id'].'/capture', [
                    'amount' => (int) $expected['amount'],
                    'currency' => 'INR',
                ]);
        }

        abort_unless($payment->successful() && $payment->json('status') === 'captured', 422, 'Payment could not be captured. Please contact support before paying again.');

        $order = $orders->place($request->user(), $request->session()->get('cart', []), 'upi', $data);
        $request->session()->forget(['cart', 'razorpay_checkout']);
        $request->session()->flash('order_success', 'Payment successful! Your order has been placed.');
        $request->session()->flash('placed_order_id', $order->id);

        return response()->json(['redirect' => route('customer.account').'#orders']);
    }
}
