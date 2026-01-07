@extends('system')

@section('title', 'Spoilage Details - TITLE')

@section('head')
    <link href="{{ asset('css/pages.css') }}" rel="stylesheet">
    <style>
        .details-card {
            background: #fff;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            max-width: 100%;
            margin: 0;
        }
        .detail-section {
            margin-bottom: 28px;
        }
        .detail-section h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 16px;
            color: #2d3748;
            border-bottom: 2px solid #f56565;
            padding-bottom: 8px;
        }
        .detail-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 24px;
            padding: 12px 0;
            border-bottom: 1px solid #f7fafc;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #4a5568;
        }
        .detail-value {
            color: #2d3748;
        }
    </style>
@endsection

@section('content')
<div class="print-hide" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 class="text-accent" style="font-size: 1.75rem; font-weight: 700; margin: 0; background: linear-gradient(135deg, #f56565, #ed8936); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            <i class="fas fa-exclamation-triangle"></i> Spoilage Details
        </h2>
        <p style="color: #718096; margin-top: 8px;">View detailed information about this spoilage record</p>
    </div>
    <a href="{{ route('spoilage.index') }}" 
       style="color: #4a5568; text-decoration: none;">
        ← Back to List
    </a>
</div>

<div class="print-title" style="display: none;">
    <h1 style="font-size: 20px; font-weight: bold; margin: 0 0 20px 0; padding-bottom: 10px; border-bottom: 2px solid #000;">
        Spoilage Record - Elenagin Management System
    </h1>
</div>

<div class="details-card">
    <div class="detail-section">
        <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
        
        <div class="detail-row">
            <div class="detail-label">Stock-Out ID:</div>
            <div class="detail-value">{{ $spoilage->stockout_id }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Date:</div>
            <div class="detail-value">{{ $spoilage->stockout_date->format('F d, Y') }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Recorded By:</div>
            <div class="detail-value">{{ $spoilage->user->name ?? 'N/A' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Created At:</div>
            <div class="detail-value">{{ $spoilage->created_at->format('F d, Y h:i A') }}</div>
        </div>
    </div>

    <div class="detail-section">
        <h3><i class="fas fa-box"></i> Item Information</h3>
        
        <div class="detail-row">
            <div class="detail-label">Item ID:</div>
            <div class="detail-value">{{ $spoilage->item->item_id }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Product Name:</div>
            <div class="detail-value"><strong>{{ $spoilage->item->name }}</strong></div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Category:</div>
            <div class="detail-value">{{ $spoilage->item->category->name ?? 'N/A' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Quantity Removed:</div>
            <div class="detail-value">{{ $spoilage->quantity }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Current Stock:</div>
            <div class="detail-value">{{ $spoilage->item->quantity }}</div>
        </div>
    </div>

    <div class="detail-section">
        <h3><i class="fas fa-clipboard-list"></i> Spoilage Details</h3>
        
        <div class="detail-row">
            <div class="detail-label">Reason:</div>
            <div class="detail-value" style="color: #f56565; font-weight: 500;">
                {{ ucfirst($spoilage->reason) }}
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Additional Notes:</div>
            <div class="detail-value">
                @if($spoilage->notes)
                    {{ $spoilage->notes }}
                @else
                    <span style="color: #a0aec0; font-style: italic;">No additional notes</span>
                @endif
            </div>
        </div>
    </div>

    <div class="print-hide" style="margin-top: 32px; padding-top: 24px; border-top: 2px solid #f7fafc; display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('spoilage.index') }}" 
           style="color: #4a5568; text-decoration: none;">
            ← Back to List
        </a>
        
        <button onclick="window.print()" 
                style="background: none; border: none; color: #667eea; cursor: pointer; font-size: 1rem;">
            Print Record
        </button>
    </div>
</div>

<style>
@media print {
    .sidebar, .header, .footer, .print-hide {
        display: none !important;
    }
    .print-title {
        display: block !important;
    }
    body {
        margin: 0 !important;
        padding: 0 !important;
    }
    .main-content {
        margin: 0 !important;
        padding: 20px !important;
    }
    .details-card {
        box-shadow: none !important;
        border: none !important;
        max-width: 100% !important;
        page-break-inside: avoid;
        padding: 0 !important;
        margin: 0 !important;
    }
    /* Ensure proper layout for print */
    .detail-section {
        page-break-inside: avoid;
        margin-bottom: 30px;
    }
    .detail-section h3 {
        font-size: 1.1rem;
        margin-bottom: 15px;
    }
    .detail-row {
        display: grid !important;
        grid-template-columns: 200px 1fr !important;
        gap: 24px !important;
        padding: 10px 0;
        border-bottom: 1px solid #e2e8f0;
    }
    .detail-label {
        font-weight: 600;
    }
    .detail-value {
        color: #000;
    }
}
</style>
@endsection
