@extends('layouts.app')

@section('content')
@if (session('status'))
  <div class="alert alert-success">
      {{ session('status') }}
  </div>
@endif
@if (session('error'))
  <div class="alert alert-danger">
      {{ session('error') }}
  </div>
@endif
<p class="p-3 mt-5 mw-100 w-75 mx-auto" >ユーザー情報</p>
<table border="1" class=" mw-100 w-75 mx-auto" >
  <tr>

    <th>No</th>
    <th>名前</th>
    <th>ログインID</th>
    <th>権限</th>
  </tr>
  @foreach($users as $key => $user)
  <?php $num = $key + 1; ?>
    <tr>
    <td>{{$num}}</td>
    <td>{{$user->name}}</td>
    <td>{{$user->login_id}}</td>
    <td>{{$roles[$key]}}</td>
    </tr>
  @endforeach
</table>
<table border="1" class="mt-5　mb-5 mw-100 w-75 mx-auto  " >
<p class="mt-5 mw-100 w-75 mx-auto" >勤怠申請</p>
  <tr>
        <th>日付</th>
        <th>開始予定日</th>
        <th>終了予定日</th>
        <th>開始打刻</th>
        <th>終了打刻</th>
        <th>休憩</th>
        <th>承認</th>
      </tr>
    @foreach($attendance as $value)
        <tr>
          <td>{{$value->month."/".$value->day}}</td>
          <td>{{$value->work_start}}</td>
          <td>{{$value->work_end}}</td>
          <td>{{ $value->punch_in }}</td>
          <td>{{ $value->punch_out }}</td>
          <td>{{$value->break_time}}</td>
          <td class="kinmu"><a href="{{ route('approval',['bfs' => $value->user_id ,'pasresf' => $value->punch_in] ) }}"><button  class="btn btn-dark btn-sm m-1">承認</button></a></td>
        </tr>
    @endforeach 
</table>
<br>
<br>
@endsection
