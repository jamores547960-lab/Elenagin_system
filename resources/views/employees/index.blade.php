@extends('system')

@section('title', 'Employees - TITLE')

@section('head')
    <link href="{{ asset('css/pages.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-accent" style="font-size: 1.75rem; font-weight: 700; margin: 0; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
        <i class="fas fa-users"></i> EMPLOYEES
    </h2>

</div>


<div class="glass-card glass-card-wide" style="height: calc(100vh - 250px); display: flex; flex-direction: column;">

    @if(session('success'))
        <div class="alert alert-success mb-3" style="background: linear-gradient(135deg, rgba(72, 187, 120, 0.1), rgba(72, 187, 120, 0.05)); border-left: 4px solid #48bb78; border-radius: 10px; padding: 14px 18px; color: #2f855a; font-weight: 500;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-3" style="background: linear-gradient(135deg, rgba(245, 101, 101, 0.1), rgba(245, 101, 101, 0.05)); border-left: 4px solid #f56565; border-radius: 10px; padding: 14px 18px; color: #c53030; font-weight: 500;">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="toolbar-modern mb-4">

        <div class="search-bar-wrapper" style="flex: 1; max-width: 600px;">
            <form action="{{ route('employees.index') }}" method="GET" class="search-bar-modern" autocomplete="off">
                <span class="search-icon" style="color: #667eea;">
                    <i class="fas fa-search"></i>
                </span>
                <input
                    id="employeeSearch"
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search employee name, email, contact or SSS..."
                    style="flex: 1; border: none; outline: none; font-size: 0.95rem; background: transparent;"
                    aria-label="Search employees">
                @if(request('search'))
                    <button type="button"
                            style="background: none; border: none; color: #667eea; cursor: pointer; padding: 4px 8px; transition: all 0.2s;"
                            title="Clear search"
                            onclick="window.location='{{ route('employees.index') }}'">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
                <button type="submit" style="background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; border: none; padding: 8px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>

            <div class="search-meta" style="margin-top: 12px; display: flex; align-items: center; gap: 12px; font-size: 0.85rem; color: #718096;">
                @php
                    $total = method_exists($employees,'total') ? $employees->total() : $employees->count();
                @endphp
                <span style="font-weight: 500;">
                    <i class="fas fa-list"></i> {{ $total }} {{ \Illuminate\Support\Str::plural('result', $total) }}
                    @if(request('search'))
                        for <strong style="color: #667eea;">"{{ e(request('search')) }}"</strong>
                    @endif
                </span>
                @if(request('search'))
                    <span class="badge-modern badge-info" style="font-size: 0.7rem;">
                        <i class="fas fa-filter"></i> Filter active
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="table-responsive" style="flex: 1; overflow-y: auto; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
        <table class="table table-modern align-middle" style="margin: 0;">
            <thead>
                <tr>
                    <th style="width:70px;"><i class="fas fa-user-circle"></i></th>
                    <th><i class="fas fa-id-badge"></i> Employee Name</th>
                    <th><i class="fas fa-envelope"></i> Email</th>
                    <th><i class="fas fa-phone"></i> Contact Number</th>
                    <th><i class="fas fa-award"></i> Role</th>
                    <th style="width:130px; text-align: center;"><i class="fas fa-cog"></i> Actions</th>
                </tr>
            </thead>
            <tbody>

            @forelse($employees as $employee)
                @php
                    $email = $employee->user->email ?? '—';
                    $role  = $employee->user->role ?? '—';
                    $profile = $employee->profile_picture
                        ? 'storage/'.$employee->profile_picture
                        : 'images/TCEmployeeProfile.png';
                @endphp

                <tr>
                    <td>
                        <div class="avatar-modern">
                            <img src="{{ asset($profile) }}" alt="Profile">
                        </div>
                    </td>
                    <td style="font-weight: 600; color: #2d3748;">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                    <td style="color: #667eea;">{{ $email }}</td>
                    <td style="color: #4a5568;">{{ $employee->contact_number ?? '—' }}</td>
                    <td>
                        <span class="badge-modern badge-info" style="text-transform: capitalize;">
                            {{ $role }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="#" class="btn-action btn-action-view" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="#" class="btn-action btn-action-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="empty-state-title">No Employees Found</div>
                            <div class="empty-state-description">
                                @if(request('search'))
                                    No results match your search criteria. Try different keywords.
                                @else
                                    Start by adding your first employee to the system.
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>
    </div>

    @if(method_exists($employees,'links'))
        <div class="mt-3 pagination-wrapper">
            {{ $employees->appends(['search'=>request('search')])->links() }}
        </div>
    @endif

</div>
@endsection
