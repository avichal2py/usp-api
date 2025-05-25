@extends('admin.layout')

@section('content')
<div class="requests-container">
    <h2 class="requests-title">📂 Student Requests</h2>

    @foreach ($forms as $form)
        <div class="request-card">
            <div class="request-info">
                <p><strong>Student:</strong> {{ $form->first_name }} {{ $form->last_name }} ({{ $form->student_id }})</p>
                <p><strong>Program:</strong> {{ $form->program_name }}</p>
                <p><strong>Request Type:</strong> {{ $form->request_type }}</p>
                <p><strong>Submitted At:</strong> {{ $form->created_at }}</p>
            </div>

            <div class="request-actions">
                @if (!empty($form->document_path))
                    <a href="{{ route('admin.downloadStudentDoc', ['filename' => basename($form->document_path)]) }}" 
                    class="download-btn">📥 Download Form</a>
                @endif

                <form action="{{ route('admin.approveForm', $form->id) }}" method="POST" class="action-form">
                    @csrf
                    <button type="submit" class="approve-btn">Approve</button>
                </form>

                <form action="{{ route('admin.rejectForm', $form->id) }}" method="POST" class="action-form">
                    @csrf
                    <button type="submit" class="reject-btn">Reject</button>
                </form>
            </div>
        </div>
    @endforeach

    @if ($forms->isEmpty())
        <p class="no-requests">No pending requests.</p>
    @endif
</div>

<style>
    .requests-container {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
    }

    .requests-title {
        color: #2c3e50;
        font-size: 24px;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e0e0e0;
    }

    .request-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border-left: 4px solid #29a3a3;
    }

    .request-info p {
        margin: 8px 0;
        color: #2c3e50;
        line-height: 1.4;
    }

    .request-info strong {
        color: #2c3e50;
        min-width: 100px;
        display: inline-block;
    }

    .request-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
        align-items: center;
    }

    .download-btn {
        background-color: #29a3a3;
        color: white;
        padding: 8px 15px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.2s;
        margin-right: 10px;
    }

    .download-btn:hover {
        background-color: #238c8c;
    }

    .action-form {
        display: inline;
    }

    .approve-btn {
        background-color: #2ecc71;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .approve-btn:hover {
        background-color: #27ae60;
    }

    .reject-btn {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .reject-btn:hover {
        background-color: #c0392b;
    }

    .no-requests {
        text-align: center;
        color: #888;
        padding: 30px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    @media (max-width: 768px) {
        .request-actions {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .download-btn {
            margin-right: 0;
            margin-bottom: 10px;
            width: 100%;
            text-align: center;
        }
        
        .action-form {
            width: 100%;
        }
        
        .approve-btn,
        .reject-btn {
            width: 100%;
        }
    }
</style>
@endsection