@extends('admin.layout')

@section('content')
    <div class="restrictions-container">
        <h2 class="restrictions-title">
            <i class="fas fa-user-lock"></i> Manage Student Restrictions
        </h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <button class="close-btn">&times;</button>
            </div>
        @endif

        <div class="search-container">
            <form method="GET" action="{{ route('admin.restrict.search') }}" class="search-form">
                <div class="search-input-group">
                    <input type="text" name="search" placeholder="Search by ID, name or email..." 
                           value="{{ request('search') }}" class="search-input">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.restrict.search') }}" class="clear-search">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="students-table-container">
            <table class="students-table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($students as $s)
                    <tr class="{{ $s->is_restricted ? 'restricted' : '' }}">
                        <td>{{ $s->student_id }}</td>
                        <td>{{ $s->first_name }} {{ $s->last_name }}</td>
                        <td>{{ $s->email_address }}</td>
                        <td>
                            <span class="status-badge {{ $s->is_restricted ? 'restricted' : 'unrestricted' }}">
                                {{ $s->is_restricted ? 'Restricted' : 'Unrestricted' }}
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.restrict.toggle', $s->student_id) }}" class="toggle-form">
                                @csrf
                                <button type="submit" class="toggle-btn {{ $s->is_restricted ? 'unrestrict' : 'restrict' }}">
                                    {{ $s->is_restricted ? 'Unrestrict' : 'Restrict' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="no-results">
                            @if(request('search'))
                                No students found matching "{{ request('search') }}"
                            @else
                                No students found
                            @endif
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            
            {{-- Only show pagination if we're dealing with a paginator object --}}
            @if(method_exists($students, 'hasPages') && $students->hasPages())
                <div class="pagination-container">
                    {{ $students->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
.restrictions-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .restrictions-title {
            color: #2c3e50;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .restrictions-title i {
            color: #e74c3c;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            position: relative;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .close-btn {
            position: absolute;
            right: 10px;
            top: 10px;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: inherit;
        }

        .students-table-container {
            overflow-x: auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .students-table {
            width: 100%;
            border-collapse: collapse;
        }

        .students-table th {
            background-color: #3498db;
            color: white;
            padding: 12px 15px;
            text-align: left;
        }

        .students-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .students-table tr:last-child td {
            border-bottom: none;
        }

        .students-table tr:hover {
            background-color: #f5f5f5;
        }

        .students-table tr.restricted {
            background-color: #ffebee;
        }

        .students-table tr.restricted:hover {
            background-color: #ffcdd2;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 500;
        }

        .status-badge.restricted {
            background-color: #ffcdd2;
            color: #c62828;
        }

        .status-badge.unrestricted {
            background-color: #c8e6c9;
            color: #2e7d32;
        }

        .toggle-form {
            display: inline;
        }

        .toggle-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }

        .toggle-btn.restrict {
            background-color: #f39c12;
            color: white;
        }

        .toggle-btn.restrict:hover {
            background-color: #e67e22;
        }

        .toggle-btn.unrestrict {
            background-color: #2ecc71;
            color: white;
        }

        .toggle-btn.unrestrict:hover {
            background-color: #27ae60;
        }        
 /* Search Container Styles */
.search-container {
    margin-bottom: 2rem;
    background: #ffffff;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid #e9ecef;
}

.search-form {
    max-width: 700px;
    margin: 0 auto;
    position: relative;
}

.search-input-group {
    position: relative;
    display: flex;
    align-items: center;
    background: #ffffff;
    border-radius: 50px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #dee2e6;
}

.search-input-group:focus-within {
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
    border-color: #3498db;
}

.search-input {
    flex: 1;
    padding: 0.875rem 1.5rem;
    border: none;
    font-size: 1rem;
    outline: none;
    background: transparent;
    color: #495057;
    font-weight: 400;
    line-height: 1.5;
}

.search-input::placeholder {
    color: #adb5bd;
    opacity: 1;
    font-weight: 300;
}

.search-button {
    position: relative;
    height: 100%;
    width: 56px;
    padding:0.5%;
    background: linear-gradient(135deg, #3498db, #2980b9);
    border: none;
    color: white;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: auto;
}

.search-button:hover {
    background: linear-gradient(135deg, #2980b9, #3498db);
    transform: translateY(-1px);
}

.search-button:active {
    transform: translateY(0);
}

.search-button i {
    font-size: 1.125rem;
    transition: transform 0.2s ease;
}

.search-button:hover i {
    transform: scale(1.1);
}

.clear-search {
    position: absolute;
    right: 70px;
    color: #6c757d;
    text-decoration: none;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    transition: all 0.2s ease;
    background: rgba(255, 255, 255, 0.9);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.clear-search:hover {
    color: #e74c3c;
    background: rgba(255, 255, 255, 1);
    transform: translateX(-2px);
}

.clear-search i {
    font-size: 0.875rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .search-container {
        padding: 1rem;
    }
    
    .search-input {
        padding: 0.75rem 1.25rem;
        font-size: 0.9375rem;
    }
    
    .search-button {
        width: 50px;
    }
    
    .clear-search {
        right: 60px;
        font-size: 0.8125rem;
    }
}

@media (max-width: 576px) {
    .search-input-group {
        border-radius: 8px;
    }
    
    .search-input {
        padding: 0.6875rem 1rem;
        font-size: 0.875rem;
    }
    
    .search-button {
        width: 46px;
    }
    
    .clear-search {
        right: 56px;
        padding: 0.125rem 0.375rem;
    }
}

.no-results {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
    font-size: 1.125rem;
    background: #f8f9fa;
    border-radius: 8px;
    margin: 1rem 0;
}

/* Pagination Styles */
.pagination-container {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

.pagination-container .pagination {
    display: flex;
    gap: 0.5rem;
    list-style: none;
    padding: 0;
    margin: 0;
}

.pagination-container .page-item {
    display: flex;
    align-items: center;
    justify-content: center;
}

.pagination-container .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.5rem;
    height: 2.5rem;
    padding: 0 0.75rem;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    background-color: white;
    color: #3498db;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.pagination-container .page-link:hover {
    background-color: #f8f9fa;
    border-color: #ced4da;
    color: #2c7be5;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.pagination-container .page-item.active .page-link {
    background-color: #3498db;
    border-color: #3498db;
    color: white;
    box-shadow: 0 2px 4px rgba(52, 152, 219, 0.3);
}

.pagination-container .page-item.disabled .page-link {
    color: #adb5bd;
    pointer-events: none;
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.pagination-container .page-item:first-child .page-link,
.pagination-container .page-item:last-child .page-link {
    padding: 0 1rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .pagination-container .page-link {
        min-width: 2.25rem;
        height: 2.25rem;
        font-size: 0.875rem;
    }
}

@media (max-width: 576px) {
    .pagination-container .pagination {
        gap: 0.25rem;
    }
    
    .pagination-container .page-link {
        min-width: 2rem;
        height: 2rem;
        padding: 0 0.5rem;
        font-size: 0.8125rem;
    }
    
    .pagination-container .page-item:first-child .page-link,
    .pagination-container .page-item:last-child .page-link {
        padding: 0 0.75rem;
    }
}
    </style>

    <script>
        // Simple script to handle alert dismissal
        document.querySelectorAll('.close-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        });
        
        // Focus search input when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.focus();
            }
        });
    </script>
@endsection