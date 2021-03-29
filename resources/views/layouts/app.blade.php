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
          @if ( Auth::guard('user')->check() )
          <li class="dropdown dropdown-list-toggle">
            <a id="toggle" href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle">
              <i class="fas fa-shopping-cart"></i>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
              <div class="dropdown-header">ショッピングカート</div>
              <div class="dropdown-list-content dropdown-list-message">
                <a href="#" class="dropdown-item dropdown-item-unread">
                  <div id="cart"></div>
                </a>
              </div>
              <div class="dropdown-footer text-center">
                <a href="{{ url('/confirm') }}" class="">営業に問い合わせる <i class="fas fa-chevron-right"></i></a>　
                <!-- <a href="#" class="">商品を発注する <i class="fas fa-chevron-right"></i></a> -->
              </div>
            </div>
          </li>
          @endif
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <img alt="image" src="{{ asset('img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">

              @if ( Auth::guard('user')->check() )
              <div class="d-sm-none d-lg-inline-block">{{ Auth::guard('user')->user()->last_name }} {{ Auth::guard('user')->user()->first_name }} 様</div>
              @endif

            </a>
            <div class="dropdown-menu dropdown-menu-right">

              @if ( Auth::guard('user')->check() )
              <!-- <div class="dropdown-title">Logged in 5 min ago</div> -->
              <!-- <a href="features-profile.html" class="dropdown-item has-icon">
                <i class="far fa-user"></i> プロフィール編集
              </a> -->
              <a href="{{ url('/deal') }}" class="dropdown-item has-icon">
                <i class="fas fa-user"></i> 取引一覧
              </a>
              <!-- <a href="features-activities.html" class="dropdown-item has-icon">
                <i class="fas fa-bolt"></i> LINEでお問い合わせ
              </a>
              <a href="features-settings.html" class="dropdown-item has-icon">
                <i class="fas fa-cog"></i> 配送先設定
              </a> -->
              <div class="dropdown-divider"></div>
              @endif
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                {{ __('Logout') }}
              </a>

              @if ( Auth::guard('user')->check() )
              <form id="logout-form" action="/user/logout" method="POST" style="display: none;">
                  @csrf
              </form>
              @endif
              @if ( Auth::guard('admin')->check() )
              <form id="logout-form" action="/admin/logout" method="POST" style="display: none;">
                  @csrf
              </form>
              @endif


            </div>
          </li>
        </ul>



      </nav>



      <div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            @if ( Auth::guard('user')->check() )
            <a href="{{ url('/') }}">
            @endif
            @if ( Auth::guard('admin')->check() )
            <a href="{{ url('/admin/home') }}">
            @endif
                <img src="{{ asset('img/logo.png') }}" alt="logo" width="195" class="">
            </a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo2.png') }}" alt="logo" width="25" class="">
            </a>
          </div>


          @if ( Auth::guard('admin')->check() )
          <ul class="sidebar-menu">
              <li class="nav-item">
                <a href="/admin/home" class="nav-link"><span>取引一覧</span></a>
              </li>
              <li class="nav-item dropdown">
                <a href="/admin/user" class="nav-link"><span>顧客一覧</span></a>
              </li>
              <li class="nav-item">
                <a href="/itemadd" class="nav-link"><span>商品登録</span></a>
              </li>
              <li class="nav-item dropdown">
                <a href="/download" class="nav-link"><span>CSVデータダウンロード</span></a>
              </li>
          </ul>
          @endif


          @if ( Auth::guard('user')->check() )
          <ul class="sidebar-menu">
            <li class="nav-item">
              <a href="{{ url('/') }}" class="nav-link"><span>すべての商品</span></a>
            </li>
          @foreach($categories as $category)
            <li class="nav-item">
              <a href="/category/{{$category->id}}" class="nav-link"><span>{{$category->category_name}}</span></a>
            </li>
          @endforeach
          </ul>
          @endif

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
    <!-- <script src="{{ asset('js/realtime.js') }}"></script> -->


</body>
</html>
