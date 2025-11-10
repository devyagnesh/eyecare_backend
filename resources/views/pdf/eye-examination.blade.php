<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eye Examination Report - {{ $examination->exam_date->format('Y-m-d') }}</title>
    <style>
        /* Base and PDF Configuration */
        /* Note: @page rules are handled by mPDF configuration, margins set to 10mm in service */

        body {
            font-family: 'DejaVu Sans', sans-serif; /* Fallback for Dompdf */
            font-size: 10.5px;
            line-height: 1.6;
            color: #333;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* Color values - mPDF doesn't support CSS variables, using direct values */
        /* Primary color: #007BFF, Primary light: rgba(0, 123, 255, 0.08) */
        /* Text dark: #333, Text medium: #555, Text muted: #888 */
        /* Border light: #eee, Background subtle: #f9f9f9 */

        /* Utility Layouts for Dompdf */
        .container {
            padding: 0 15px; /* Minimal padding for the content area */
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
        
        /* Header */
        .header {
            border-bottom: 4px solid #007BFF;
            padding: 15px 0 20px 0;
            margin-bottom: 25px;
            overflow: auto; /* Clearfix for floats */
        }

        .header-logo {
            float: left;
            font-size: 24px;
            font-weight: 700;
            color: #007BFF;
        }

        .header-meta {
            float: right;
            text-align: right;
            font-size: 9px;
        }
        
        .header-meta-item {
            margin-bottom: 3px;
        }

        .header-meta-label {
            color: #888;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1px;
        }

        .header-meta-value {
            font-size: 10px;
            font-weight: 600;
            color: #333;
        }
        
        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Card-like Info Blocks */
        .info-block {
            border: 1px solid #eee;
            border-left: 4px solid #007BFF;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            background: #fff;
        }

        .info-header {
            font-size: 11px;
            font-weight: 700;
            color: #007BFF;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .info-row {
            overflow: auto;
            padding: 4px 0;
        }

        .info-label {
            float: left;
            font-weight: 600;
            color: #555;
            width: 35%;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            float: left;
            width: 65%;
            color: #333;
            font-size: 10.5px;
        }

        /* Section Title */
        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #333;
            padding: 8px 0;
            margin: 25px 0 15px 0;
            border-bottom: 2px solid #eee;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        
        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #eee;
            border-radius: 4px;
            overflow: hidden;
        }

        .data-table th, .data-table td {
            padding: 12px 10px;
            text-align: center;
            border: 1px solid #eee;
            font-size: 10px;
        }

        .data-table thead th {
            background-color: rgba(0, 123, 255, 0.08);
            color: #007BFF;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #007BFF;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .eye-label {
            text-align: left !important;
            font-weight: 700;
            color: #007BFF;
        }

        /* Clinical Notes */
        .notes-area {
            border: 1px solid #eee;
            border-left: 4px solid #007BFF;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            background: #f9f9f9;
        }

        .notes-area h4 {
            color: #333;
            font-size: 10.5px;
            font-weight: 700;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 1px dotted #eee;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .notes-area p {
            font-size: 10px;
            margin-bottom: 10px;
            color: #555;
        }
        
        .highlight-box {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid #ffc107;
            border-left: 4px solid #ffc107;
            padding: 10px 15px;
            margin-top: 15px;
            border-radius: 4px;
        }

        .highlight-box h4 {
            color: #d39e00;
            font-size: 10px;
            font-weight: 700;
            margin-bottom: 5px;
            border-bottom: none;
            padding-bottom: 0;
        }

        .highlight-box p {
            color: #b88a00;
            font-weight: 600;
            font-size: 11px;
            margin: 0;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #888;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-logo">{{ $store->name ?? 'EYECARE' }}</div>
            <div class="header-meta">
                <div class="header-meta-item">
                    <div class="header-meta-label">Report Date</div>
                    <div class="header-meta-value">{{ $examination->exam_date->format('M d, Y') }}</div>
                </div>
                <div class="header-meta-item">
                    <div class="header-meta-label">Report ID</div>
                    <div class="header-meta-value">#{{ str_pad($examination->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>
            <div class="report-title">Eye Examination Report</div>
        </div>

        <div class="row">
            <div class="col-half">
                <div class="info-block">
                    <div class="info-header">Store Information</div>
                    <div class="info-row">
                        <div class="info-label">Name</div>
                        <div class="info-value"><strong>{{ $store->name ?? 'N/A' }}</strong></div>
                    </div>
                    @if(!empty($store->phone_number))
                    <div class="info-row">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $store->phone_number }}</div>
                    </div>
                    @endif
                    @if(!empty($store->address))
                    <div class="info-row">
                        <div class="info-label">Address</div>
                        <div class="info-value">{{ $store->address }}</div>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="col-half">
                <div class="info-block">
                    <div class="info-header">Doctor Information</div>
                    <div class="info-row">
                        <div class="info-label">Name</div>
                        <div class="info-value"><strong>{{ $user->name ?? 'N/A' }}</strong></div>
                    </div>
                    @if(!empty($user->email))
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $user->email }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="info-block" style="width: 96%; margin-right: 0;">
                <div class="info-header">Patient Information</div>
                <div class="row" style="margin-bottom: 0;">
                    <div class="col-half" style="margin-bottom: 0;">
                        <div class="info-row">
                            <div class="info-label">Name</div>
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
        

        <div class="section-title">Examination Details</div>
        
        <div class="row" style="margin-bottom: 0;">
            <div class="info-block" style="width: 96%; margin-right: 0; border-left-color: #5cb85c;">
                <div class="row" style="margin-bottom: 0;">
                    <div class="col-half" style="margin-bottom: 0;">
                        <div class="info-row">
                            <div class="info-label">Examination Date</div>
                            <div class="info-value"><strong>{{ $examination->exam_date->format('F d, Y') }}</strong></div>
                        </div>
                        @if(!empty($examination->old_rx_date))
                        <div class="info-row">
                            <div class="info-label">Previous RX Date</div>
                            <div class="info-value">{{ $examination->old_rx_date->format('F d, Y') }}</div>
                        </div>
                        @endif
                    </div>
                    <div class="col-half" style="margin-bottom: 0;">
                        @if(!empty($examination->chief_complaint))
                        <div class="info-row">
                            <div class="info-label">Chief Complaint</div>
                            <div class="info-value">{{ $examination->chief_complaint }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($examination->od_va_unaided || $examination->os_va_unaided || $examination->od_bcva || $examination->os_bcva)
        <div class="section-title">Visual Acuity</div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 35%;">Measurement</th>
                    <th>OD (Right Eye)</th>
                    <th>OS (Left Eye)</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($examination->od_va_unaided) || !empty($examination->os_va_unaided))
                <tr>
                    <td class="eye-label">Unaided VA</td>
                    <td>{{ !empty($examination->od_va_unaided) ? $examination->od_va_unaided : '-' }}</td>
                    <td>{{ !empty($examination->os_va_unaided) ? $examination->os_va_unaided : '-' }}</td>
                </tr>
                @endif
                @if(!empty($examination->od_bcva) || !empty($examination->os_bcva))
                <tr>
                    <td class="eye-label">Best Corrected VA (BCVA)</td>
                    <td>{{ !empty($examination->od_bcva) ? $examination->od_bcva : '-' }}</td>
                    <td>{{ !empty($examination->os_bcva) ? $examination->os_bcva : '-' }}</td>
                </tr>
                @endif
            </tbody>
        </table>
        @endif

        <div class="section-title">Prescription</div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Eye</th>
                    <th>Sphere (SPH)</th>
                    <th>Cylinder (CYL)</th>
                    <th>Axis</th>
                    <th>Add Power</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="eye-label">OD (Right Eye)</td>
                    <td>{{ !empty($examination->od_sphere) ? number_format($examination->od_sphere, 2) : '-' }}</td>
                    <td>{{ !empty($examination->od_cylinder) ? number_format($examination->od_cylinder, 2) : '-' }}</td>
                    <td>{{ !empty($examination->od_axis) ? $examination->od_axis . '°' : '-' }}</td>
                    <td rowspan="2" style="vertical-align: middle; background-color: rgba(0, 123, 255, 0.08); color: #007BFF; font-weight: 700;">{{ !empty($examination->add_power) ? number_format($examination->add_power, 2) : '-' }}</td>
                </tr>
                <tr>
                    <td class="eye-label">OS (Left Eye)</td>
                    <td>{{ !empty($examination->os_sphere) ? number_format($examination->os_sphere, 2) : '-' }}</td>
                    <td>{{ !empty($examination->os_cylinder) ? number_format($examination->os_cylinder, 2) : '-' }}</td>
                    <td>{{ !empty($examination->os_axis) ? $examination->os_axis . '°' : '-' }}</td>
                </tr>
            </tbody>
        </table>

        @if(!empty($examination->pd_distance) || !empty($examination->pd_near) || !empty($examination->iop_od) || !empty($examination->iop_os))
        <div class="section-title">Auxiliary Data</div>
        
        <div class="row">
            <div class="col-half">
                @if(!empty($examination->pd_distance) || !empty($examination->pd_near))
                <div class="info-block" style="border-left-color: #17a2b8;">
                    <div class="info-header">Pupil Distance (PD)</div>
                    @if(!empty($examination->pd_distance))
                    <div class="info-row">
                        <div class="info-label">Distance</div>
                        <div class="info-value"><strong>{{ number_format($examination->pd_distance, 1) }} mm</strong></div>
                    </div>
                    @endif
                    @if(!empty($examination->pd_near))
                    <div class="info-row">
                        <div class="info-label">Near</div>
                        <div class="info-value"><strong>{{ number_format($examination->pd_near, 1) }} mm</strong></div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            
            <div class="col-half">
                @if(!empty($examination->iop_od) || !empty($examination->iop_os))
                <div class="info-block" style="border-left-color: #dc3545;">
                    <div class="info-header">Intraocular Pressure (IOP)</div>
                    @if(!empty($examination->iop_od))
                    <div class="info-row">
                        <div class="info-label">OD</div>
                        <div class="info-value"><strong>{{ $examination->iop_od }} mmHg</strong></div>
                    </div>
                    @endif
                    @if(!empty($examination->iop_os))
                    <div class="info-row">
                        <div class="info-label">OS</div>
                        <div class="info-value"><strong>{{ $examination->iop_os }} mmHg</strong></div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif

        @if(!empty($examination->fundus_notes) || !empty($examination->diagnosis) || !empty($examination->management_plan) || !empty($examination->next_recall_date))
        <div class="section-title">Clinical Notes & Recommendations</div>
        
        <div class="notes-area">
            @if(!empty($examination->diagnosis))
            <div>
                <h4>Diagnosis</h4>
                <p>{{ $examination->diagnosis }}</p>
            </div>
            @endif
            
            @if(!empty($examination->fundus_notes))
            <div>
                <h4>Fundus Examination Notes</h4>
                <p>{{ $examination->fundus_notes }}</p>
            </div>
            @endif
            
            @if(!empty($examination->management_plan))
            <div>
                <h4>Management Plan</h4>
                <p>{{ $examination->management_plan }}</p>
            </div>
            @endif
            
            @if(!empty($examination->next_recall_date))
            <div class="highlight-box">
                <h4>Recommended Follow-up</h4>
                <p>Next Examination Date: <strong>{{ $examination->next_recall_date->format('F d, Y') }}</strong></p>
            </div>
            @endif
        </div>
        @endif

        <div class="footer">
            <p><strong>Generated on:</strong> {{ now()->format('F d, Y \a\t h:i A') }}</p>
            <p>This is a computer-generated medical report. Please verify all information with your healthcare provider.</p>
            <p class="footer-copyright">© {{ date('Y') }} {{ $store->name ?? 'Eyecare' }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>