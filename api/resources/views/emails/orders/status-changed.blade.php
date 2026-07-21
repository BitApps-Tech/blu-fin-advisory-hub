<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Status Updated</title>
</head>
<body>
    <h2>Order Status Updated</h2>
    
    <p><strong>Order Code:</strong> {{ $order->code }}</p>
    <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
    
    <p>
        <strong>Status Changed:</strong> 
        {{ ucfirst($oldStatus) }} → {{ ucfirst($newStatus) }}
    </p>
    
    <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
    
    <p>You can view the order details in the admin panel.</p>
</body>
</html>

