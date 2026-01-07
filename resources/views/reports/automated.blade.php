@extends('system')

@section('title', 'Automated Reports - Elenagin System')

@section('head')
    <link href="{{ asset('css/pages.css') }}" rel="stylesheet">
    <style>
        .report-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .report-card:hover {
            border-color: #667eea;
            box-shadow: 0 6px 16px rgba(102,126,234,0.1);
            transform: translateY(-4px);
        }
        
        .report-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .report-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .report-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }
        
        .report-subtitle {
            color: #6b7280;
            font-size: 0.875rem;
            margin: 0;
        }
        
        .report-stats {
            background: #f9fafb;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        
        .stat-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
        }
        
        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .stat-value {
            font-weight: 700;
            color: #1f2937;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            width: 100%;
        }
        
        .btn-generate {
            flex: 1;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
        }
        
        .btn-generate:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
        }
        
        .btn-generate:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .btn-pdf {
            flex: 0 0 auto;
            min-width: 110px;
            padding: 0.875rem 1.25rem;
            background: #fff;
            border: 2px solid #48bb78;
            border-radius: 10px;
            color: #48bb78;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .btn-pdf:hover {
            background: #48bb78;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(72, 187, 120, 0.25);
        }
        
        .btn-pdf:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(72, 187, 120, 0.1), rgba(72, 187, 120, 0.05));
            border-left: 4px solid #48bb78;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            color: #2f855a;
            font-weight: 500;
        }
        
        .alert-error {
            background: linear-gradient(135deg, rgba(245, 101, 101, 0.1), rgba(245, 101, 101, 0.05));
            border-left: 4px solid #f56565;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            color: #c53030;
            font-weight: 500;
        }
        
        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 24px;
            margin-top: 2rem;
        }
    </style>
@endsection

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto;">
    <div style="position: relative; margin-bottom: 24px;">
        <h1 style="font-size: 2.25rem; font-weight: 800; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin: 0;">
            <i class="fas fa-robot"></i> Automated Reports
        </h1>
        <a href="{{ route('reports.index') }}" style="position: absolute; top: 0; right: 0; display: inline-flex; align-items: center; gap: 8px; background: white; border: 2px solid rgba(102, 126, 234, 0.3); color: #667eea; padding: 10px 20px; border-radius: 10px; font-weight: 600; text-decoration: none; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); transition: all 0.2s ease;">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
    </div>

    <div id="message-container"></div>

    <div class="reports-grid">
        <!-- Daily Report -->
        <div class="report-card">
            <div class="report-header">
                <div class="report-icon"><i class="fas fa-calendar-day"></i></div>
                <div>
                    <h3 class="report-title">Daily Report</h3>
                    <p class="report-subtitle">Today's sales summary</p>
                </div>
            </div>
            
            <div class="report-stats">
                <div class="stat-item">
                    <span class="stat-label">Reports Generated Today:</span>
                    <span class="stat-value">{{ $dailyReportCount }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Last Generated:</span>
                    <span class="stat-value">{{ $dailyReportCount > 0 ? 'Today' : 'Never' }}</span>
                </div>
            </div>
            
            <div class="button-group">
                <button onclick="generateReport('daily')" class="btn-generate" id="btn-daily">
                    <i class="fas fa-chart-line"></i> Generate Report
                </button>
                <button onclick="downloadPDF('daily')" class="btn-pdf" id="btn-daily-pdf">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
            </div>
        </div>

        <!-- Weekly Report -->
        <div class="report-card">
            <div class="report-header">
                <div class="report-icon"><i class="fas fa-chart-bar"></i></div>
                <div>
                    <h3 class="report-title">Weekly Report</h3>
                    <p class="report-subtitle">This week's performance</p>
                </div>
            </div>
            
            <div class="report-stats">
                <div class="stat-item">
                    <span class="stat-label">Reports Generated This Week:</span>
                    <span class="stat-value">{{ $weeklyReportCount }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Week Period:</span>
                    <span class="stat-value">{{ now()->startOfWeek()->format('M d') }} - {{ now()->endOfWeek()->format('M d') }}</span>
                </div>
            </div>
            
            <div class="button-group">
                <button onclick="generateReport('weekly')" class="btn-generate" id="btn-weekly">
                    <i class="fas fa-chart-line"></i> Generate Report
                </button>
                <button onclick="downloadPDF('weekly')" class="btn-pdf" id="btn-weekly-pdf">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
            </div>
        </div>

        <!-- Monthly Report -->
        <div class="report-card">
            <div class="report-header">
                <div class="report-icon"><i class="fas fa-chart-line"></i></div>
                <div>
                    <h3 class="report-title">Monthly Report</h3>
                    <p class="report-subtitle">This month's overview</p>
                </div>
            </div>
            
            <div class="report-stats">
                <div class="stat-item">
                    <span class="stat-label">Reports Generated This Month:</span>
                    <span class="stat-value">{{ $monthlyReportCount }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Month:</span>
                    <span class="stat-value">{{ now()->format('F Y') }}</span>
                </div>
            </div>
            
            <div class="button-group">
                <button onclick="generateReport('monthly')" class="btn-generate" id="btn-monthly">
                    <i class="fas fa-chart-line"></i> Generate Report
                </button>
                <button onclick="downloadPDF('monthly')" class="btn-pdf" id="btn-monthly-pdf">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
            </div>
        </div>
    </div>

    <div class="mt-5 p-4" style="background: #f9fafb; border-radius: 16px; border: 2px solid #e5e7eb;">
        <h4 style="color: #1f2937; font-weight: 700; margin-bottom: 1rem;">
            <i class="fas fa-info-circle" style="color: #667eea;"></i> About Automated Reports
        </h4>
        <p style="color: #6b7280; margin: 0; line-height: 1.6;">
            Automated reports provide quick insights into your business performance. Click the buttons above to generate comprehensive reports for different time periods. Each report includes sales data, transaction counts, and key performance metrics. Reports are logged in the activity system for record-keeping.
        </p>
    </div>
</div>

<script>
    function generateReport(period) {
        const btn = document.getElementById(`btn-${period}`);
        const originalText = btn.innerHTML;
        
        // Disable button and show loading
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
        
        // Send request
        fetch(`/reports/generate-${period}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(`${period.charAt(0).toUpperCase() + period.slice(1)} report generated successfully!`, 'success', data.data);
                // Reload page after 2 seconds to show updated counts
                setTimeout(() => window.location.reload(), 2000);
            } else {
                showMessage('Error generating report. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error generating report. Please try again.', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }
    
    function downloadPDF(period) {
        const btn = document.getElementById(`btn-${period}-pdf`);
        const originalText = btn.innerHTML;
        
        // Disable button and show loading
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        // Create a temporary form to POST the request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/reports/generate-${period}`;
        form.target = '_blank';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrfInput);
        
        // Add format parameter
        const formatInput = document.createElement('input');
        formatInput.type = 'hidden';
        formatInput.name = 'format';
        formatInput.value = 'pdf';
        form.appendChild(formatInput);
        
        // Submit form
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
        
        // Re-enable button after a short delay
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            showMessage(`${period.charAt(0).toUpperCase() + period.slice(1)} report opened. Use Ctrl+P to save as PDF.`, 'success');
        }, 1000);
    }
    
    function showMessage(message, type, data = null) {
        const container = document.getElementById('message-container');
        const alert = document.createElement('div');
        alert.className = `alert-${type}`;
        
        let content = `<strong>${message}</strong>`;
        if (data) {
            content += `<div style="margin-top: 0.75rem; font-size: 0.875rem;">`;
            for (const [key, value] of Object.entries(data)) {
                content += `<div><strong>${key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}:</strong> ${value}</div>`;
            }
            content += `</div>`;
        }
        
        alert.innerHTML = content;
        container.innerHTML = '';
        container.appendChild(alert);
        
        // Auto-remove after 5 seconds
        setTimeout(() => alert.remove(), 5000);
    }
</script>
@endsection
