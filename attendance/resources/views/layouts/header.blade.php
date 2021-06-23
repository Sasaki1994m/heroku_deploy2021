 <!-- <div class="main_header" > -->
  <header >
    <h1 class="header_title">
        <a href="{{ url('/list') }}">KiNtai</a>
    </h1>
  <nav>
    <ul>
    <!-- ユーザーIDを取得 -->
    <?php $auth = Auth::id(); ?>
    @if (Route::has('login'))
      <div class="top-right links">
        @auth
          <div class="btn-group">
            <button type="button" class="btn dropdown-toggle mt-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
              メニュー
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="{{route('list')}}">トップページ</a>
              <a class="dropdown-item" href="{{route('mypage.index',['id' => $auth] )}}">マイページ</a>
              <a class="dropdown-item" href="{{route('logout')}}">ログアウト</a>
            </div>
          </div>
          @else
          <li class="header_item">
            <a href="{{ route('login') }}">ログイン</a>
          </li>
          <!-- @if (Route::has('register'))
          <li class="header_item">
            <a href="{{ route('register') }}">新規登録</a>
          </li>
          @endif -->
        @endauth
      </div>
    @endif
      <!-- <li class="header_item"><a href=""></a>ログイン</li>
      <li class="header_item"><a href=""></a>新規登録</li> -->
    </ul>
  </nav>
  </header>
 <!-- </div> -->