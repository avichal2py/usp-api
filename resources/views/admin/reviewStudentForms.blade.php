@extends('admin.layout')

@section('content')
  @if(session('success'))
    <div class="alert alert-success">
      <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
  @elseif(session('error'))
    <div class="alert alert-error">
      <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
  @endif
<div class="requests-container">
    <h2 class="requests-title">📂 Student Requests</h2>

    <div class="filter-search-bar">
        <input type="text" id="searchInput" placeholder="Search by student name or ID...">
        <select id="filterType">
            <option value="">All Types</option>
            <option value="Graduation">Graduation</option>
            <option value="Aegrotat Pass">Aegrotat Pass</option>
            <option value="Compassionate Pass">Compassionate Pass</option>
            <option value="Re-sit">Re-sit</option>
        </select>
    </div>


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
      .alert {
    padding: 12px 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
  }

  .alert-success {
    background-color: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
  }

  .alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
  }
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

    .filter-search-bar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    gap: 10px;
}

#searchInput,
#filterType {
    padding: 10px;
    font-size: 14px;
    border-radius: 6px;
    border: 1px solid #ccc;
    flex: 1;
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const filterType = document.getElementById('filterType');
    const cards = document.querySelectorAll('.request-card');

    function normalize(text) {
        return text.toLowerCase().trim();
    }

    function filterRequests() {
        const searchValue = normalize(searchInput.value);
        const typeValue = filterType.value;

        cards.forEach(card => {
            const studentText = normalize(card.querySelector('.request-info').textContent);
            const requestType = card.querySelector('.request-info p:nth-child(3)').textContent;

            const matchesSearch = studentText.includes(searchValue);
            const matchesType = !typeValue || requestType.includes(typeValue);

            if (matchesSearch && matchesType) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterRequests);
    filterType.addEventListener('change', filterRequests);
});
</script>

@endsection