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
        .ticket-details {
            margin: 20px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .event-info {
            margin: 20px 0;
            padding: 15px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .seats {
            margin: 10px 0;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .total {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
        }
        .important-note {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            border-radius: 5px;
            color: #856404;
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
            <h1>Ticket Confirmation</h1>
            <p>Thank you for your purchase!</p>
        </div>

        <div class="ticket-details">
            <p>Dear {{ $customerName }},</p>
            <p>Your ticket order #{{ $orderNumber }} has been confirmed.</p>

            <div class="event-info">
                <h3>Event Details:</h3>
                <p><strong>Event:</strong> Malam Citra Bayu 16th</p>
                <p><strong>Date:</strong> {{ $eventDate }}</p>
                <p><strong>Time:</strong> {{ $eventTime }}</p>
                <p><strong>Venue:</strong> {{ $venue }}</p>
            </div>

            <h3>Your Seats:</h3>
            <div class="seats">
                @foreach($seats as $seat)
                    <p>Seat: {{ $seat->seat_id }}</p>
                @endforeach
            </div>

            <div class="total">
                <p>Total Amount: RM{{ number_format($totalAmount, 2) }}</p>
            </div>

            <div class="important-note">
                <p><strong>Important:</strong></p>
                <ul>
                    <li>Please arrive at least 30 minutes before the event.</li>
                    <li>Bring this confirmation email or your order number.</li>
                    <li>Valid ID may be required for verification.</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>If you have any questions about your tickets, please contact us at support@mcb.com</p>
            <p>© {{ date('Y') }} Malam Citra Bayu. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 