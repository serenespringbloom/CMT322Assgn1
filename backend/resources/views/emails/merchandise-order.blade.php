<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #ffeaea;
        }
        .order-details {
            margin: 20px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .item {
            margin: 10px 0;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .total {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Confirmation</h1>
            <p>Thank you for your purchase!</p>
        </div>

        <div class="order-details">
            <p>Dear {{ $customerName }},</p>
            <p>Your order #{{ $orderNumber }} has been received and is being processed.</p>

            <h2>Order Summary:</h2>
            @foreach($items as $item)
                <div class="item">
                    <p><strong>{{ $item['name'] }}</strong></p>
                    <p>Size: {{ $item['size'] }}</p>
                    <p>Quantity: {{ $item['quantity'] }}</p>
                    <p>Price: RM{{ number_format($item['price'], 2) }}</p>
                    <p>Subtotal: RM{{ number_format($item['total'], 2) }}</p>
                </div>
            @endforeach

            <div class="total">
                <p>Total Amount: RM{{ number_format($totalAmount, 2) }}</p>
            </div>
        </div>

        <div class="footer">
            <p>If you have any questions about your order, please contact us at support@mcb.com</p>
            <p>© {{ date('Y') }} Malam Citra Bayu. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 