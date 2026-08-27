<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminOrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New order '.$this->order->order_number.' from '.$this->order->customer_name);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.orders.admin-placed');
    }

    public function attachments(): array
    {
        return [];
    }
}
