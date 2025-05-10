@extends('student.layout')

@section('content')
<style>
    .container {
        max-width: 960px;
        margin: 20px auto;
        padding: 20px;
        font-family: 'Segoe UI', sans-serif;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    .title {
        font-size: 24px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 20px;
    }

    .success-msg {
        background-color: #d4edda;
        color: #155724;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th, td {
        padding: 12px 14px;
        border-bottom: 1px solid #ccc;
        text-align: left;
        font-size: 15px;
    }

    th {
        background-color: #f1f1f1;
        color: #333;
    }

    .form-input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
    }

    .submit-btn {
        background-color: #29a3a3;
        color: white;
        padding: 8px 14px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease;
        margin-top: 4px;
    }

    .submit-btn:hover {
        background-color: #248888;
    }

    .form-flex {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .reason-input {
        flex: 1;
    }

    @media (max-width: 768px) {
        .form-flex {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>

<div class="container">
    <div class="title">📋 Request Grade Recheck</div>

    @if(session('success'))
        <div class="success-msg">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course</th>
                <th>Grade</th>
                <th>Apply</th>
            </tr>
        </thead>
        <tbody>
            @foreach($completedCourses as $course)
                <tr>
                    <td>{{ $course->course_code }}</td>
                    <td>{{ $course->course_name }}</td>
                    <td>{{ $course->grade }}</td>
                    <td>
                        <form method="POST" action="{{ route('student.applyRecheck') }}">
                            @csrf
                            <input type="hidden" name="course_code" value="{{ $course->course_code }}">
                            <div class="form-flex">
                                <input type="text" name="reason" class="form-input reason-input" placeholder="Optional reason">
                                <button type="submit" class="submit-btn">Apply</button>
                            </div>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
