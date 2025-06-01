@extends('admin.layout')

@section('content')
<div class="admin-logs">
    <div class="admin-logs__header">
        <div class="header__top">
            <h1 class="header__title">
                <i class="fas fa-clipboard-list"></i>Activity Logs
            </h1>
            <a href="{{ route('admin.loginLogs.archived') }}" class="archive-btn">
                <i class="fas fa-archive"></i> Archived Logs
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-message success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button class="alert-close">&times;</button>
        </div>
    @endif

    <div class="filter-card">
        <div class="filter-card__header">
            <h2><i class="fas fa-filter"></i> Filter Logs</h2>
        </div>
        <div class="filter-card__body">
            <form method="GET" class="filter-form">
                <div class="filter-group">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" value="{{ request('date') }}">
                </div>
                <div class="filter-group">
                    <label for="type">User Type</label>
                    <select name="type" id="type">
                        <option value="">All Types</option>
                        <option value="STUDENT" {{ request('type') == 'STUDENT' ? 'selected' : '' }}>Student</option>
                        <option value="EMPLOYEE" {{ request('type') == 'EMPLOYEE' ? 'selected' : '' }}>Employee</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select name="status" id="status">
                        <option value="">All Statuses</option>
                        <option value="SUCCESS" {{ request('status') == 'SUCCESS' ? 'selected' : '' }}>Success</option>
                        <option value="FAIL" {{ request('status') == 'FAIL' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="filter-group">
                    <button type="submit" class="filter-btn">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <form action="{{ route('admin.loginLogs.archive') }}" method="POST" class="archive-form">
        @csrf
        <div class="logs-card">
            <div class="logs-card__body">
                <div class="logs-table-container">
                    <table class="logs-table">
                        <thead>
                            <tr>
                                <th class="select-col">
                                    <input type="checkbox" id="selectAll" class="select-checkbox">
                                </th>
                                <th>User Type</th>
                                <th>ID</th>
                                <th>Status</th>
                                <th>IP Address</th>
                                <th>Log Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td class="select-col">
                                        <input type="checkbox" name="selected_logs[]" value="{{ $log->id }}" 
                                               id="log_{{ $log->id }}" class="select-checkbox">
                                    </td>
                                    <td>
                                        <span class="user-type-badge {{ strtolower($log->type) }}">
                                            <i class="fas {{ $log->type == 'STUDENT' ? 'fa-user-graduate' : 'fa-user-tie' }}"></i>
                                            {{ ucfirst(strtolower($log->type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="identifier">{{ $log->identifier }}</span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ strtolower($log->status) }}">
                                            <i class="fas {{ 
                                                $log->status == 'SUCCESS' ? 'fa-check-circle' : 
                                                ($log->status == 'FAIL' ? 'fa-times-circle' : 'fa-sign-out-alt') 
                                            }}"></i>
                                            {{ $log->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="ip-address">{{ $log->ip_address }}</span>
                                    </td>
                                    <td>
                                        <div class="log-time">
                                            <span class="log-date">{{ $log->created_at->format('M d, Y') }}</span>
                                            <span class="log-hour">{{ $log->created_at->format('h:i A') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="log-action">{{ $log->action }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="empty-state">
                                        <i class="fas fa-database"></i>
                                        <h5>No logs found</h5>
                                        <p>Try adjusting your filters or check back later</p>
                                        <a href="{{ route('admin.loginLogs') }}" class="reset-btn">
                                            <i class="fas fa-sync"></i> Reset Filters
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($logs->count() > 0)
                <div class="logs-card__footer">
                    <div class="select-all">
                        <input type="checkbox" id="selectAllFooter" class="select-checkbox">
                        <label for="selectAllFooter">Select all {{ $logs->total() }} logs</label>
                    </div>
                    <button type="submit" class="archive-selected-btn">
                        <i class="fas fa-archive"></i> Archive Selected
                    </button>
                </div>
            @endif
        </div>
    </form>

    @if($logs->hasPages())
        <div class="pagination-container">
            {{ $logs->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle select all checkboxes
        const selectAllCheckbox = document.getElementById('selectAll');
        const selectAllFooter = document.getElementById('selectAllFooter');
        const itemCheckboxes = document.querySelectorAll('input[name="selected_logs[]"]');
        
        function updateSelectAll() {
            const allChecked = Array.from(itemCheckboxes).every(checkbox => checkbox.checked);
            selectAllCheckbox.checked = allChecked;
            selectAllFooter.checked = allChecked;
        }
        
        selectAllCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            selectAllFooter.checked = this.checked;
        });
        
        selectAllFooter.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            selectAllCheckbox.checked = this.checked;
        });
        
        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectAll);
        });

        // Close alert message
        const alertClose = document.querySelector('.alert-close');
        if (alertClose) {
            alertClose.addEventListener('click', function() {
                this.closest('.alert-message').style.display = 'none';
            });
        }
    });
</script>

<style>
/* ===== BASE STYLES ===== */
.admin-logs {
    font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    color: #333;
    line-height: 1.6;
    padding: 20px;
    margin: 0 auto;
}

/* ===== HEADER STYLES ===== */
.admin-logs__header {
    margin-bottom: 30px;
}

.header__top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.header__title {
    font-size: 28px;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

.header__title i {
    color: #4a6cf7;
    margin-right: 10px;
}

.archive-btn {
    background-color: transparent;
    border: 1px solid #4a6cf7;
    color: #4a6cf7;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.archive-btn:hover {
    background-color: #4a6cf7;
    color: white;
}

/* ===== ALERT MESSAGE ===== */
.alert-message {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
}

.alert-message.success {
    background-color: #e6f7ee;
    color: #1a7d56;
    border-left: 4px solid #1a7d56;
}

.alert-message i {
    font-size: 18px;
}

.alert-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    margin-left: auto;
    color: inherit;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.alert-close:hover {
    opacity: 1;
}

/* ===== FILTER CARD ===== */
.filter-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    margin-bottom: 24px;
    overflow: hidden;
}

.filter-card__header {
    padding: 16px 24px;
    border-bottom: 1px solid #eee;
}

.filter-card__header h2 {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    color: #444;
}

.filter-card__header i {
    color: #4a6cf7;
    margin-right: 10px;
}

.filter-card__body {
    padding: 20px 24px;
}

.filter-form {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-group label {
    font-size: 14px;
    font-weight: 500;
    color: #555;
}

.filter-group input,
.filter-group select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.filter-group input:focus,
.filter-group select:focus {
    border-color: #4a6cf7;
    outline: none;
    box-shadow: 0 0 0 2px rgba(74, 108, 247, 0.1);
}

.filter-btn {
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
}

.filter-btn:hover {
    background-color: #3a5bd9;
}

/* ===== LOGS TABLE ===== */
.logs-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.logs-card__body {
    overflow-x: auto;
}

.logs-table {
    width: 100%;
    border-collapse: collapse;
}

.logs-table thead th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    color: white;
    background-color: #3498db;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.logs-table tbody tr {
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s;
}

.logs-table tbody tr:last-child {
    border-bottom: none;
}

.logs-table tbody tr:hover {
    background-color: #f8f9fa;
}

.logs-table td {
    padding: 16px;
    vertical-align: middle;
}

.select-col {
    width: 40px;
    text-align: center;
}

.select-checkbox {
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.user-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.user-type-badge i {
    font-size: 14px;
}

.user-type-badge.student {
    background-color: rgba(74, 108, 247, 0.1);
    color: #4a6cf7;
}

.user-type-badge.employee {
    background-color: rgba(42, 187, 155, 0.1);
    color: #2abb9b;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.status-badge i {
    font-size: 14px;
}

.status-badge.success {
    background-color: rgba(26, 125, 86, 0.1);
    color: #1a7d56;
}

.status-badge.fail {
    background-color: rgba(219, 56, 56, 0.1);
    color: #db3838;
}

.status-badge.logout {
    background-color: rgba(107, 114, 128, 0.1);
    color: #6b7280;
}

.identifier {
    font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, monospace;
    font-size: 14px;
    color: #444;
}

.ip-address {
    font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, monospace;
    font-size: 14px;
    color: #444;
    background: #f5f5f5;
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-block;
}

.log-time {
    display: flex;
    flex-direction: column;
}

.log-date {
    font-weight: 500;
    color: #444;
}

.log-hour {
    font-size: 13px;
    color: #777;
}

.log-action {
    color: #666;
    font-size: 14px;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    padding: 40px 20px;
    text-align: center;
}

.empty-state i {
    font-size: 48px;
    color: #ccc;
    margin-bottom: 16px;
}

.empty-state h5 {
    font-size: 18px;
    color: #444;
    margin-bottom: 8px;
    font-weight: 600;
}

.empty-state p {
    color: #777;
    margin-bottom: 16px;
}

.reset-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background-color: transparent;
    border: 1px solid #4a6cf7;
    color: #4a6cf7;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s;
}

.reset-btn:hover {
    background-color: #4a6cf7;
    color: white;
}

/* ===== FOOTER ===== */
.logs-card__footer {
    padding: 16px 24px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.select-all {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #555;
}

.archive-selected-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: transparent;
    border: 1px solid #db3838;
    color: #db3838;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

.archive-selected-btn:hover {
    background-color: #db3838;
    color: white;
}

/* ===== PAGINATION ===== */
.pagination-container {
    margin-top: 30px;
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    gap: 6px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.page-item {
    display: flex;
    align-items: center;
}

.page-link {
    padding: 8px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    color: #4a6cf7;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s ease;
    background-color: white;
    min-width: 40px;
    text-align: center;
}

.page-link:hover {
    background-color: #f8fafc;
    border-color: #cbd5e1;
    color: #3a5bd9;
}

.page-item.active .page-link {
    background-color: #4a6cf7;
    border-color: #4a6cf7;
    color: white;
    font-weight: 600;
}

.page-item.disabled .page-link {
    color: #94a3b8;
    background-color: #f8fafc;
    border-color: #e2e8f0;
    cursor: not-allowed;
}

/* Previous and Next buttons */
.page-item:first-child .page-link,
.page-item:last-child .page-link {
    padding: 8px 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.page-item:first-child .page-link::before {
    content: "←";
    font-weight: bold;
}

.page-item:last-child .page-link::after {
    content: "→";
    font-weight: bold;
}

/* Ellipsis styling */
.page-item .page-link[aria-label="..."] {
    background: transparent;
    border: none;
    color: #64748b;
    padding: 8px 4px;
    pointer-events: none;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .pagination {
        gap: 4px;
    }
    
    .page-link {
        padding: 6px 10px;
        min-width: 32px;
        font-size: 13px;
    }
    
    .page-item:first-child .page-link,
    .page-item:last-child .page-link {
        padding: 6px 8px;
    }
    
    .page-item:first-child .page-link::before,
    .page-item:last-child .page-link::after {
        content: "";
    }
    
    .page-item:first-child .page-link span,
    .page-item:last-child .page-link span {
        display: none;
    }
}

/* ===== RESPONSIVE ADJUSTMENTS ===== */
@media (max-width: 768px) {
    .header__top {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .archive-btn {
        width: 100%;
        justify-content: center;
    }
    
    .filter-form {
        grid-template-columns: 1fr;
    }
    
    .logs-card__footer {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
}
</style>
@endsection