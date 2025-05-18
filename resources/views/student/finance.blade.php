@extends('student.layout')

@section('content')
<style>
    .container { max-width: 700px; margin: 20px auto; font-family: 'Segoe UI', sans-serif; background: #fff; padding: 20px; }
    .title { font-size: 24px; font-weight: bold; text-align: center; margin-bottom: 20px; color: #2c3e50; }
    .card { background-color: #f9f9f9; padding: 16px; border-radius: 12px; margin-bottom: 20px; }
    .section-title { font-size: 18px; font-weight: bold; margin-bottom: 12px; color: #34495e; }
    .course-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #ddd; }
    .course-code { font-size: 16px; color: #2d3436; }
    .course-price { font-size: 16px; font-weight: 600; color: #2e86de; }
    .row { display: flex; justify-content: space-between; margin: 6px 0; }
    .label { font-size: 16px; color: #555; }
    .value { font-size: 16px; font-weight: 500; color: #333; }
    .total-label { font-size: 18px; font-weight: bold; }
    .total-value { font-size: 18px; font-weight: bold; color: #27ae60; }
    .download-btn {
        background-color: #29a3a3;
        color: white;
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 15px;
        margin-top: 10px;
    }
</style>

<div class="container">
    <div class="title">💳 Finance Summary</div>

    <div class="card">
        <div class="section-title">📘 Registered Courses</div>
    @foreach ($finance['courses'] as $course)
        <div class="course-item">
            <span class="course-code">{{ $course->course_code }}</span>
            <span class="course-price">
                ${{ $course->course_price }}
                @if ($course->payment_status === 'Paid')
                    <span style="color: green; font-weight: 600;">✔ Paid</span>
                @elseif ($course->payment_status === 'Partial')
                    <span style="color: orange; font-weight: 600;">⏳ Partial</span>
                @else
                    <span style="color: red; font-weight: 600;">❌ Unpaid</span>
                @endif
            </span>
        </div>
    @endforeach

    </div>

    <div class="card">
        <div class="section-title">📊 Cost Breakdown</div>
        <div class="row">
            <span class="label">Subtotal:</span>
            <span class="value">${{ $finance['subtotal'] }}</span>
        </div>
        <div class="row">
            <span class="label">Service Fee:</span>
            <span class="value">${{ $finance['service_fee'] }}</span>
        </div>
        <hr>
        <div class="row">
            <span class="total-label">Total:</span>
            <span class="total-value">${{ $finance['total'] }}</span>
        </div>
    </div>

    <form action="{{ route('student.downloadFinancePDF') }}" method="GET">
        <button type="submit" class="download-btn">💾 Download PDF</button>
    </form>
</div>
@endsection
