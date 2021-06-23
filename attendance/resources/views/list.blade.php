@extends('layouts.app')

@section('content')

{{-- リアルタイム時刻停止中（再開したい場合は「RealtimeClockArea2」をidタグに） --}}
<p class="times" id=""></p>
  <table border="1" class="table">
    <p class="text-center" ><strong>本日の出勤者</strong></p>
    <thead class="thead-dark">
      <tr>
        <div class="work_time">
        </div>
        <th scope="col">No.</th>
        <th scope="col">出勤者</th>
        <th scope="col">開始予定時間</th>
        <th scope="col">終了予定時間</th>
        <th scope="col">出勤打刻時刻</th>
        <th scope="col">退勤打刻時刻</th>
        <th scope="col">休憩時間</th>
      </tr>
    </thead>
    <tbody>
      <?php $i=1; ?>
      @foreach($timestamp as $value)
      <tr>
        <td>{{ $i }}</td>
        <td>{{ $value->user->name }}</td>
        <td>{{ $value->work_start }}</td>
        <td>{{ $value->work_end }}</td>
        <td>{{ $value->punch_in }}</td>
        <td>{{ $value->punch_out }}</td>
        <td>{{ $value->break_time }}</td>
      </tr>
      <?php $i++ ?>
      @endforeach
    </tbody>
  </table>
  <div class="float-right mb-3 mr-3">
    {{ $timestamp->links() }}
  </div>
@endsection