@extends('student.layout')

@section('content')

      @if(session('success'))
            <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
<div class="form-container">

    <h2 class="form-title">🎓 Apply for Special Request</h2>

    <a href="{{ route('student.docs') }}" class="doc-link">
        <i class="fas fa-file-download"></i> Get Your Docx
    </a>

    <form method="POST" action="{{ route('student.submitRequestForm') }}" enctype="multipart/form-data" class="request-form">
        @csrf

        <div class="form-group">
            <label for="request_type">Request Type</label>
            <select name="request_type" id="request_type" required>
                <option value="">-- Select --</option>
                <option value="Graduation">Graduation</option>
                <option value="Aegrotat Pass">Aegrotat Pass</option>
                <option value="Compassionate Pass">Compassionate Pass</option>
                <option value="Re-sit">Re-sit</option>
            </select>
        </div>

        <div class="form-group">
            <label for="document">Upload Form (PDF/JPG/DOCX)</label>
            <input type="file" name="document" id="document" required>
        </div>

        <button type="submit" class="submit-btn">
            <i class="fas fa-paper-plane"></i> Submit Request
        </button>
    </form>
</div>

<style>
    .form-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 25px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .form-title {
        color: #2c3e50;
        margin-bottom: 20px;
        font-size: 1.5rem;
        font-weight: 600;
    }

      .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

    .doc-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #29a3a3;
        text-decoration: none;
        margin-bottom: 20px;
        transition: color 0.2s;
    }

    .doc-link:hover {
        color: #238c8c;
        text-decoration: underline;
    }

    .request-form {
        display: grid;
        gap: 20px;
    }

    .form-group {
        display: grid;
        gap: 8px;
    }

    .form-group label {
        font-weight: 500;
        color: #2c3e50;
        font-size: 15px;
    }

    .form-group select {
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        width: 100%;
        font-size: 15px;
        background-color: white;
        transition: border 0.2s;
    }

    .form-group select:focus {
        border-color: #29a3a3;
        outline: none;
        box-shadow: 0 0 0 2px rgba(41, 163, 163, 0.2);
    }

    .form-group input[type="file"] {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        background: white;
    }

    .submit-btn {
        background-color: #29a3a3;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.2s;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .submit-btn:hover {
        background-color: #238c8c;
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
        }
        
        .form-title {
            font-size: 1.3rem;
        }
    }
</style>
@endsection