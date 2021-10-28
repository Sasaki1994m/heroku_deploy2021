@extends('layouts.app')

@section('content')

<body class="bk_color">
<div class="form-wrapper">
        <h2>Sign In</h2>
        <form method="POST" action="{{ route('login') }}">
        @csrf
          <div class="form-item">
            <label for="login_id"></label>
            <input id="login_id" type="login_id" class="{{ $errors->has('login_id') ? ' is-invalid' : '' }}" name="login_id" value="{{ old('login_id') }}" required autocomplete="login_id" autofocus placeholder="Login id">
            @if ($errors->has('login_id'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('login_id') }}</strong>
                </span>
            @endif
        </div>
          <div class="form-item">
            <label for="password"></label>
            <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
          <div class="button-panel">
            <input type="submit" class="button" title="Sign In" value="Sign In"></input>
          </div>
        </form>
        <div class="form-footer">
          <!-- <p><a href="#">Create an account</a></p> -->
          <!-- <p><a href="#">Forgot password?</a></p> -->
        </div>
    </div>
</body>
@endsection
