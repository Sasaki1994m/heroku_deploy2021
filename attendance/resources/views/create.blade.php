@extends('layouts.app')

@section('content')
{{--バリデーションエラー処理 --}}
@if (session('status'))
    <div class="alert alert-danger">
        {{ session('status') }}
    </div>
@endif
<center>
<p class="times" id="RealtimeClockArea"></p>
<div class="container">
  <table>
    <td>
      <form action="{{ route('timestamps.store',['user_id' => Auth::user()->id]) }}" method="POST">
      @csrf
        <button type="submit" >出勤</button>
      </form>
    </td>
    <td>
      <form action="{{ route('timestamps.update',['user_id' => Auth::user()->id]) }}" method="POST">
      @csrf
        <button type="submit" >退勤</button>
      </form>
    </td>
  </table>
</div>
</center>

@endsection