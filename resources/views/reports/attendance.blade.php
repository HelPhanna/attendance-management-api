<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #1a1a2e;
            background: #ffffff;
        }

        /* ── Header ── */
        .header {
            background: #1a1a2e;
            color: #ffffff;
            padding: 20px 24px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 11px;
            opacity: 0.75;
        }

        /* ── Meta Info ── */
        .meta-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .meta-item {
            display: table-cell;
            width: 25%;
            padding: 10px 12px;
            background: #f0f4ff;
            border: 1px solid #d8e3ff;
            vertical-align: top;
        }

        .meta-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7ab0;
            margin-bottom: 4px;
        }

        .meta-value {
            font-size: 12px;
            font-weight: 600;
            color: #1a1a2e;
        }

        /* ── Table ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead tr {
            background: #1a1a2e;
            color: #ffffff;
        }

        thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        thead th.num {
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background: #f8f9ff;
        }

        tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        tbody td {
            padding: 9px 12px;
            border-bottom: 1px solid #e8ecf8;
            font-size: 11px;
            vertical-align: middle;
        }

        tbody td.num {
            text-align: center;
            font-weight: 600;
        }

        tbody td.present  { color: #15803d; }
        tbody td.absent   { color: #b91c1c; }
        tbody td.permission { color: #d97706; }

        /* ── Totals Row ── */
        tfoot tr {
            background: #1a1a2e;
            color: #ffffff;
        }

        tfoot td {
            padding: 10px 12px;
            font-size: 11px;
            font-weight: 700;
        }

        tfoot td.num {
            text-align: center;
        }

        /* ── Summary Cards ── */
        .summary {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .summary-card {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 14px 10px;
            border: 1px solid #e8ecf8;
        }

        .summary-card.present-card  { background: #f0fdf4; border-color: #86efac; }
        .summary-card.absent-card   { background: #fff1f2; border-color: #fca5a5; }
        .summary-card.perm-card     { background: #fffbeb; border-color: #fde68a; }

        .summary-number {
            font-size: 26px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 4px;
        }

        .present-card .summary-number  { color: #15803d; }
        .absent-card  .summary-number  { color: #b91c1c; }
        .perm-card    .summary-number  { color: #d97706; }

        .summary-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7c93;
        }

        /* ── Footer ── */
        .footer {
            font-size: 9px;
            color: #9aa3bf;
            text-align: center;
            border-top: 1px solid #e8ecf8;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    {{-- ── Header ── --}}
    <div class="header">
        <h1>Attendance Report</h1>
        <p>Generated on {{ now()->format('F j, Y  H:i') }}</p>
    </div>

    {{-- ── Meta ── --}}
    <div class="meta-grid">
        <div class="meta-item">
            <div class="meta-label">Period</div>
            <div class="meta-value">{{ $period['from'] }} – {{ $period['to'] }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Term</div>
            <div class="meta-value">{{ $term_name }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Class</div>
            <div class="meta-value">{{ $class_name }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Total Students</div>
            <div class="meta-value">{{ count($rows) }}</div>
        </div>
    </div>

    {{-- ── Summary Cards ── --}}
    <div class="summary">
        <div class="summary-card present-card">
            <div class="summary-number">{{ $totals['present'] }}</div>
            <div class="summary-label">&#10003; Present</div>
        </div>
        <div class="summary-card absent-card">
            <div class="summary-number">{{ $totals['absent'] }}</div>
            <div class="summary-label">&#10007; Absent</div>
        </div>
        <div class="summary-card perm-card">
            <div class="summary-number">{{ $totals['permission'] }}</div>
            <div class="summary-label">&#9998; Permission</div>
        </div>
    </div>

    {{-- ── Table ── --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Student Code</th>
                <th>Student Name</th>
                <th>Class</th>
                <th class="num">Present</th>
                <th class="num">Absent</th>
                <th class="num">Permission</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $row['student_code'] }}</td>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['class_name'] }}</td>
                <td class="num present">{{ $row['present'] }}</td>
                <td class="num absent">{{ $row['absent'] }}</td>
                <td class="num permission">{{ $row['permission'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding: 20px; color: #9aa3bf;">
                    No attendance records found.
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Grand Total</td>
                <td class="num">{{ $totals['present'] }}</td>
                <td class="num">{{ $totals['absent'] }}</td>
                <td class="num">{{ $totals['permission'] }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- ── Footer ── --}}
    <div class="footer">
        Attendance Management System &bull; This report was automatically generated.
    </div>

</body>
</html>
