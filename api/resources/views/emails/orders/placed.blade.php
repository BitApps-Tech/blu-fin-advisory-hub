<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Order Received</title>
</head>
<body>
    <h2>New Order Received</h2>
    
    <p><strong>Order Code:</strong> {{ $order->code }}</p>
    <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
    <p><strong>Phone:</strong> {{ $order->phone }}</p>
    @if($order->email)
    <p><strong>Email:</strong> {{ $order->email }}</p>
    @endif
    <p><strong>Order Type:</strong> {{ ucfirst($order->order_type) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
    
    @if($order->scheduled_at)
    <p><strong>Scheduled For:</strong> {{ $order->scheduled_at->format('Y-m-d H:i') }}</p>
    @endif
    
    @if($order->note)
    <p><strong>Note:</strong> {{ $order->note }}</p>
    @endif
    
    <h3>Order Items:</h3>
    <ul>
        @foreach($order->items as $item)
        <li>
            {{ $item->name }} - 
            Qty: {{ $item->qty }} - 
            Unit Price: ${{ number_format($item->unit_price, 2) }} - 
            Total: ${{ number_format($item->total, 2) }}
        </li>
        @endforeach
    </ul>
    
    <p><strong>Order Total:</strong> ${{ number_format($order->total, 2) }}</p>
</body>
</html>

