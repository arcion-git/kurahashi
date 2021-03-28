<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

    <!-- Styles -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <!-- CSS Libraries -->
    <link href="{{ asset('css/selectric.css') }}" rel="stylesheet" >

    <!-- Template CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" >
    <link href="{{ asset('css/components.css') }}" rel="stylesheet" >


</head>
<body>


  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
          </ul>
        </form>

        <ul class="navbar-nav navbar-right">
          <li class="dropdown dropdown-list-toggle">
            <a id="toggle" href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle">
              <i class="fas fa-shopping-cart"></i>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
              <div class="dropdown-header">ショッピングカート
                <!-- <div class="float-right">
                  <a href="#">カートの中身を全て削除</a>
                </div> -->
              </div>
              <div class="dropdown-list-content dropdown-list-message">
                <a href="#" class="dropdown-item dropdown-item-unread">
                  <div id="cart">
                  </div>
                </a>
              </div>
              <div class="dropdown-footer text-center">
                <a href="{{ url('/confirm') }}" class="">営業に問い合わせる <i class="fas fa-chevron-right"></i></a>　
                <a href="#" class="">商品を発注する <i class="fas fa-chevron-right"></i></a>
              </div>
            </div>
          </li>
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <img alt="image" src="{{ asset('img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">

              @if ( Auth::guard('user')->check() )
              <div class="d-sm-none d-lg-inline-block">{{ Auth::guard('user')->user()->last_name }} {{ Auth::guard('user')->user()->first_name }} 様</div>
              @endif

            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <!-- <div class="dropdown-title">Logged in 5 min ago</div> -->
              <a href="features-profile.html" class="dropdown-item has-icon">
                <i class="far fa-user"></i> プロフィール編集
              </a>
              <a href="{{ url('/deal') }}" class="dropdown-item has-icon">
                <i class="fas fa-bolt"></i> 取引一覧
              </a>
              <a href="features-activities.html" class="dropdown-item has-icon">
                <i class="fas fa-bolt"></i> LINEでお問い合わせ
              </a>
              <a href="features-settings.html" class="dropdown-item has-icon">
                <i class="fas fa-cog"></i> 配送先設定
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
   {{ __('Logout') }}
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>
            </div>
          </li>
        </ul>



      </nav>



      <div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo.png') }}" alt="logo" width="195" class="">
            </a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo2.png') }}" alt="logo" width="25" class="">
            </a>
          </div>
          <ul class="sidebar-menu">
              <li class="menu-header">商品カテゴリー</li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link" data-toggle="dropdown"><span>全ての商品</span></a>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"> <span>おすすめ商品</span></a>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"> <span>クラハシオリジナル</span></a>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"> <span>塩蔵・塩干し</span></a>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"> <span>鮮魚</span></a>
                <ul class="dropdown-menu">
                  <li><a class="nav-link" href="index-0.html">冷凍</a></li>
                  <li><a class="nav-link" href="index-0.html">冷凍加工</a></li>
                  <li><a class="nav-link" href="index-0.html">天然</a></li>
                  <li><a class="nav-link" href="index-0.html">養殖</a></li>
                </ul>
              </li>

              <li class="menu-header">特集カテゴリー</li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link"> <span>季節のおすすめ</span></a>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link"> <span>年末商品</span></a>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link"> <span>塩蔵・塩干し</span></a>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"> <span>訳あり</span></a>
                <ul class="dropdown-menu">
                  <li><a class="nav-link" href="index-0.html">冷凍</a></li>
                  <li><a class="nav-link" href="index-0.html">冷凍加工</a></li>
                  <li><a class="nav-link" href="index-0.html">天然</a></li>
                  <li><a class="nav-link" href="index-0.html">養殖</a></li>
                </ul>
              </li>

          </ul>
        </aside>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        @yield('content')
      </div>
      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; KURAHASHI
        </div>
        <!-- <div class="footer-right">
          2.3.0
        </div> -->
      </footer>
    </div>
  </div>




    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
    <script src="{{ asset('js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <!-- <script src="../node_modules/jquery-pwstrength/jquery.pwstrength.min.js"></script>
    <script src="../node_modules/selectric/public/jquery.selectric.min.js"></script> -->

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth-register.js') }}"></script>
    <script src="{{ asset('js/calculation.js') }}"></script>

</body>
</html>
