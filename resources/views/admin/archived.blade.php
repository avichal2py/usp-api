@extends('admin.layout')

@section('content')
<div id="archived-logs-container" class="container mt-4">
    <h2 id="archived-logs-title">🗃 Archived Login Logs</h2>

    {{-- Tabs --}}
    <div id="archived-logs-tabs" class="mb-3">
        <a href="{{ route('admin.loginLogs') }}" class="btn btn-outline-primary" id="btn-active-logs">Active Logs</a>
        <a href="{{ route('admin.loginLogs.archived') }}" class="btn btn-outline-secondary active" id="btn-archived-logs">Archived Logs</a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div id="archived-success-alert" class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <form id="archived-filters-form" method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="date" name="date" id="filter-date" value="{{ request('date') }}" class="form-control" placeholder="Date">
        </div>
        <div class="col-md-3">
            <select name="type" id="filter-type" class="form-control">
                <option value="">-- Type --</option>
                <option value="STUDENT" {{ request('type') == 'STUDENT' ? 'selected' : '' }}>STUDENT</option>
                <option value="EMPLOYEE" {{ request('type') == 'EMPLOYEE' ? 'selected' : '' }}>EMPLOYEE</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" id="filter-status" class="form-control">
                <option value="">-- Status --</option>
                <option value="SUCCESS" {{ request('status') == 'SUCCESS' ? 'selected' : '' }}>SUCCESS</option>
                <option value="FAIL" {{ request('status') == 'FAIL' ? 'selected' : '' }}>FAIL</option>
            </select>
        </div>
        <div class="col-md-3">
            <button id="btn-apply-filter" class="btn btn-secondary w-100">Filter</button>
        </div>
    </form>

    {{-- Restore Form --}}
    <form id="archived-restore-form" action="{{ route('admin.loginLogs.restore') }}" method="POST">
        @csrf

        <div class="table-responsive">
            <table id="archived-logs-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>User Type</th>
                        <th>ID</th>
                        <th>Status</th>
                        <th>IP Address</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td><input type="checkbox" name="selected_logs[]" value="{{ $log->id }}"></td>
                            <td>{{ $log->type }}</td>
                            <td>{{ $log->identifier }}</td>
                            <td>{{ $log->status }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td>{{ $log->created_at }}</td>
                            <td>{{ $log->action }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No archived logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <button id="btn-restore-selected" type="submit" class="btn btn-success mt-3">Restore Selected</button>
    </form>

    {{-- Pagination --}}
    <div id="archived-pagination" class="mt-4">
        {{ $logs->links() }}
    </div>
</div>

{{-- JavaScript --}}
<script>
    document.getElementById('selectAll').onclick = function () {
        const checkboxes = document.querySelectorAll('input[name="selected_logs[]"]');
        checkboxes.forEach(cb => cb.checked = this.checked);
    };
</script>

<style>
/* ===== BASE STYLES ===== */
#archived-logs-container {
    font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    color: #333;
    line-height: 1.6;
    padding: 20px;
    margin: 0 auto;
    display:block;
}

/* ===== HEADER STYLES ===== */
#archived-logs-title {
    font-size: 28px;
    font-weight: 600;
    color: #2c3e50;
    margin: 0 0 20px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* ===== TABS STYLES ===== */
#archived-logs-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
}

#archived-logs-tabs .btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

#btn-active-logs {
    background-color: transparent;
    border: 1px solid #4a6cf7;
    color: #4a6cf7;
}

#btn-active-logs:hover {
    background-color: #4a6cf7;
    color: white;
}

#btn-archived-logs {
    background-color: #4a6cf7;
    border: 1px solid #4a6cf7;
    color: white;
}

/* ===== ALERT MESSAGE ===== */
#archived-success-alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    background-color: #e6f7ee;
    color: #1a7d56;
    border-left: 4px solid #1a7d56;
}

/* ===== FILTER CARD ===== */
#archived-filters-form {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    margin-bottom: 24px;
    padding: 20px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
}

#archived-filters-form .form-control {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s;
    width: 100%;
}

#archived-filters-form .form-control:focus {
    border-color: #4a6cf7;
    outline: none;
    box-shadow: 0 0 0 2px rgba(74, 108, 247, 0.1);
}

#btn-apply-filter {
    background-color: #4a6cf7;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 100%;
}

#btn-apply-filter:hover {
    background-color: #3a5bd9;
}

/* ===== LOGS TABLE ===== */
.table-responsive {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    margin-bottom: 20px;
}

#archived-logs-table {
    width: 100%;
    border-collapse: collapse;
}

#archived-logs-table thead th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    color: white;
    background-color: #3498db;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

#archived-logs-table tbody tr {
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s;
}

#archived-logs-table tbody tr:last-child {
    border-bottom: none;
}

#archived-logs-table tbody tr:hover {
    background-color: #f8f9fa;
}

#archived-logs-table td {
    padding: 16px;
    vertical-align: middle;
}

/* User Type Badges */
#archived-logs-table td:nth-child(2) {
    text-transform: capitalize;
}

#archived-logs-table td:nth-child(2):before {
    content: "";
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 8px;
}

#archived-logs-table td:nth-child(2):contains("STUDENT"):before {
    background-color: #4a6cf7;
}

#archived-logs-table td:nth-child(2):contains("EMPLOYEE"):before {
    background-color: #2abb9b;
}

/* Status Badges */
#archived-logs-table td:nth-child(4) {
    font-weight: 500;
}

#archived-logs-table td:nth-child(4):contains("SUCCESS") {
    color: #1a7d56;
}

#archived-logs-table td:nth-child(4):contains("FAIL") {
    color: #db3838;
}

#archived-logs-table td:nth-child(4):contains("LOGOUT") {
    color: #6b7280;
}

/* IP Address */
#archived-logs-table td:nth-child(5) {
    font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, monospace;
    font-size: 14px;
}

/* Date Formatting */
#archived-logs-table td:nth-child(6) {
    white-space: nowrap;
}

/* ===== RESTORE BUTTON ===== */
#btn-restore-selected {
    background-color: #1a7d56;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

#btn-restore-selected:hover {
    background-color: #166c4b;
}

/* ===== PAGINATION ===== */
#archived-pagination {
    margin-top: 24px;
}

#archived-pagination .pagination {
    display: flex;
    gap: 8px;
    justify-content: center;
}

#archived-pagination .page-item .page-link {
    padding: 8px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    color: #4a6cf7;
    text-decoration: none;
    transition: all 0.2s ease;
    background-color: white;
}

#archived-pagination .page-item.active .page-link {
    background-color: #4a6cf7;
    border-color: #4a6cf7;
    color: white;
}

#archived-pagination .page-item.disabled .page-link {
    color: #94a3b8;
    background-color: #f8fafc;
    border-color: #e2e8f0;
}

/* ===== EMPTY STATE ===== */
.text-center.text-muted {
    padding: 40px 20px;
    text-align: center;
    color: #777;
}

/* ===== RESPONSIVE ADJUSTMENTS ===== */
@media (max-width: 768px) {
    #archived-filters-form {
        grid-template-columns: 1fr;
    }
    
    #archived-logs-table thead {
        display: none;
    }
    
    #archived-logs-table tbody tr {
        display: block;
        margin-bottom: 15px;
        border: 1px solid #eee;
        border-radius: 6px;
    }
    
    #archived-logs-table tbody td {
        display: flex;
        justify-content: space-between;
        padding: 12px;
        border-top: 1px solid #eee;
    }
    
    #archived-logs-table tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        margin-right: 15px;
        color: #555;
    }
    
    #archived-logs-table tbody td:first-child {
        border-top: none;
    }
}
</style>
@endsection
