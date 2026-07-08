<x-mail::message>
# Gracias por tu pedido, {{ $order->customer_name }}

Tu pedido **#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}** fue registrado correctamente
y se encuentra en estado **{{ ucfirst($order->status) }}**.

<x-mail::table>
| Combo | Cant. | Precio | Subtotal |
|:------|:-----:|-------:|---------:|
@foreach($order->items as $item)
| {{ $item->combo_nombre }} | {{ $item->cantidad }} | S/. {{ number_format($item->precio_unitario, 2) }} | S/. {{ number_format($item->subtotal, 2) }} |
@endforeach
| **Total** | | | **S/. {{ number_format($order->total, 2) }}** |
</x-mail::table>

**Método de pago elegido:** {{ $order->metodo_pago_label }}

Nos pondremos en contacto contigo al **{{ $order->customer_phone }}** para coordinar
el pago y la entrega o el recojo en tienda.

Casas Cerámicos Hatun Wasi<br>
Jr. Sandia 206, Juliaca, Perú
</x-mail::message>
