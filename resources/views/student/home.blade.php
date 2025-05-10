@extends('student.layout')

@section('content')
<style>
  .container {
    padding: 24px;
    max-width: 700px;
    margin: auto;
    font-family: 'Segoe UI', sans-serif;
  }

  .title {
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 10px;
  }

  .notify-box {
    background-color: #fef3c7;
    border-left: 5px solid #f59e0b;
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
  }

  .notify-box h4 {
    margin-top: 0;
    font-size: 16px;
    color: #b45309;
  }

  .notify-btn {
    margin-top: 10px;
    background-color: #10b981;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    max-height: 40vh;
    overflow-y: auto;
  }

  .notify-msg {
    margin-bottom: 4px;
  }
</style>

<div class="container">
  @if(session('user'))
    <h1 class="title">Welcome, {{ session('user')->first_name }} 👋</h1>

    {{-- Grade Recheck Notifications --}}
    @php
        $notifications = DB::table('grade_rechecks')
            ->where('student_id', session('user')->student_id)
            ->whereIn('status', ['Reviewed', 'Rejected'])
            ->where('student_notified', false)
            ->get();
    @endphp

    @if ($notifications->isNotEmpty())
      <div class="notify-box">
        <h4>📢 Grade Recheck Notifications</h4>
        @foreach ($notifications as $note)
          <p class="notify-msg">
            <strong>{{ $note->course_code }}</strong>: {{ $note->lecturer_message ?? 'Recheck reviewed.' }}
          </p>
        @endforeach

        <form method="POST" action="{{ route('student.dismissAllNotifications') }}">
          @csrf
          <input type="hidden" name="notification_ids" value="{{ $notifications->pluck('id')->implode(',') }}">
          <button type="submit" class="notify-btn">OK</button>
        </form>
      </div>
    @endif
  @endif
</div>
@endsection
