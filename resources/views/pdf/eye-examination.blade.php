<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eye Examination Report - {{ $examination->exam_date->format('Y-m-d') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #2563eb;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .header h2 {
            color: #1e40af;
            font-size: 18px;
            font-weight: normal;
        }
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .info-section h3 {
            background-color: #2563eb;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            margin-bottom: 12px;
            border-radius: 4px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 8px 12px;
            width: 35%;
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
        }
        
        .info-value {
            display: table-cell;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
        }
        
        .section-title {
            background-color: #2563eb;
            color: white;
            padding: 10px 15px;
            font-size: 16px;
            margin: 20px 0 15px 0;
            border-radius: 4px;
        }
        
        .prescription-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .prescription-table th {
            background-color: #1e40af;
            color: white;
            padding: 10px;
            text-align: center;
            border: 1px solid #1e3a8a;
        }
        
        .prescription-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }
        
        .prescription-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .notes-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9fafb;
            border-left: 4px solid #2563eb;
        }
        
        .notes-section h4 {
            color: #1e40af;
            margin-bottom: 10px;
        }
        
        .notes-section p {
            margin-bottom: 8px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
        
        .empty-value {
            color: #9ca3af;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>EYE EXAMINATION REPORT</h1>
        <h2>{{ $store->name }}</h2>
    </div>

    <!-- Store Information -->
    <div class="info-section">
        <h3>Store Information</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Store Name:</div>
                <div class="info-value">{{ $store->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone Number:</div>
                <div class="info-value">{{ $store->phone_number }}</div>
            </div>
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

    <!-- User Information -->
    <div class="info-section">
        <h3>Doctor Information</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Doctor Name:</div>
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

    <!-- Patient Information -->
    <div class="info-section">
        <h3>Patient Information</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Patient Name:</div>
                <div class="info-value">{{ $customer->name }}</div>
            </div>
            @if($customer->email)
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $customer->email }}</div>
            </div>
            @endif
            @if($customer->phone_number)
            <div class="info-row">
                <div class="info-label">Phone Number:</div>
                <div class="info-value">{{ $customer->phone_number }}</div>
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

    <!-- Examination Details -->
    <div class="section-title">Examination Details</div>
    
    <div class="info-section">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Examination Date:</div>
                <div class="info-value">{{ $examination->exam_date->format('F d, Y') }}</div>
            </div>
            @if($examination->old_rx_date)
            <div class="info-row">
                <div class="info-label">Previous RX Date:</div>
                <div class="info-value">{{ $examination->old_rx_date->format('F d, Y') }}</div>
            </div>
            @endif
            @if($examination->chief_complaint)
            <div class="info-row">
                <div class="info-label">Chief Complaint:</div>
                <div class="info-value">{{ $examination->chief_complaint }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Visual Acuity -->
    <div class="section-title">Visual Acuity</div>
    
    <div class="info-section">
        <div class="info-grid">
            @if($examination->od_va_unaided)
            <div class="info-row">
                <div class="info-label">OD VA (Unaided):</div>
                <div class="info-value">{{ $examination->od_va_unaided }}</div>
            </div>
            @endif
            @if($examination->os_va_unaided)
            <div class="info-row">
                <div class="info-label">OS VA (Unaided):</div>
                <div class="info-value">{{ $examination->os_va_unaided }}</div>
            </div>
            @endif
            @if($examination->od_bcva)
            <div class="info-row">
                <div class="info-label">OD BCVA:</div>
                <div class="info-value">{{ $examination->od_bcva }}</div>
            </div>
            @endif
            @if($examination->os_bcva)
            <div class="info-row">
                <div class="info-label">OS BCVA:</div>
                <div class="info-value">{{ $examination->os_bcva }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Prescription -->
    <div class="section-title">Prescription</div>
    
    <table class="prescription-table">
        <thead>
            <tr>
                <th>Eye</th>
                <th>Sphere</th>
                <th>Cylinder</th>
                <th>Axis</th>
                <th>Add Power</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>OD (Right Eye)</strong></td>
                <td>{{ $examination->od_sphere ?? '-' }}</td>
                <td>{{ $examination->od_cylinder ?? '-' }}</td>
                <td>{{ $examination->od_axis ?? '-' }}</td>
                <td rowspan="2">{{ $examination->add_power ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>OS (Left Eye)</strong></td>
                <td>{{ $examination->os_sphere ?? '-' }}</td>
                <td>{{ $examination->os_cylinder ?? '-' }}</td>
                <td>{{ $examination->os_axis ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Pupil Distance -->
    <div class="info-section">
        <div class="info-grid">
            @if($examination->pd_distance)
            <div class="info-row">
                <div class="info-label">PD Distance:</div>
                <div class="info-value">{{ $examination->pd_distance }} mm</div>
            </div>
            @endif
            @if($examination->pd_near)
            <div class="info-row">
                <div class="info-label">PD Near:</div>
                <div class="info-value">{{ $examination->pd_near }} mm</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Intraocular Pressure -->
    @if($examination->iop_od || $examination->iop_os)
    <div class="section-title">Intraocular Pressure (IOP)</div>
    
    <div class="info-section">
        <div class="info-grid">
            @if($examination->iop_od)
            <div class="info-row">
                <div class="info-label">OD IOP:</div>
                <div class="info-value">{{ $examination->iop_od }} mmHg</div>
            </div>
            @endif
            @if($examination->iop_os)
            <div class="info-row">
                <div class="info-label">OS IOP:</div>
                <div class="info-value">{{ $examination->iop_os }} mmHg</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Additional Notes -->
    @if($examination->fundus_notes || $examination->diagnosis || $examination->management_plan)
    <div class="section-title">Additional Notes</div>
    
    <div class="notes-section">
        @if($examination->fundus_notes)
        <div>
            <h4>Fundus Notes:</h4>
            <p>{{ $examination->fundus_notes }}</p>
        </div>
        @endif
        
        @if($examination->diagnosis)
        <div>
            <h4>Diagnosis:</h4>
            <p>{{ $examination->diagnosis }}</p>
        </div>
        @endif
        
        @if($examination->management_plan)
        <div>
            <h4>Management Plan:</h4>
            <p>{{ $examination->management_plan }}</p>
        </div>
        @endif
        
        @if($examination->next_recall_date)
        <div>
            <h4>Next Recall Date:</h4>
            <p>{{ $examination->next_recall_date->format('F d, Y') }}</p>
        </div>
        @endif
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>This is a computer-generated report. Please verify all information.</p>
    </div>
</body>
</html>

