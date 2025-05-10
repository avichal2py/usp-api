@extends('lecturer.layout')

@section('content')
<style>
    .grade-container {
        max-width: 900px;
        margin: 30px auto;
        padding: 20px;
        font-family: 'Segoe UI', sans-serif;
    }

    .title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 25px;
        color: #2c3e50;
    }

    .card {
        background-color: #fdfdfd;
        padding: 18px;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        border-left: 5px solid #27ae60;
    }

    .info {
        font-size: 15px;
        margin: 4px 0;
        color: #34495e;
    }

    .label {
        font-weight: 600;
        color: #2c3e50;
    }

    .form-inline {
        margin-top: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-inline input[type="text"] {
        padding: 6px 10px;
        font-size: 15px;
        border: 1px solid #ccc;
        border-radius: 6px;;
    }

    .update-btn {
        background-color: #27ae60;
        color: white;
        border: none;
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 6px;
        cursor: pointer;
    }

    .action-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 12px;
    }

    .reject-btn {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 6px;
        cursor: pointer;
    }

    .reject-btn:hover {
        background-color: #c0392b;
    }


    .update-btn :hover {
        background-color: #219150;
    }

    .empty-message {
        font-size: 16px;
        color: #888;
        text-align: center;
        margin-top: 30px;
    }

    .success-msg {
        background-color: #d4edda;
        color: #155724;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .reject-msg {
        background-color: #d4edda;
        color:rgb(217, 3, 3);
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
</style>

@if(session('success'))
        <div class="success-msg">{{ session('success') }}</div>
    @endif

    @if(session('reject'))
        <div class="reject-msg">{{ session('reject') }}</div>
    @endif

<div class="grade-container">
    <h2 class="title">📩 Grade Recheck Requests</h2>

    @foreach ($rechecks as $req)
        <div class="card">
            <p class="info"><span class="label">Student:</span> {{ $req->first_name }} {{ $req->last_name }} ({{ $req->student_id }})</p>
            <p class="info"><span class="label">Course:</span> {{ $req->course_code }} - {{ $req->course_name }}</p>
            <p class="info"><span class="label">Old Grade:</span> {{ $req->old_grade ?? '—' }}</p>
            <p class="info"><span class="label">Reason:</span> {{ $req->reason ?? '—' }}</p>

            <div class="action-row">
                <form method="POST" action="{{ route('lecturer.updateGrade', $req->id) }}" class="form-inline">
                    @csrf
                    <input type="text" name="new_grade" placeholder="Enter new grade" required>
                    <button type="submit" class="update-btn">Update Grade</button>
                </form>

                <form method="POST" action="{{ route('lecturer.rejectGrade', $req->id) }}" class="form-inline">
                    @csrf
                    <button type="submit" class="reject-btn">Reject Request</button>
                </form>
            </div>

        </div>
    @endforeach

    @if ($rechecks->isEmpty())
        <p class="empty-message">No pending grade recheck requests.</p>
    @endif
</div>
@endsection
