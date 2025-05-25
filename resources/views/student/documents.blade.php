@extends('student.layout')

@section('content')
<div class="dashboard-container">
    <div class="documents-section">
        <h2><i class="fas fa-file-download"></i> Download Forms</h2>
        <div class="document-cards">
            <a href="{{ route('student.downloadDoc', ['filename' => 'graduation.docx']) }}" class="document-card">
                <div class="document-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="document-info">
                    <h3>Graduation Application</h3>
                    <p>Download the graduation application form</p>
                </div>
                <div class="download-btn">
                    <i class="fas fa-download"></i>
                </div>
            </a>

            <a href="{{ route('student.downloadDoc', ['filename' => 'resit.docx']) }}" class="document-card">
                <div class="document-icon">
                    <i class="fas fa-redo-alt"></i>
                </div>
                <div class="document-info">
                    <h3>Re-Sit Application</h3>
                    <p>Download the re-sit application form</p>
                </div>
                <div class="download-btn">
                    <i class="fas fa-download"></i>
                </div>
            </a>

            <a href="{{ route('student.downloadDoc', ['filename' => 'aggrogate.docx']) }}" class="document-card">
                <div class="document-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <div class="document-info">
                    <h3>Aggregate Application</h3>
                    <p>Download the aggregate application form</p>
                </div>
                <div class="download-btn">
                    <i class="fas fa-download"></i>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
    .dashboard-container {
        padding: 25px;
    }

    .documents-section {
        background: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .documents-section h2 {
        color: #2c3e50;
        font-size: 22px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .documents-section h2 i {
        color: #29a3a3;
    }

    .document-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .document-card {
        display: flex;
        align-items: center;
        background-color: #f5f7fa;
        border-radius: 8px;
        padding: 20px;
        text-decoration: none;
        color: #2c3e50;
        transition: all 0.3s ease;
        border-left: 4px solid #29a3a3;
    }

    .document-card:hover {
        background-color: #e8f4f4;
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .document-icon {
        background-color: #29a3a3;
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .document-info {
        flex-grow: 1;
    }

    .document-info h3 {
        font-size: 16px;
        margin-bottom: 5px;
        color: #2c3e50;
    }

    .document-info p {
        font-size: 14px;
        color: #666;
        margin: 0;
    }

    .download-btn {
        color: #29a3a3;
        font-size: 18px;
        transition: all 0.2s ease;
    }

    .document-card:hover .download-btn {
        transform: scale(1.2);
    }

    @media (max-width: 768px) {
        .document-cards {
            grid-template-columns: 1fr;
        }
        
        .document-card {
            flex-direction: column;
            text-align: center;
            padding: 25px 15px;
        }
        
        .document-icon {
            margin-right: 0;
            margin-bottom: 15px;
        }
    }
</style>
@endsection