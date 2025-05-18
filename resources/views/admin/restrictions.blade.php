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
                @foreach($students as $s)
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
                @endforeach
                </tbody>
            </table>
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
    </style>

    <script>
        // Simple script to handle alert dismissal
        document.querySelectorAll('.close-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        });
    </script>
@endsection