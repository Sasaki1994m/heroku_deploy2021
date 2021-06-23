@extends('layouts.app')

@section('content')
{{--バリデーションエラー処理 --}}
@if (session('status'))
    <div class="alert alert-danger">
        {{ session('status') }}
    </div>
@endif
<?php $auth = Auth::id(); ?>
<div class="container">
            <div class="content m-4 mb-auto">
                <div class="title"></div>
                <h4>取得したいデータを選択してください</h4>
                <div class="row">
                    <div class="col-md-6">
                    </div>
                </div>
                <form role="form" method="POST" action="" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <?= $year_data; ?>
                    <select name="month" >
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                    <div class="form-group">
                        <br>
                        <button type="submit" class="btn btn-default btn-success">取得</button>
                        <button type="button" onclick="history.back()" class="btn btn-info" >戻る</button>
                    </div>
                    <input type="hidden" name="user_id" value="<?= $auth; ?>" >
                </form> 
            </div>
</div>
@endsection