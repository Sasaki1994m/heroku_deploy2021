@extends('layouts.app')
@section('content')
  @auth
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
<div class="row mw-100 ">
    <div class="col-4">
      <table align="center" class="m-3 ml-5">
        <td align="center">
          <a href="{{ route('template',['id' => $id]) }}"><button  class="btn btn-dark">テンプレートをダウンロード</button></a>
        </td>
      </table>
    </div>
    <div class="col-8 p-3">
        <table align="center" class="mr-3 mb-3" style="width:90%">
          <td>
            <a href="{{ route('mypage.before',['id' => $id ,'month' => $month] ) }}"><button  class="btn btn-dark">先月へ</button></a>
            <a href="{{ route('mypage.next',['id' => $id ,'month' => $month] ) }}"><button  class="btn btn-dark">来月へ</button></a>
          </td>
          <td>
            <form action="{{ route('timestamps.store',['id' => Auth::user()->id]) }}" method="POST">
              @csrf
              <button class="btn btn-primary">出勤打刻</button>
            </form>
          </td>
          <td>
              <form action="{{ route('timestamps.update',['id' => Auth::user()->id]) }}" method="POST">
              @csrf
              <button class="btn btn-primary">退勤打刻</button>
            </form>
          </td>
          <td>
            <a href="{{ route('inport') }}"><button type="button" class="btn btn-success">勤務表を登録</button></a>
            <a href="{{ route('export') }}"><button class="btn btn-success">勤務データを出力</button></a>
          </td>
        </table>
        <table border="1" align="center" class="mb-5" style="width:90%" >
          <tr>
            <th>編集</th>
            <th>日付</th>
            <th>開始予定日</th>
            <th>終了予定日</th>
            <th>開始打刻</th>
            <th>終了打刻</th>
            <th>休憩</th>
          </tr>
        @foreach($result as $value)
          <tr>
          <td></td>
          <td>{{$value->month."/".$value->day}}</td>
          <td>{{$value->work_start}}</td>
          <td>{{$value->work_end}}</td>
          <td>{{$value->punch_in}}</td>
          <td>{{$value->punch_out}}</td>
          <td>{{$value->break_time}}</td>
          </tr>
        @endforeach
        </table>
        @if ($result->isEmpty())
        <div class="text-center pt-5" >
          <p><strong>{{$msg}}</strong></p>
        </div>
        @endif
    </div>
</div>
  @endauth
@endsection