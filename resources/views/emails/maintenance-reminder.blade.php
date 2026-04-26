<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Maintenance Reminder</title>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f9fafb;
            color: #111827;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }
        .header {
            background-color: #F53003;
            padding: 40px;
            text-align: center;
            color: #ffffff;
        }
        .content {
            padding: 40px;
        }
        .footer {
            padding: 24px;
            text-align: center;
            background-color: #f3f4f6;
            color: #6b7280;
            font-size: 12px;
        }
        h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        p {
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .vehicle-info {
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 16px;
            padding: 24px;
            margin: 24px 0;
        }
        .vehicle-info h2 {
            margin: 0 0 8px 0;
            font-size: 18px;
            color: #F53003;
        }
        .btn {
            display: inline-block;
            background-color: #F53003;
            color: #ffffff;
            padding: 16px 32px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>AutoCheck Reminder</h1>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $vehicle->owner_name }}</strong>,</p>
            <p>This is a friendly reminder that your vehicle is scheduled for maintenance tomorrow.</p>
            
            <div class="vehicle-info">
                <h2>{{ $vehicle->make }} {{ $vehicle->model }}</h2>
                <p style="margin: 0; color: #6b7280;">Plate Number: <strong>{{ $vehicle->plate_number }}</strong></p>
                <p style="margin: 8px 0 0 0; color: #6b7280;">Scheduled Date: <strong>{{ $vehicle->next_service_date->format('F j, Y') }}</strong></p>
            </div>

            <p>Regular maintenance ensures your vehicle remains in peak condition and helps prevent costly repairs down the road.</p>
            
            <p style="text-align: center;">
                <a href="{{ config('app.url') }}" class="btn">View Details</a>
            </p>
            
            <p>If you have already performed this maintenance, please disregard this email.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} AutoCheck Maintenance System. All rights reserved.
        </div>
    </div>
</body>
</html>
