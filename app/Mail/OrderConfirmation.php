<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hatun Wasi — Confirmación de tu pedido #' . str_pad($this->order->id, 5, '0', STR_PAD_LEFT),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order-confirmation',
            with: ['order' => $this->order->load('items')],
        );
    }
}
