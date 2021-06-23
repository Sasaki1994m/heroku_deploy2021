@extends('layouts.app')

@section('content')
{{--バリデーションエラー処理 --}}
@if (session('status'))
    <div class="alert alert-danger">
        {{ session('status') }}
    </div>
@endif
<div class="container">
            <div class="content m-4 mb-auto">
                <div class="title"></div>
                <h4>CSVファイルを選択してください</h4>
                <div class="row">
                    <div class="col-md-6">
                    </div>
                </div>
                <form role="form" method="POST" action="" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="file" name="csv_file" id="csv_file">
                    <div class="form-group">
                        <br>
                        <button type="submit" class="btn btn-default btn-success">登録</button>
                        <button type="button" onclick="history.back()" class="btn btn-info" >戻る</button>
                    </div>
                </form> 
            </div>
</div>
@endsection