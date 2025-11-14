<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.5;
            color: #333;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 10mm;
        }
        
        .row {
            clear: both;
            margin-bottom: 15px;
        }

        .col-half {
            float: left;
            width: 48%;
            margin-right: 4%;
        }

        .col-half:last-child {
            margin-right: 0;
        }
        
        .header {
            border-bottom: 3px solid #007BFF;
            padding: 15px 0 20px 0;
            margin-bottom: 20px;
            overflow: auto;
        }

        .header-logo {
            float: left;
            font-size: 24px;
            font-weight: 700;
            color: #007BFF;
            line-height: 1;
        }

        .header-meta {
            float: right;
            text-align: right;
            font-size: 9px;
            line-height: 1.4;
        }
        
        .header-meta-item {
            margin-bottom: 4px;
        }

        .header-meta-label {
            color: #888;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .header-meta-value {
            font-size: 11px;
            font-weight: 600;
            color: #333;
        }
        
        .invoice-title {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            color: #333;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            clear: both;
        }

        .info-block {
            border: 1px solid #eee;
            border-left: 3px solid #007BFF;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 4px;
            background: #fff;
        }

        .info-header {
            color: #007BFF;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-row {
            margin-bottom: 6px;
            overflow: auto;
        }

        .info-label {
            float: left;
            width: 35%;
            color: #666;
            font-weight: 500;
        }

        .info-value {
            float: left;
            width: 65%;
            color: #333;
            font-weight: 600;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .frame-photo-section {
            margin: 20px 0;
            text-align: center;
        }

        .frame-photo-label {
            font-size: 11px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .frame-photo-placeholder {
            border: 2px dashed #ccc;
            padding: 30px;
            background: #f9f9f9;
            color: #999;
            font-size: 9px;
        }

        .glass-details-section {
            margin: 20px 0;
        }

        .glass-details-box {
            border: 1px solid #eee;
            border-left: 4px solid #28a745;
            padding: 12px;
            background: #f9f9f9;
            border-radius: 4px;
        }

        .glass-details-title {
            font-size: 11px;
            font-weight: 700;
            color: #28a745;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .glass-details-content {
            font-size: 10px;
            color: #555;
            line-height: 1.6;
        }

        .pricing-section {
            margin-top: 30px;
            border-top: 2px solid #007BFF;
            padding-top: 15px;
        }

        .pricing-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .pricing-label {
            display: table-cell;
            width: 70%;
            text-align: right;
            padding-right: 15px;
            font-size: 10px;
            color: #666;
        }

        .pricing-value {
            display: table-cell;
            width: 30%;
            text-align: right;
            font-size: 11px;
            font-weight: 600;
            color: #333;
        }

        .total-row {
            border-top: 2px solid #007BFF;
            padding-top: 10px;
            margin-top: 10px;
        }

        .total-label {
            font-size: 14px;
            font-weight: 700;
            color: #007BFF;
        }

        .total-value {
            font-size: 16px;
            font-weight: 700;
            color: #007BFF;
        }

        .examination-section {
            margin-top: 25px;
            border: 1px solid #eee;
            border-left: 4px solid #ffc107;
            padding: 12px;
            background: #fffbf0;
            border-radius: 4px;
        }

        .examination-title {
            font-size: 11px;
            font-weight: 700;
            color: #d39e00;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .examination-details {
            font-size: 9px;
            color: #555;
        }

        .examination-row {
            margin-bottom: 5px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #888;
            font-size: 8px;
        }

        .notes-section {
            margin-top: 20px;
            padding: 12px;
            background: #f9f9f9;
            border-left: 4px solid #6c757d;
            border-radius: 4px;
        }

        .notes-title {
            font-size: 10px;
            font-weight: 700;
            color: #6c757d;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .notes-content {
            font-size: 9px;
            color: #555;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-logo">{{ $store->name ?? 'EYECARE CLINIC' }}</div>
            <div class="header-meta">
                @if(!empty($store->address))
                <div class="header-meta-item">
                    <div class="header-meta-label">Address</div>
                    <div class="header-meta-value">{{ $store->address }}</div>
                </div>
                @endif
                @if(!empty($store->phone_number))
                <div class="header-meta-item">
                    <div class="header-meta-label">Phone</div>
                    <div class="header-meta-value">{{ $store->phone_number }}</div>
                </div>
                @endif
                @if(!empty($store->email))
                <div class="header-meta-item">
                    <div class="header-meta-label">Email</div>
                    <div class="header-meta-value">{{ $store->email }}</div>
                </div>
                @endif
            </div>
            <div class="invoice-title">Invoice</div>
        </div>

        <div class="row">
            <div class="col-half">
                <div class="info-block" style="border-left-color: #17a2b8;">
                    <div class="info-header">Invoice Details</div>
                    <div class="info-row">
                        <div class="info-label">Invoice Number</div>
                        <div class="info-value"><strong>{{ $order->invoice_number }}</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Invoice Date</div>
                        <div class="info-value">{{ $order->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Expected Completion</div>
                        <div class="info-value">{{ $order->expected_completion_date->format('M d, Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <strong style="text-transform: uppercase;">{{ $order->status }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-half">
                <div class="info-block" style="border-left-color: #28a745;">
                    <div class="info-header">Customer Information</div>
                    <div class="info-row">
                        <div class="info-label">Customer Name</div>
                        <div class="info-value"><strong>{{ $customer->name ?? 'N/A' }}</strong></div>
                    </div>
                    @if(!empty($customer->phone_number))
                    <div class="info-row">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $customer->phone_number }}</div>
                    </div>
                    @endif
                    @if(!empty($customer->email))
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $customer->email }}</div>
                    </div>
                    @endif
                    @if(!empty($customer->address))
                    <div class="info-row">
                        <div class="info-label">Address</div>
                        <div class="info-value">{{ $customer->address }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($order->frame_photo)
        <div class="frame-photo-section">
            <div class="frame-photo-label">Frame Photo</div>
            <div class="frame-photo-placeholder">
                Frame photo attached (Reference: {{ basename($order->frame_photo) }})
            </div>
        </div>
        @endif

        @if($order->glass_details)
        <div class="glass-details-section">
            <div class="glass-details-box">
                <div class="glass-details-title">Glass Details</div>
                <div class="glass-details-content">
                    {{ $order->glass_details }}
                </div>
            </div>
        </div>
        @endif

        @if($eyeExamination)
        <div class="examination-section">
            <div class="examination-title">Attached Eye Examination Data</div>
            <div class="examination-details">
                <div class="examination-row">
                    <strong>Examination Date:</strong> {{ $eyeExamination->exam_date->format('M d, Y') }}
                </div>
                @if($eyeExamination->od_sphere || $eyeExamination->os_sphere)
                <div class="examination-row">
                    <strong>Prescription:</strong>
                    @if($eyeExamination->od_sphere)
                        OD: {{ $eyeExamination->od_sphere }} 
                        @if($eyeExamination->od_cylinder) / {{ $eyeExamination->od_cylinder }} @endif
                        @if($eyeExamination->od_axis) x {{ $eyeExamination->od_axis }} @endif
                    @endif
                    @if($eyeExamination->os_sphere)
                        | OS: {{ $eyeExamination->os_sphere }}
                        @if($eyeExamination->os_cylinder) / {{ $eyeExamination->os_cylinder }} @endif
                        @if($eyeExamination->os_axis) x {{ $eyeExamination->os_axis }} @endif
                    @endif
                </div>
                @endif
                @if($eyeExamination->add_power)
                <div class="examination-row">
                    <strong>Add Power:</strong> {{ $eyeExamination->add_power }}
                </div>
                @endif
                @if($eyeExamination->pd_distance)
                <div class="examination-row">
                    <strong>PD Distance:</strong> {{ $eyeExamination->pd_distance }} mm
                </div>
                @endif
                @if($eyeExamination->diagnosis)
                <div class="examination-row">
                    <strong>Diagnosis:</strong> {{ $eyeExamination->diagnosis }}
                </div>
                @endif
            </div>
        </div>
        @endif

        <div class="pricing-section">
            <div class="pricing-row">
                <div class="pricing-label">Subtotal:</div>
                <div class="pricing-value">{{ number_format($order->total_price, 2) }}</div>
            </div>
            <div class="pricing-row total-row">
                <div class="pricing-label total-label">Total Amount:</div>
                <div class="pricing-value total-value">{{ number_format($order->total_price, 2) }}</div>
            </div>
        </div>

        @if($order->notes)
        <div class="notes-section">
            <div class="notes-title">Notes</div>
            <div class="notes-content">
                {{ $order->notes }}
            </div>
        </div>
        @endif

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>This is a computer-generated invoice. No signature required.</p>
            <p>Generated on {{ now()->format('M d, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>

