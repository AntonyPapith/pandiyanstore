<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class WhatsAppService
{
    public function sendOrderNotifications(Order $order): void
    {
        if (! $this->configured()) {
            Log::notice('WhatsApp notification skipped: Cloud API credentials are incomplete.', ['order_id' => $order->id]);
            return;
        }

        $products = $order->items->map(fn ($item): string => $item->product_name.' x '.$item->quantity)->implode(', ');
        $total = number_format((float) $order->total_amount, 2, '.', '');
        $payment = strtoupper($order->payment_method);
        $address = collect([$order->address, $order->area, $order->city, $order->nearby_landmark ? 'Near '.$order->nearby_landmark : null])->filter()->implode(', ');

        if (config('services.whatsapp.customer_template') && $order->customer_phone) {
            $this->sendTemplate($order->customer_phone, config('services.whatsapp.customer_template'), [
                $order->customer_name, $order->order_number, $products, $total, $payment,
            ], $order);
        }

        $adminPhone = config('services.whatsapp.admin_phone') ?: User::where('is_admin', true)->whereNotNull('phone')->value('phone');
        if (config('services.whatsapp.admin_template') && $adminPhone) {
            $this->sendTemplate($adminPhone, config('services.whatsapp.admin_template'), [
                $order->order_number, $order->customer_name, $this->normalizePhone($order->customer_phone),
                $products, $total, $payment, $address,
            ], $order);
        }
    }

    private function sendTemplate(string $recipient, string $template, array $parameters, Order $order): void
    {
        $recipient = $this->normalizePhone($recipient);
        if ($recipient === '') {
            return;
        }

        try {
            $response = Http::withToken(config('services.whatsapp.access_token'))->acceptJson()->timeout(12)
                ->post('https://graph.facebook.com/'.config('services.whatsapp.api_version').'/'.config('services.whatsapp.phone_number_id').'/messages', [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $recipient,
                    'type' => 'template',
                    'template' => [
                        'name' => $template,
                        'language' => ['code' => config('services.whatsapp.template_language', 'en_US')],
                        'components' => [[
                            'type' => 'body',
                            'parameters' => collect($parameters)->map(fn ($value): array => ['type' => 'text', 'text' => (string) $value])->all(),
                        ]],
                    ],
                ]);

            if ($response->failed()) {
                Log::error('WhatsApp order notification failed.', [
                    'order_id' => $order->id,
                    'template' => $template,
                    'status' => $response->status(),
                    'error' => $response->json('error.message', 'Unknown Meta API error'),
                ]);
            }
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private function configured(): bool
    {
        return filled(config('services.whatsapp.access_token'))
            && filled(config('services.whatsapp.phone_number_id'))
            && filled(config('services.whatsapp.api_version'));
    }

    private function normalizePhone(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);
        return strlen($digits) === 10 ? '91'.$digits : ltrim($digits, '0');
    }
}
