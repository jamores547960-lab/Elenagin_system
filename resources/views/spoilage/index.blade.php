@extends('system')

@section('title', 'Spoilage Management - TITLE')

@section('head')
    <link href="{{ asset('css/pages.css') }}" rel="stylesheet">
@endsection

@section('content')
<div style="position: relative; margin-bottom: 24px;">
    <h2 class="text-accent" style="font-size: 1.75rem; font-weight: 700; margin: 0; background: linear-gradient(135deg, #f56565, #ed8936); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
        <i class="fas fa-exclamation-triangle"></i> Spoilage Management
    </h2>
    <div style="position: absolute; top: 0; right: 0;">
        <a href="{{ route('spoilage.create') }}" 
           class="btn btn-primary"
           style="display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #f56565, #ed8936); color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; box-shadow: 0 2px 8px rgba(245, 101, 101, 0.2); transition: all 0.2s ease; text-decoration: none;"
           onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 12px rgba(245,101,101,0.3)'"
           onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(245,101,101,0.2)'">
            <i class="fas fa-plus"></i> Record Spoilage
        </a>
    </div>
</div>

<div class="glass-card glass-card-wide" style="height: calc(100vh - 250px); display: flex; flex-direction: column;">

    @if(session('success'))
        <div class="alert alert-success mb-3" style="background: linear-gradient(135deg, rgba(72, 187, 120, 0.1), rgba(72, 187, 120, 0.05)); border-left: 4px solid #48bb78; border-radius: 10px; padding: 14px 18px; color: #2f855a; font-weight: 500; border: 1px solid rgba(72, 187, 120, 0.2);">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="toolbar-modern mb-4">
        <div class="search-bar-wrapper" style="flex:1 1 400px;max-width:600px;">
            <form method="GET" action="{{ route('spoilage.index') }}" class="search-bar-modern" autocomplete="off">
                <span class="search-icon" style="color:#f56565;"><i class="fas fa-search"></i></span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       style="flex:1;border:none;outline:none;font-size:0.95rem;background:transparent;"
                       placeholder="Search by item name...">
                @if(request('search'))
                    <button type="button"
                            style="background:none;border:none;color:#f56565;cursor:pointer;padding:4px 8px;"
                            onclick="window.location='{{ route('spoilage.index') }}'">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
                <button style="background:linear-gradient(135deg,#f56565,#ed8936);color:#fff;border:none;padding:8px 20px;border-radius:8px;font-weight:600;cursor:pointer;transition:all 0.2s ease;">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>

        <form method="GET" action="{{ route('spoilage.index') }}" class="filter-group" style="display:flex;gap:10px;align-items:center;">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <select name="reason_filter" class="form-select-modern" style="min-width:200px;">
                <option value="">All Reasons</option>
                <option value="expired" @selected(request('reason_filter') == 'expired')>Expired</option>
                <option value="damaged" @selected(request('reason_filter') == 'damaged')>Damaged</option>
                <option value="contaminated" @selected(request('reason_filter') == 'contaminated')>Contaminated</option>
                <option value="other" @selected(request('reason_filter') == 'other')>Other</option>
            </select>
            <button style="background:linear-gradient(135deg,#f56565,#ed8936);color:#fff;border:none;padding:10px 20px;border-radius:8px;font-weight:600;cursor:pointer;">
                Apply Filter
            </button>
            @if(request('reason_filter'))
                <a href="{{ route('spoilage.index', array_filter(['search'=>request('search')])) }}"
                   style="background:#fff;color:#f56565;border:2px solid rgba(245,101,101,0.2);padding:10px 20px;border-radius:8px;font-weight:600;text-decoration:none;">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <div style="flex: 1; overflow-y: auto; min-height: 0;">
        <table class="table-modern" style="width: 100%;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Stock-Out ID</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Reason</th>
                    <th>Notes</th>
                    <th>Recorded By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($spoilages as $spoilage)
                    <tr>
                        <td>{{ $spoilage->stockout_date->format('M d, Y') }}</td>
                        <td>{{ $spoilage->stockout_id }}</td>
                        <td>
                            <strong>{{ $spoilage->item->name }}</strong>
                            <br><small style="color: #718096;">{{ $spoilage->item->item_id }}</small>
                        </td>
                        <td>{{ $spoilage->quantity }}</td>
                        <td style="color: #f56565; font-weight: 500;">
                            {{ ucfirst($spoilage->reason) }}
                        </td>
                        <td>
                            <small style="color: #718096;">
                                {{ $spoilage->notes ? Str::limit($spoilage->notes, 30) : 'N/A' }}
                            </small>
                        </td>
                        <td>{{ $spoilage->user->name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('spoilage.show', $spoilage->id) }}" 
                               style="color: #3182ce; text-decoration: none;">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: #a0aec0;">
                            <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 10px; opacity: 0.3;"></i>
                            <p style="margin: 0; font-size: 1.1rem;">No spoilage records found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($spoilages->hasPages())
        <div class="mt-4" style="padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.05);">
            {{ $spoilages->links() }}
        </div>
    @endif
</div>
@endsection
