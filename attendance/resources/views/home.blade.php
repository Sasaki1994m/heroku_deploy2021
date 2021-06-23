@extends('layouts.app')
@section('content')
    <body>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
            <div class="content"> 
                <div class="login_top">
                勤怠管理システム
                </div>
            </div>
    </body>
@endsection