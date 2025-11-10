<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eye Examination Report - {{ $examination->exam_date->format('Y-m-d') }}</title>
    <style>
        @page {
            margin: 0.8cm;
            size: A4;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Inter', Arial, Helvetica, sans-serif;
            font-size: 10px;
            line-height: 1.6;
            color: #333335;
            background: #ffffff;
        }
        
        /* Theme Colors */
        :root {
            --primary-color: rgb(132, 90, 223);
            --primary-light: rgba(132, 90, 223, 0.1);
            --primary-dark: rgb(107, 73, 181);
            --secondary-color: rgb(35, 183, 229);
            --secondary-light: rgba(35, 183, 229, 0.1);
            --text-primary: #333335;
            --text-secondary: #536485;
            --text-muted: #8c9097;
            --border-color: #f3f3f3;
            --bg-light: #f7f8f9;
            --bg-card: #ffffff;
            --success-color: rgb(38, 191, 148);
            --warning-color: rgb(245, 184, 73);
            --danger-color: rgb(230, 83, 60);
        }
        
        /* Header Section */
        .header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 30px 35px;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(132, 90, 223, 0.2);
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .header-logo {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        .header-meta {
            text-align: right;
            font-size: 9px;
            opacity: 0.95;
            line-height: 1.8;
        }
        
        .header-meta-item {
            margin-bottom: 4px;
        }
        
        .header-meta-label {
            font-weight: 600;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 8px;
        }
        
        .header-meta-value {
            font-size: 10px;
            margin-top: 2px;
        }
        
        .header-title {
            text-align: center;
            font-size: 22px;
            font-weight: 600;
            margin-top: 15px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .header-subtitle {
            text-align: center;
            font-size: 12px;
            margin-top: 8px;
            opacity: 0.95;
            font-weight: 400;
        }
        
        /* Two Column Layout */
        .two-column {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-spacing: 15px 0;
        }
        
        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        /* Info Cards */
        .info-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 0;
            margin-bottom: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }
        
        .info-card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 12px 18px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 2px solid var(--primary-dark);
        }
        
        .info-card-body {
            padding: 18px;
        }
        
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }
        
        .info-label {
            display: table-cell;
            font-weight: 600;
            color: var(--text-secondary);
            width: 35%;
            padding: 8px 0;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            vertical-align: top;
        }
        
        .info-value {
            display: table-cell;
            color: var(--text-primary);
            padding: 8px 0 8px 15px;
            font-size: 10px;
            border-left: 2px solid var(--border-color);
            padding-left: 15px;
            vertical-align: top;
        }
        
        /* Section Titles */
        .section-title {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 14px 20px;
            font-size: 12px;
            font-weight: 600;
            margin: 30px 0 20px 0;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 2px 8px rgba(132, 90, 223, 0.15);
            position: relative;
        }
        
        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* Prescription Table */
        .prescription-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            background: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }
        
        .prescription-table thead {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }
        
        .prescription-table th {
            color: white;
            padding: 14px 10px;
            text-align: center;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-right: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .prescription-table th:last-child {
            border-right: none;
        }
        
        .prescription-table td {
            padding: 14px 10px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            font-size: 10px;
            color: var(--text-primary);
        }
        
        .prescription-table td:last-child {
            border-right: none;
        }
        
        .prescription-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .prescription-table tbody tr:nth-child(even) {
            background-color: var(--bg-light);
        }
        
        .eye-label {
            font-weight: 700;
            color: var(--primary-color);
            text-align: left !important;
            padding-left: 18px !important;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Visual Acuity Grid */
        .va-grid {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 25px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }
        
        .va-row {
            display: table-row;
        }
        
        .va-cell {
            display: table-cell;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            text-align: center;
            vertical-align: middle;
        }
        
        .va-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-right: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .va-header:last-child {
            border-right: none;
        }
        
        .va-label {
            background: var(--bg-light);
            font-weight: 600;
            color: var(--text-secondary);
            text-align: left;
            width: 35%;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .va-value {
            background: white;
            color: var(--text-primary);
            font-size: 10px;
            font-weight: 500;
        }
        
        .va-row:nth-child(even) .va-value {
            background-color: var(--bg-light);
        }
        
        /* Notes Section */
        .notes-section {
            background: var(--bg-light);
            border-left: 4px solid var(--primary-color);
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 0 6px 6px 0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }
        
        .notes-section > div {
            margin-bottom: 18px;
        }
        
        .notes-section > div:last-child {
            margin-bottom: 0;
        }
        
        .notes-section h4 {
            color: var(--primary-color);
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .notes-section p {
            color: var(--text-primary);
            font-size: 10px;
            line-height: 1.7;
            margin: 0;
        }
        
        /* Footer */
        .footer {
            margin-top: 50px;
            padding-top: 25px;
            border-top: 2px solid var(--border-color);
            text-align: center;
            color: var(--text-muted);
            font-size: 9px;
            line-height: 1.8;
        }
        
        .footer p {
            margin-bottom: 6px;
        }
        
        .footer-copyright {
            margin-top: 12px;
            font-size: 8px;
            color: var(--text-muted);
            opacity: 0.8;
        }
        
        /* Utility Classes */
        .empty-value {
            color: var(--text-muted);
            font-style: italic;
        }
        
        .highlight-box {
            background: rgba(245, 184, 73, 0.1);
            border: 1px solid var(--warning-color);
            border-left: 4px solid var(--warning-color);
            border-radius: 4px;
            padding: 14px 18px;
            margin: 15px 0;
        }
        
        .highlight-box h4 {
            color: rgb(184, 138, 55);
            font-size: 10px;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .highlight-box p {
            color: rgb(120, 90, 40);
            font-weight: 600;
            font-size: 11px;
            margin: 0;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 10px;
            background: var(--primary-color);
            color: white;
            border-radius: 4px;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .divider {
            height: 1px;
            background: var(--border-color);
            margin: 20px 0;
        }
        
        /* Print Optimizations */
        @media print {
            body {
                font-size: 9px;
            }
            
            .header {
                page-break-inside: avoid;
                margin-bottom: 20px;
            }
            
            .info-card {
                page-break-inside: avoid;
            }
            
            .prescription-table {
                page-break-inside: avoid;
            }
            
            .section-title {
                page-break-after: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-top">
            <div class="header-logo">EYECARE</div>
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
        </div>
        <div class="header-title">Eye Examination Report</div>
        <div class="header-subtitle">{{ $store->name ?? 'Eyecare' }}</div>
    </div>

    <!-- Store and Doctor Information - Two Column -->
    <div class="two-column">
        <div class="column">
            <div class="info-card">
                <div class="info-card-header">Store Information</div>
                <div class="info-card-body">
                    <div class="info-row">
                        <div class="info-label">Name</div>
                        <div class="info-value">{{ $store->name ?? 'N/A' }}</div>
                    </div>
                    @if(!empty($store->phone_number))
                    <div class="info-row">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $store->phone_number }}</div>
                    </div>
                    @endif
                    @if(!empty($store->email))
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $store->email }}</div>
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
        </div>
        
        <div class="column">
            <div class="info-card">
                <div class="info-card-header">Doctor Information</div>
                <div class="info-card-body">
                    <div class="info-row">
                        <div class="info-label">Name</div>
                        <div class="info-value">{{ $user->name ?? 'N/A' }}</div>
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
    </div>

    <!-- Patient Information -->
    <div class="info-card">
        <div class="info-card-header">Patient Information</div>
        <div class="info-card-body">
            <div class="two-column" style="border-spacing: 0; margin: 0;">
                <div class="column">
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
                <div class="column">
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

    <!-- Examination Details -->
    <div class="section-title">Examination Details</div>
    
    <div class="info-card">
        <div class="info-card-body">
            <div class="two-column" style="border-spacing: 0; margin: 0;">
                <div class="column">
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
                <div class="column">
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

    <!-- Visual Acuity -->
    @if($examination->od_va_unaided || $examination->os_va_unaided || $examination->od_bcva || $examination->os_bcva)
    <div class="section-title">Visual Acuity</div>
    
    <table class="va-grid">
        <thead>
            <tr class="va-row">
                <th class="va-cell va-header" style="width: 35%;">Measurement</th>
                <th class="va-cell va-header">OD (Right Eye)</th>
                <th class="va-cell va-header">OS (Left Eye)</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($examination->od_va_unaided) || !empty($examination->os_va_unaided))
            <tr class="va-row">
                <td class="va-cell va-label">Unaided VA</td>
                <td class="va-cell va-value">{{ !empty($examination->od_va_unaided) ? $examination->od_va_unaided : '-' }}</td>
                <td class="va-cell va-value">{{ !empty($examination->os_va_unaided) ? $examination->os_va_unaided : '-' }}</td>
            </tr>
            @endif
            @if(!empty($examination->od_bcva) || !empty($examination->os_bcva))
            <tr class="va-row">
                <td class="va-cell va-label">Best Corrected VA</td>
                <td class="va-cell va-value">{{ !empty($examination->od_bcva) ? $examination->od_bcva : '-' }}</td>
                <td class="va-cell va-value">{{ !empty($examination->os_bcva) ? $examination->os_bcva : '-' }}</td>
            </tr>
            @endif
        </tbody>
    </table>
    @endif

    <!-- Prescription -->
    <div class="section-title">Prescription</div>
    
    <table class="prescription-table">
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
                <td class="eye-label"><strong>OD (Right Eye)</strong></td>
                <td>{{ !empty($examination->od_sphere) ? number_format($examination->od_sphere, 2) : '-' }}</td>
                <td>{{ !empty($examination->od_cylinder) ? number_format($examination->od_cylinder, 2) : '-' }}</td>
                <td>{{ !empty($examination->od_axis) ? $examination->od_axis . '°' : '-' }}</td>
                <td rowspan="2" style="vertical-align: middle;">{{ !empty($examination->add_power) ? number_format($examination->add_power, 2) : '-' }}</td>
            </tr>
            <tr>
                <td class="eye-label"><strong>OS (Left Eye)</strong></td>
                <td>{{ !empty($examination->os_sphere) ? number_format($examination->os_sphere, 2) : '-' }}</td>
                <td>{{ !empty($examination->os_cylinder) ? number_format($examination->os_cylinder, 2) : '-' }}</td>
                <td>{{ !empty($examination->os_axis) ? $examination->os_axis . '°' : '-' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Pupil Distance and IOP - Two Column -->
    @if(!empty($examination->pd_distance) || !empty($examination->pd_near) || !empty($examination->iop_od) || !empty($examination->iop_os))
    <div class="two-column">
        <div class="column">
            @if(!empty($examination->pd_distance) || !empty($examination->pd_near))
            <div class="info-card">
                <div class="info-card-header">Pupil Distance (PD)</div>
                <div class="info-card-body">
                    @if(!empty($examination->pd_distance))
                    <div class="info-row">
                        <div class="info-label">Distance</div>
                        <div class="info-value"><strong>{{ number_format($examination->pd_distance, 2) }} mm</strong></div>
                    </div>
                    @endif
                    @if(!empty($examination->pd_near))
                    <div class="info-row">
                        <div class="info-label">Near</div>
                        <div class="info-value"><strong>{{ number_format($examination->pd_near, 2) }} mm</strong></div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
        
        <div class="column">
            @if(!empty($examination->iop_od) || !empty($examination->iop_os))
            <div class="info-card">
                <div class="info-card-header">Intraocular Pressure (IOP)</div>
                <div class="info-card-body">
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
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Additional Notes -->
    @if(!empty($examination->fundus_notes) || !empty($examination->diagnosis) || !empty($examination->management_plan) || !empty($examination->next_recall_date))
    <div class="section-title">Clinical Notes & Recommendations</div>
    
    <div class="notes-section">
        @if(!empty($examination->diagnosis))
        <div>
            <h4>Diagnosis</h4>
            <p>{{ $examination->diagnosis }}</p>
        </div>
        @endif
        
        @if(!empty($examination->fundus_notes))
        <div>
            <h4>Fundus Examination</h4>
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
            <h4>Next Recall Date</h4>
            <p>{{ $examination->next_recall_date->format('F d, Y') }}</p>
        </div>
        @endif
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>Generated on:</strong> {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>This is a computer-generated medical report. Please verify all information with your healthcare provider.</p>
        <p class="footer-copyright">© {{ date('Y') }} {{ $store->name ?? 'Eyecare' }}. All rights reserved.</p>
    </div>
</body>
</html>
