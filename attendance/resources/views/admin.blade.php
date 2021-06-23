@extends('layouts.app')

@section('content')
<table border="1" align="center" class="mt-5" style="width:70%" >
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
@endsection
