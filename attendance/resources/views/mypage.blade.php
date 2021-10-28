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
          <a href="{{ route('template',['id' => $id]) }}"><button  class="btn btn-dark">勤務表雛形をダウンロード</button></a>
        </td>
      </table>
      <table align="center" class="m-4 ml-5 w-75 border border-dark">
        <td align="center" class=" ">
          <p>時給：{{ $users[0]->pay }}円</p>
          <p>現在の給料：{{ number_format($total) }}円</p>
        </td>
      </table>
      @if( $users[0]->role > 0  && $users[0]->role <= 5 )
      <table  class="mr-3 mb-3" style="width:90%">
        <td>
        <a href="{{ route('admin') }}"><button  class="btn btn-info">申請一覧</button></a>
        </td>
      </table>
      @endif
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
            <th>日付</th>
            <th>開始予定日</th>
            <th>終了予定日</th>
            <th>開始打刻</th>
            <th>終了打刻</th>
            <th>休憩</th>
            <th>編集</th>
          </tr>
          @foreach($result as $value)
            @if($value->status == 1)
              <tr style="background-color: #ff6347;">
            @else
              <tr>
            @endif
                <td>{{$value->month."/".$value->day}}</td>
                <td>{{$value->work_start}}</td>
                <td>{{$value->work_end}}</td>
                <td><input type="text" value="{{ $value->punch_in }}" disabled></td>
                <td><input type="text" value="{{ $value->punch_out }}" disabled></td>
                <td>{{$value->break_time}}</td>
                <td class="kinmu"><button type="button" name="request" class="btn btn-dark btn-sm m-1" value="0"><a href="#"></a>修正</button></td>
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