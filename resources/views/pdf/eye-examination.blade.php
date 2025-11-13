<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eye Examination Report - {{ $examination->exam_date->format('Y-m-d') }}</title>
    <style>
        /* Base and PDF Configuration */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9.5px; /* Reduced font size for single page fit */
            line-height: 1.4; /* Tighter line height */
            color: #333;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* Utility Layouts for Dompdf - Tighter Layout */
        .container {
            padding: 5mm 10mm; /* Minimal padding for the content area */
        }
        
        .row {
            clear: both;
            margin-bottom: 10px; /* Reduced margin */
        }

        .col-half {
            float: left;
            width: 49%; /* Reduced width for slightly tighter columns */
            margin-right: 2%; /* Reduced gap */
        }

        .col-half:last-child {
            margin-right: 0;
        }
        
        /* Header */
        .header {
            border-bottom: 3px solid #007BFF;
            padding: 10px 0 15px 0; /* Reduced padding */
            margin-bottom: 15px; /* Reduced margin */
            overflow: auto;
        }

        .header-logo {
            float: left;
            font-size: 20px; /* Slightly reduced font size */
            font-weight: 700;
            color: #007BFF;
            line-height: 1;
        }

        .header-meta {
            float: right;
            text-align: right;
            font-size: 8px; /* Micro font size */
            line-height: 1.2;
        }
        
        .header-meta-item {
            margin-bottom: 2px;
        }

        .header-meta-label {
            color: #888;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .header-meta-value {
            font-size: 9px;
            font-weight: 600;
            color: #333;
        }
        
        .report-title {
            text-align: center;
            font-size: 16px; /* Slightly reduced font size */
            font-weight: 700;
            color: #333;
            margin-top: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
            clear: both;
        }

        /* Info Blocks - Streamlined for Compactness */
        .info-block {
            border: 1px solid #eee;
            border-left: 3px solid #007BFF;
            padding: 8px; /* Reduced padding */
            margin-bottom: 10px; /* Reduced margin */
            border-radius: 3px;
            background: #fff;
        }

        .info-header {
            font-size: 10px; /* Reduced font size */
            font-weight: 700;
            color: #007BFF;
            border-bottom: 1px solid #eee;
            padding-bottom: 4px;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-row {
            overflow: auto;
            padding: 2px 0; /* Tighter vertical spacing */
        }

        .info-label {
            float: left;
            font-weight: 600;
            color: #555;
            width: 40%; /* Adjusted width */
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .info-value {
            float: left;
            width: 60%; /* Adjusted width */
            color: #333;
            font-size: 9.5px;
        }

        /* Section Title - More emphasis, less space */
        .section-title {
            font-size: 12px;
            font-weight: 700;
            color: #007BFF; /* Primary color for emphasis */
            background-color: rgba(0, 123, 255, 0.05);
            padding: 5px 10px;
            margin: 15px 0 10px 0; /* Reduced margin */
            border-left: 4px solid #007BFF;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        
        /* Table Styles - Prescription focused */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px; /* Reduced margin */
            border: 1px solid #eee;
        }

        .data-table th, .data-table td {
            padding: 8px 6px; /* Reduced padding */
            text-align: center;
            border: 1px solid #eee;
            font-size: 9.5px;
        }

        .data-table thead th {
            background-color: #007BFF; /* Solid background for strong header */
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #007BFF;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .eye-label {
            text-align: left !important;
            font-weight: 700;
            color: #007BFF;
            background-color: #f0f8ff;
        }
        
        /* New Micro-Data Table for IOP/PD */
        .micro-data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .micro-data-table th, .micro-data-table td {
            padding: 5px 5px;
            text-align: center;
            border: 1px solid #eee;
            font-size: 9px;
        }
        .micro-data-table thead th {
            background-color: #f1f1f1;
            color: #555;
            font-weight: 600;
        }
        .micro-data-table td strong {
            font-size: 9.5px;
        }


        /* Clinical Notes */
        .notes-area {
            border: 1px solid #eee;
            border-left: 4px solid #007BFF;
            padding: 10px; /* Reduced padding */
            margin-bottom: 15px; /* Reduced margin */
            border-radius: 3px;
            background: #f9f9f9;
        }

        .notes-area h4 {
            color: #333;
            font-size: 9.5px; /* Reduced font size */
            font-weight: 700;
            margin-bottom: 4px;
            padding-bottom: 3px;
            border-bottom: 1px dotted #ccc;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .notes-area p {
            font-size: 9px; /* Reduced font size */
            margin-bottom: 8px;
            color: #555;
        }
        
        .highlight-box {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid #ffc107;
            border-left: 4px solid #ffc107;
            padding: 8px 10px; /* Reduced padding */
            margin-top: 10px; /* Reduced margin */
            border-radius: 3px;
        }

        .highlight-box h4 {
            color: #d39e00;
            font-size: 9px;
            font-weight: 700;
            margin-bottom: 4px;
            border-bottom: none;
            padding-bottom: 0;
        }

        .highlight-box p {
            color: #b88a00;
            font-weight: 600;
            font-size: 10px;
            margin: 0;
        }

        /* Footer */
        .footer {
            margin-top: 20px; /* Reduced margin */
            padding-top: 10px; /* Reduced padding */
            border-top: 1px solid #eee;
            text-align: center;
            color: #888;
            font-size: 8px;
        }
        
        /* Specific adjustments for info-blocks */
        .patient-info-block .info-label {
            width: 30%;
        }
        .patient-info-block .info-value {
            width: 70%;
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
            </div>
            <div class="report-title">Eye Examination Report</div>
        </div>

        <div class="row">
            <div class="col-half">
                <div class="info-block" style="border-left-color: #17a2b8;">
                    <div class="info-header">Report Details</div>
                    <div class="info-row">
                        <div class="info-label">Report Date</div>
                        <div class="info-value"><strong>{{ $examination->exam_date->format('M d, Y') }}</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Report ID</div>
                        <div class="info-value">#{{ str_pad($examination->id, 6, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    @if(!empty($examination->old_rx_date))
                    <div class="info-row">
                        <div class="info-label">Previous RX Date</div>
                        <div class="info-value">{{ $examination->old_rx_date->format('M d, Y') }}</div>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="col-half">
                <div class="info-block" style="border-left-color: #28a745;">
                    <div class="info-header">Examiner</div>
                    <div class="info-row">
                        <div class="info-label">Doctor Name</div>
                        <div class="info-value"><strong>{{ $user->name ?? 'N/A' }}</strong></div>
                    </div>
                    @if(!empty($user->email))
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $user->email }}</div>
                    </div>
                    @endif
                    @if(!empty($examination->chief_complaint))
                    <div class="info-row">
                        <div class="info-label">Chief Complaint</div>
                        <div class="info-value">{{ $examination->chief_complaint }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="info-block patient-info-block" style="width: 100%; margin-right: 0; border-left-color: #ffc107;">
                <div class="info-header" style="color: #d39e00; border-bottom-color: #ffc107;">Patient Information</div>
                <div class="row" style="margin-bottom: 0;">
                    <div class="col-half" style="margin-bottom: 0;">
                        <div class="info-row">
                            <div class="info-label">Patient Name</div>
                            <div class="info-value"><strong>{{ $customer->name ?? 'N/A' }}</strong></div>
                        </div>
                        @if(!empty($customer->phone_number))
                        <div class="info-row">
                            <div class="info-label">Phone</div>
                            <div class="info-value">{{ $customer->phone_number }}</div>
                        </div>
                        @endif
                    </div>
                    <div class="col-half" style="margin-bottom: 0;">
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
        </div>
        
        <div class="section-title">Ophthalmic Prescription</div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Eye</th>
                    <th>Sphere (SPH)</th>
                    <th>Cylinder (CYL)</th>
                    <th>Axis (°)</th>
                    <th>Add Power (NV/INT)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="eye-label">OD (Right Eye)</td>
                    <td>{{ !empty($examination->od_sphere) ? number_format($examination->od_sphere, 2) : '-' }}</td>
                    <td>{{ !empty($examination->od_cylinder) ? number_format($examination->od_cylinder, 2) : '-' }}</td>
                    <td>{{ !empty($examination->od_axis) ? $examination->od_axis . '°' : '-' }}</td>
                    <td rowspan="2" style="vertical-align: middle; background-color: rgba(0, 123, 255, 0.08); color: #007BFF; font-weight: 700; font-size: 11px;">
                        {{ !empty($examination->add_power) ? number_format($examination->add_power, 2) : 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <td class="eye-label">OS (Left Eye)</td>
                    <td>{{ !empty($examination->os_sphere) ? number_format($examination->os_sphere, 2) : '-' }}</td>
                    <td>{{ !empty($examination->os_cylinder) ? number_format($examination->os_cylinder, 2) : '-' }}</td>
                    <td>{{ !empty($examination->os_axis) ? $examination->os_axis . '°' : '-' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="row">
            <div class="col-half">
                @if($examination->od_va_unaided || $examination->os_va_unaided || $examination->od_bcva || $examination->os_bcva)
                <div class="info-block" style="border-left-color: #5cb85c; margin-bottom: 0;">
                    <div class="info-header" style="color: #28a745; border-bottom-color: #5cb85c;">Visual Acuity</div>
                    <table class="micro-data-table">
                        <thead>
                            <tr>
                                <th>Measurement</th>
                                <th>OD</th>
                                <th>OS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($examination->od_va_unaided) || !empty($examination->os_va_unaided))
                            <tr>
                                <td class="eye-label" style="background-color: transparent;">Unaided VA</td>
                                <td><strong>{{ !empty($examination->od_va_unaided) ? $examination->od_va_unaided : '-' }}</strong></td>
                                <td><strong>{{ !empty($examination->os_va_unaided) ? $examination->os_va_unaided : '-' }}</strong></td>
                            </tr>
                            @endif
                            @if(!empty($examination->od_bcva) || !empty($examination->os_bcva))
                            <tr>
                                <td class="eye-label" style="background-color: transparent;">BCVA</td>
                                <td><strong>{{ !empty($examination->od_bcva) ? $examination->od_bcva : '-' }}</strong></td>
                                <td><strong>{{ !empty($examination->os_bcva) ? $examination->os_bcva : '-' }}</strong></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            
            <div class="col-half">
                @if(!empty($examination->pd_distance) || !empty($examination->pd_near) || !empty($examination->iop_od) || !empty($examination->iop_os))
                <div class="info-block" style="border-left-color: #dc3545; margin-bottom: 0;">
                    <div class="info-header" style="color: #dc3545; border-bottom-color: #dc3545;">Auxiliary Data</div>
                    <table class="micro-data-table">
                        <thead>
                            <tr>
                                <th>Parameter</th>
                                <th>OD</th>
                                <th>OS/Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($examination->pd_distance) || !empty($examination->pd_near))
                            <tr>
                                <td class="eye-label" style="background-color: transparent;">PD Dist/Near</td>
                                <td>{{ !empty($examination->pd_distance) ? number_format($examination->pd_distance, 1) . ' mm' : '-' }}</td>
                                <td>{{ !empty($examination->pd_near) ? number_format($examination->pd_near, 1) . ' mm' : '-' }}</td>
                            </tr>
                            @endif
                            @if(!empty($examination->iop_od) || !empty($examination->iop_os))
                            <tr>
                                <td class="eye-label" style="background-color: transparent;">IOP (mmHg)</td>
                                <td><strong>{{ !empty($examination->iop_od) ? $examination->iop_od : '-' }}</strong></td>
                                <td><strong>{{ !empty($examination->iop_os) ? $examination->iop_os : '-' }}</strong></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>


        @if(!empty($examination->diagnosis) || !empty($examination->management_plan) || !empty($examination->fundus_notes) || !empty($examination->next_recall_date))
        <div class="section-title">Clinical Summary & Follow-up</div>
        
        <div class="notes-area">
            @if(!empty($examination->diagnosis))
            <div>
                <h4>Diagnosis</h4>
                <p>{{ $examination->diagnosis }}</p>
            </div>
            @endif
            
            @if(!empty($examination->management_plan))
            <div>
                <h4>Management Plan/Recommendations</h4>
                <p>{{ $examination->management_plan }}</p>
            </div>
            @endif

            @if(!empty($examination->fundus_notes))
            <div>
                <h4>Fundus Examination Notes</h4>
                <p>{{ $examination->fundus_notes }}</p>
            </div>
            @endif
            
            @if(!empty($examination->next_recall_date))
            <div class="highlight-box">
                <h4>Recommended Follow-up</h4>
                <p>Please schedule your next comprehensive eye examination on or before: <strong>{{ $examination->next_recall_date->format('F d, Y') }}</strong></p>
            </div>
            @endif
        </div>
        @endif

        <div class="footer">
            <p><strong>Generated on:</strong> {{ now()->format('F d, Y \a\t h:i A') }} | Report authorized by: {{ $user->name ?? 'Optometrist/Ophthalmologist' }}</p>
            <p>This prescription is valid for one year from the examination date. Please contact the clinic for verification or any concerns.</p>
            <p class="footer-copyright">© {{ date('Y') }} {{ $store->name ?? 'Eyecare Clinic' }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>