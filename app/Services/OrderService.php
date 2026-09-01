<?php

namespace App\Services;

use App\Mail\AdminOrderPlaced;
use App\Mail\CustomerOrderPlaced;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Throwable;

class OrderService
{
    public function __construct(private WhatsAppService $whatsapp) {}

    public function place(User $user, array $cart, string $paymentMethod, array $payment = []): Order
    {
        if ($cart === []) {
            throw ValidationException::withMessages(['cart' => 'Your cart is empty.']);
        }
        $detail = $user->customerDetail;
        if (! $detail) {
            throw ValidationException::withMessages(['address' => 'Please add a delivery address.']);
        }

        $order = DB::transaction(function () use ($user, $cart, $paymentMethod, $payment, $detail): Order {
            $products = Product::whereIn('id', array_keys($cart))->lockForUpdate()->get();
            if ($products->count() !== count($cart)) {
                throw ValidationException::withMessages(['cart' => 'One or more products are unavailable.']);
            }
            $total = 0;
            foreach ($products as $product) {
                if ($cart[$product->id] > $product->quantity) {
                    throw ValidationException::withMessages(['cart' => "Only {$product->quantity} {$product->name} available."]);
                }
                $total += $product->cartPrice() * $cart[$product->id];
            }

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'PS'.now()->format('YmdHis').str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT),
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentMethod === 'cod' ? 'cash_on_delivery' : 'pending_verification',
                'upi_reference' => $payment['upi_reference'] ?? null,
                'razorpay_order_id' => $payment['razorpay_order_id'] ?? null,
                'razorpay_payment_id' => $payment['razorpay_payment_id'] ?? null,
                'order_status' => $paymentMethod === 'cod' ? 'placed' : 'payment_pending',
                'total_amount' => $total,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone,
                'city' => $detail->city,
                'area' => $detail->area,
                'nearby_landmark' => $detail->nearby_landmark,
                'address' => $detail->address,
            ]);
            foreach ($products as $product) {
                $quantity = $cart[$product->id];
                $order->items()->create(['product_id' => $product->id, 'product_name' => $product->name, 'image_path' => $product->image_path, 'quantity' => $quantity, 'unit_price' => $product->cartPrice(), 'amount' => $product->cartPrice() * $quantity]);
                $product->decrement('quantity', $quantity);
            }

            return $order;
        });

        $order->load('items');
        $this->sendOrderEmails($order);
        $this->whatsapp->sendOrderNotifications($order);

        return $order;
    }

    private function sendOrderEmails(Order $order): void
    {
        if (filter_var($order->customer_email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($order->customer_email)->send(new CustomerOrderPlaced($order));
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        $adminEmails = User::where('is_admin', true)->whereNotNull('email')->pluck('email')
            ->filter(fn (string $email): bool => filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
            ->unique()->values()->all();

        if ($adminEmails !== []) {
            try {
                Mail::to($adminEmails)->send(new AdminOrderPlaced($order));
            } catch (Throwable $exception) {
                report($exception);
            }
        }
    }

    public function total(array $cart): float
    {
        return Product::whereIn('id', array_keys($cart))->get()->sum(fn (Product $product) => $product->cartPrice() * ($cart[$product->id] ?? 0));
    }
}
