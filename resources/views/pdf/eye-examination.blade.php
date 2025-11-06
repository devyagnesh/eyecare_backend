<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eye Examination Report - {{ $examination->exam_date->format('Y-m-d') }}</title>
    <style>
        @page {
            margin: 0.5cm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #1a1a1a;
            background: #ffffff;
        }
        
        /* Header Section */
        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 25px 30px;
            margin-bottom: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .header-logo {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        
        .header-date {
            font-size: 11px;
            opacity: 0.9;
            text-align: right;
        }
        
        .header-title {
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .header-subtitle {
            text-align: center;
            font-size: 14px;
            margin-top: 5px;
            opacity: 0.95;
        }
        
        /* Two Column Layout */
        .two-column {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 10px;
        }
        
        .column:first-child {
            padding-left: 0;
        }
        
        .column:last-child {
            padding-right: 0;
        }
        
        /* Info Cards */
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .info-card-header {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 8px 12px;
            margin: -15px -15px 12px -15px;
            border-radius: 6px 6px 0 0;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }
        
        .info-label {
            display: table-cell;
            font-weight: 600;
            color: #475569;
            width: 40%;
            padding: 6px 8px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .info-value {
            display: table-cell;
            color: #1e293b;
            padding: 6px 8px;
            font-size: 11px;
            border-left: 2px solid #e2e8f0;
            padding-left: 12px;
        }
        
        /* Section Titles */
        .section-title {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 12px 18px;
            font-size: 13px;
            font-weight: 600;
            margin: 25px 0 15px 0;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* Prescription Table */
        .prescription-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: 6px;
            overflow: hidden;
        }
        
        .prescription-table thead {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }
        
        .prescription-table th {
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .prescription-table th:last-child {
            border-right: none;
        }
        
        .prescription-table td {
            padding: 12px 8px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            font-size: 11px;
        }
        
        .prescription-table td:last-child {
            border-right: none;
        }
        
        .prescription-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .prescription-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .prescription-table tbody tr:hover {
            background-color: #f1f5f9;
        }
        
        .eye-label {
            font-weight: 600;
            color: #1e40af;
            text-align: left !important;
            padding-left: 15px !important;
        }
        
        /* Visual Acuity Grid */
        .va-grid {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 20px;
        }
        
        .va-row {
            display: table-row;
        }
        
        .va-cell {
            display: table-cell;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        
        .va-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .va-label {
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
            text-align: left;
            width: 30%;
        }
        
        .va-value {
            background: white;
            color: #1e293b;
        }
        
        /* Notes Section */
        .notes-section {
            background: #f8fafc;
            border-left: 4px solid #3b82f6;
            padding: 18px;
            margin-bottom: 20px;
            border-radius: 0 6px 6px 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .notes-section h4 {
            color: #1e40af;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .notes-section p {
            color: #334155;
            font-size: 11px;
            line-height: 1.6;
            margin-bottom: 12px;
        }
        
        .notes-section p:last-child {
            margin-bottom: 0;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 9px;
            line-height: 1.8;
        }
        
        .footer p {
            margin-bottom: 4px;
        }
        
        /* Utility Classes */
        .empty-value {
            color: #94a3b8;
            font-style: italic;
        }
        
        .highlight-box {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 4px;
            padding: 10px;
            margin: 10px 0;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            background: #3b82f6;
            color: white;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        /* Print Optimizations */
        @media print {
            body {
                font-size: 10px;
            }
            
            .header {
                page-break-inside: avoid;
            }
            
            .info-card {
                page-break-inside: avoid;
            }
            
            .prescription-table {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-top">
            <div class="header-logo">EYECARE</div>
            <div class="header-date">
                <div>Report Date: {{ $examination->exam_date->format('M d, Y') }}</div>
                <div>Report ID: #{{ str_pad($examination->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>
        <div class="header-title">Eye Examination Report</div>
        <div class="header-subtitle">{{ $store->name }}</div>
    </div>

    <!-- Store and Doctor Information - Two Column -->
    <div class="two-column">
        <div class="column">
            <div class="info-card">
                <div class="info-card-header">📍 Store Information</div>
                <div class="info-row">
                    <div class="info-label">Name:</div>
                    <div class="info-value">{{ $store->name }}</div>
                </div>
                @if($store->phone_number)
                <div class="info-row">
                    <div class="info-label">Phone:</div>
                    <div class="info-value">{{ $store->phone_number }}</div>
                </div>
                @endif
                @if($store->email)
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $store->email }}</div>
                </div>
                @endif
                @if($store->address)
                <div class="info-row">
                    <div class="info-label">Address:</div>
                    <div class="info-value">{{ $store->address }}</div>
                </div>
                @endif
            </div>
        </div>
        
        <div class="column">
            <div class="info-card">
                <div class="info-card-header">👨‍⚕️ Doctor Information</div>
                <div class="info-row">
                    <div class="info-label">Name:</div>
                    <div class="info-value">{{ $user->name }}</div>
                </div>
                @if($user->email)
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Patient Information -->
    <div class="info-card">
        <div class="info-card-header">👤 Patient Information</div>
        <div class="two-column">
            <div class="column">
                <div class="info-row">
                    <div class="info-label">Name:</div>
                    <div class="info-value"><strong>{{ $customer->name }}</strong></div>
                </div>
                @if($customer->phone_number)
                <div class="info-row">
                    <div class="info-label">Phone:</div>
                    <div class="info-value">{{ $customer->phone_number }}</div>
                </div>
                @endif
            </div>
            <div class="column">
                @if($customer->email)
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $customer->email }}</div>
                </div>
                @endif
                @if($customer->address)
                <div class="info-row">
                    <div class="info-label">Address:</div>
                    <div class="info-value">{{ $customer->address }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Examination Details -->
    <div class="section-title">📋 Examination Details</div>
    
    <div class="info-card">
        <div class="two-column">
            <div class="column">
                <div class="info-row">
                    <div class="info-label">Examination Date:</div>
                    <div class="info-value"><strong>{{ $examination->exam_date->format('F d, Y') }}</strong></div>
                </div>
                @if($examination->old_rx_date)
                <div class="info-row">
                    <div class="info-label">Previous RX Date:</div>
                    <div class="info-value">{{ $examination->old_rx_date->format('F d, Y') }}</div>
                </div>
                @endif
            </div>
            <div class="column">
                @if($examination->chief_complaint)
                <div class="info-row">
                    <div class="info-label">Chief Complaint:</div>
                    <div class="info-value">{{ $examination->chief_complaint }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Visual Acuity -->
    @if($examination->od_va_unaided || $examination->os_va_unaided || $examination->od_bcva || $examination->os_bcva)
    <div class="section-title">👁️ Visual Acuity</div>
    
    <table class="va-grid" style="display: table; width: 100%;">
        <thead>
            <tr class="va-row">
                <th class="va-cell va-header" style="width: 30%;">Measurement</th>
                <th class="va-cell va-header">OD (Right Eye)</th>
                <th class="va-cell va-header">OS (Left Eye)</th>
            </tr>
        </thead>
        <tbody>
            @if($examination->od_va_unaided || $examination->os_va_unaided)
            <tr class="va-row">
                <td class="va-cell va-label">Unaided VA</td>
                <td class="va-cell va-value">{{ $examination->od_va_unaided ?? '-' }}</td>
                <td class="va-cell va-value">{{ $examination->os_va_unaided ?? '-' }}</td>
            </tr>
            @endif
            @if($examination->od_bcva || $examination->os_bcva)
            <tr class="va-row">
                <td class="va-cell va-label">Best Corrected VA</td>
                <td class="va-cell va-value">{{ $examination->od_bcva ?? '-' }}</td>
                <td class="va-cell va-value">{{ $examination->os_bcva ?? '-' }}</td>
            </tr>
            @endif
        </tbody>
    </table>
    @endif

    <!-- Prescription -->
    <div class="section-title">💊 Prescription</div>
    
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
                <td>{{ $examination->od_sphere ? number_format($examination->od_sphere, 2) : '-' }}</td>
                <td>{{ $examination->od_cylinder ? number_format($examination->od_cylinder, 2) : '-' }}</td>
                <td>{{ $examination->od_axis ?? '-' }}°</td>
                <td rowspan="2" style="vertical-align: middle;">{{ $examination->add_power ? number_format($examination->add_power, 2) : '-' }}</td>
            </tr>
            <tr>
                <td class="eye-label"><strong>OS (Left Eye)</strong></td>
                <td>{{ $examination->os_sphere ? number_format($examination->os_sphere, 2) : '-' }}</td>
                <td>{{ $examination->os_cylinder ? number_format($examination->os_cylinder, 2) : '-' }}</td>
                <td>{{ $examination->os_axis ?? '-' }}°</td>
            </tr>
        </tbody>
    </table>

    <!-- Pupil Distance and IOP - Two Column -->
    @if($examination->pd_distance || $examination->pd_near || $examination->iop_od || $examination->iop_os)
    <div class="two-column">
        <div class="column">
            @if($examination->pd_distance || $examination->pd_near)
            <div class="info-card">
                <div class="info-card-header">📏 Pupil Distance (PD)</div>
                @if($examination->pd_distance)
                <div class="info-row">
                    <div class="info-label">Distance:</div>
                    <div class="info-value"><strong>{{ number_format($examination->pd_distance, 2) }} mm</strong></div>
                </div>
                @endif
                @if($examination->pd_near)
                <div class="info-row">
                    <div class="info-label">Near:</div>
                    <div class="info-value"><strong>{{ number_format($examination->pd_near, 2) }} mm</strong></div>
                </div>
                @endif
            </div>
            @endif
        </div>
        
        <div class="column">
            @if($examination->iop_od || $examination->iop_os)
            <div class="info-card">
                <div class="info-card-header">🔬 Intraocular Pressure (IOP)</div>
                @if($examination->iop_od)
                <div class="info-row">
                    <div class="info-label">OD:</div>
                    <div class="info-value"><strong>{{ $examination->iop_od }} mmHg</strong></div>
                </div>
                @endif
                @if($examination->iop_os)
                <div class="info-row">
                    <div class="info-label">OS:</div>
                    <div class="info-value"><strong>{{ $examination->iop_os }} mmHg</strong></div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Additional Notes -->
    @if($examination->fundus_notes || $examination->diagnosis || $examination->management_plan || $examination->next_recall_date)
    <div class="section-title">📝 Clinical Notes & Recommendations</div>
    
    <div class="notes-section">
        @if($examination->diagnosis)
        <div>
            <h4>🔍 Diagnosis</h4>
            <p>{{ $examination->diagnosis }}</p>
        </div>
        @endif
        
        @if($examination->fundus_notes)
        <div>
            <h4>👁️ Fundus Examination</h4>
            <p>{{ $examination->fundus_notes }}</p>
        </div>
        @endif
        
        @if($examination->management_plan)
        <div>
            <h4>💡 Management Plan</h4>
            <p>{{ $examination->management_plan }}</p>
        </div>
        @endif
        
        @if($examination->next_recall_date)
        <div class="highlight-box">
            <h4 style="color: #92400e; margin-bottom: 8px;">📅 Next Recall Date</h4>
            <p style="color: #78350f; font-weight: 600; font-size: 12px; margin: 0;">
                {{ $examination->next_recall_date->format('F d, Y') }}
            </p>
        </div>
        @endif
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>Generated on:</strong> {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>This is a computer-generated medical report. Please verify all information with your healthcare provider.</p>
        <p style="margin-top: 8px; font-size: 8px; color: #94a3b8;">© {{ date('Y') }} {{ $store->name }}. All rights reserved.</p>
    </div>
</body>
</html>
