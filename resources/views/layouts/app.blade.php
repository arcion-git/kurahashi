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

    <!-- jQUERY -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <!-- jquery-ui -->
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <!-- datepicker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
    <!-- <script src="{{ asset('js/daterangepicker.js') }}"></script> -->



    <!-- <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}"> -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ja.min.js"></script>





    <!-- Styles -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <!-- CSS Libraries -->
    <link href="{{ asset('css/selectric.css') }}" rel="stylesheet" >

    <!-- Template CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" >
    <link href="{{ asset('css/components.css') }}" rel="stylesheet" >
    <!-- <link href="{{ asset('css/daterangepicker.css') }}" rel="stylesheet" > -->


</head>
<body>



  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">

        @if ( Auth::guard('user')->check() )
        <form id="saveform" action="{{ url('/search') }}" enctype="multipart/form-data" method="POST" class="form-inline mr-auto">
          @csrf
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <!-- <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li> -->
          </ul>



          <div class="search-element">
            {{--
			      <select name="cat" id="cat" class="postform">
              @if(isset($search_category_parent))<option value="{{$search_category_parent}}">{{$search_category_parent}}</option>@endif
              @if(isset($search_category))<option value="{{$search_category->category_id}}">{{$search_category->category_name}}</option>@endif
            	<option value="-1">すべて</option>
                @foreach($categories as $key=>$vals)
                <option class="level-0" value="{{$key}}">{{$key}}</option>
                    @foreach($vals as $val)
                      @if($val->items->count() == 0)
                      @else
                      <option class="level-1" value="{{$val->category_id}}">&nbsp;&nbsp;&nbsp;{{ $val->category_name }} （{{ $val->items->count() }}）</option>
                      @endif
                    @endforeach
                  </ul>
                </li>
                @endforeach
            </select>
            <input class="form-control" type="text" name="search" placeholder="検索" aria-label="Search" data-width="250" style="width: 250px;" value="@if(isset($search)){{$search}}@endif">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>

            <!-- <div class="search-backdrop"></div>
            <div class="search-result">
              <div class="search-header">
                Histories
              </div>
              <div class="search-item">
                <a href="#">How to hack NASA using CSS</a>
                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
              </div>
              <div class="search-item">
                <a href="#">Kodinger.com</a>
                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
              </div>
              <div class="search-item">
                <a href="#">#Stisla</a>
                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
              </div>
              <div class="search-header">
                Result
              </div>
              <div class="search-item">
                <a href="#">
                  <img class="mr-3 rounded" width="30" src="assets/img/products/product-3-50.png" alt="product">
                  oPhone S9 Limited Edition
                </a>
              </div>
              <div class="search-item">
                <a href="#">
                  <img class="mr-3 rounded" width="30" src="assets/img/products/product-2-50.png" alt="product">
                  Drone X2 New Gen-7
                </a>
              </div>
              <div class="search-item">
                <a href="#">
                  <img class="mr-3 rounded" width="30" src="assets/img/products/product-1-50.png" alt="product">
                  Headphone Blitz
                </a>
              </div>
              <div class="search-header">
                Projects
              </div>
              <div class="search-item">
                <a href="#">
                  <div class="search-icon bg-danger text-white mr-3">
                    <i class="fas fa-code"></i>
                  </div>
                  Stisla Admin Template
                </a>
              </div>
              <div class="search-item">
                <a href="#">
                  <div class="search-icon bg-primary text-white mr-3">
                    <i class="fas fa-laptop"></i>
                  </div>
                  Create a new Homepage Design
                </a>
              </div>
            </div> -->
            --}}
          </div>
        </form>

        @endif

        @if ( Auth::guard('admin')->check() )
        <form id="saveform" action="{{ url('/admin/search') }}" enctype="multipart/form-data" method="POST" class="form-inline mr-auto">
          @csrf
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
          </ul>
          <!-- <div class="search-element">
			      <select name="cat" id="cat" class="postform">
            	<option value="-1">すべての取引</option>
              <option class="level-0" value="交渉中">交渉中</option>
              <option class="level-0" value="受注済">受注済</option>
              <option class="level-0" value="キャンセル">キャンセル</option>
            </select>
            <input class="form-control" type="text" name="search" placeholder="検索" aria-label="Search" data-width="250" style="width: 250px;" value="@if(isset($search)){{$search}}@endif">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
          </div> -->
        </form>
        @endif

        @if ( Auth::guard('admin')->check() )
        <!-- <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
          </ul>
        </form> -->
        @endif

        <!-- <div class="float-right">
          <form id="saveform" action="{{ url('/') }}" enctype="multipart/form-data" method="POST" class="form-horizontal">
            @csrf
            <div class="input-group">
              <input type="text" name="search" class="form-control" placeholder="検索">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
              </div>
            </div>
          </form>
        </div> -->

        <ul class="navbar-nav navbar-right">
          @if ( Auth::guard('user')->check() )
          <li class="dropdown dropdown-list-toggle">
            <a id="toggle" href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle">
              <i class="fas fa-shopping-cart"></i>
            </a>
            <div id="cart-container" class="dropdown-menu dropdown-list dropdown-menu-right">
              <div class="dropdown-header">ショッピングカート</div>
              <div class="dropdown-list-content dropdown-list-message">
                <a href="#" class="dropdown-item dropdown-item-unread">
                  <div id="cart"></div>
                </a>
              </div>
              <div class="dropdown-footer text-center">
                <a href="{{ url('/confirm') }}" class="btn btn-warning">注文個数入力に進む <i class="fas fa-chevron-right"></i></a>　
                <!-- <a href="" class="btn btn-success">このまま商品を発注する <i class="fas fa-chevron-right"></i></a>　 -->
              </div>
            </div>
          </li>
          @endif
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <img alt="image" src="{{ asset('img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
              @if ( Auth::guard('admin')->check() )
              <div class="d-sm-none d-lg-inline-block">{{ Auth::guard('admin')->user()->name }}</div>
              @endif
              @if ( Auth::guard('user')->check() )
              <div class="d-sm-none d-lg-inline-block">{{ Auth::guard('user')->user()->name }} 様</div>
              @endif
            </a>
            <div class="dropdown-menu dropdown-menu-right">

              @if ( Auth::guard('user')->check() )
              <!-- <div class="dropdown-title">Logged in 5 min ago</div> -->
              <!-- <a href="features-profile.html" class="dropdown-item has-icon">
                <i class="far fa-user"></i> プロフィール編集
              </a> -->
              <a href="{{ url('/deal') }}" class="dropdown-item has-icon">
                <i class="fa fa-clipboard-list"></i> 取引一覧
              </a>
              <a href="{{ url('/favoriteitem') }}" class="dropdown-item has-icon">
                <i class="far fa-heart"></i> お気に入り商品一覧
              </a>
              <a href="{{ url('/repeatorder') }}" class="dropdown-item has-icon">
                <i class="fa fa-redo-alt"></i> リピートオーダー
              </a>
              <!-- <a href="{{ url('/deal') }}" class="dropdown-item has-icon">
                <i class="far fa-user"></i> ×ご提案商品
              </a> -->
              {{--
              <a href="{{ url('/favorite') }}" class="dropdown-item has-icon">
                <i class="far fa-heart"></i> お気に入り編集
              </a>
              --}}
              <a href="{{ url('/contact') }}" class="dropdown-item has-icon">
                <i class="far fa-comments"></i> お問い合わせフォーム
              </a>
              <a href="{{ url('/guide') }}" class="dropdown-item has-icon">
                <i class="fa fa-map-signs"></i> ご利用ガイド
              </a>
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
            <a href="{{ url('/bulk') }}">
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
                <a href="/admin/home" class="nav-link"><i class="fa fa-clipboard-list"></i><span>取引一覧</span></a>
              </li>
              <li class="nav-item">
                <a href="/admin/user" class="nav-link"><i class="fa fa-redo-alt"></i><span>リピートオーダー</span></a>
              </li>
              <li class="nav-item">
                <a href="/admin/user" class="nav-link"><i class="far fa-user"></i><span>ご提案商品</span></a>
              </li>
              <li class="nav-item">
                <a href="/admin/user" class="nav-link"><i class="fa fa-users"></i><span>顧客担当者一覧</span></a>
              </li>
              <li class="nav-item">
                <a href="/admin/buyer" class="nav-link"><i class="fa fa-users"></i><span>得意先一覧</span></a>
              </li>
              <li class="nav-item">
                <a href="/admin/setonagiuser" class="nav-link"><i class="fa fa-users"></i><span>セトナギユーザー審査</span></a>
              </li>
              <li class="nav-item">
                <a href="/admin/recommendcategory" class="nav-link"><i class="fa fa-bullhorn"></i><span>カテゴリーのおすすめ</span></a>
              </li>
              <li class="nav-item">
                <a href="/admin/item" class="nav-link"><i class="fas fa-list"></i><span>商品一覧</span></a>
              </li>
              <!-- <li class="nav-item">
                <a href="/admin/home" class="nav-link"><span>×社内営業</span></a>
              </li> -->
              <li class="nav-item">
                <a href="/admin/imgupload" class="nav-link"><i class="fas fa-file-image"></i><span>商品画像登録</span></a>
              </li>
              @if(Auth::guard('admin')->user()->kengen == 1)
              <li class="nav-item">
                <a href="/admin/csv" class="nav-link"><i class="fas fa-file-csv"></i><span>CSVインポート</span></a>
              </li>
              <li class="nav-item">
                <a href="/admin/download" class="nav-link"><i class="fas fa-file-csv"></i><span>CSVダウンロード</span></a>
              </li>
              @endif
              <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                  <i class="fas fa-sign-out-alt"></i><span>{{ __('Logout') }}</span>
                </a>
              </li>
              <!-- <li class="nav-item dropdown">
                <a href="/admin/download" class="nav-link"><span>CSVダウンロード</span></a>
              </li> -->
          </ul>
          @endif


          @if ( Auth::guard('user')->check() )
          <ul class="sidebar-menu">

            <li class="menu-header">メニュー</li>
            <li class="nav-item">
              <a href="{{ url('/setonagi') }}" class="nav-link"><i class="fas fa-check"></i><span>限定お買い得商品</span></a>
            </li>

            <!-- <li class="nav-item">
              <a href="{{ url('/') }}" class="nav-link"><i class="fas fa-list"></i><span>すべての商品</span></a>
            </li> -->
            <li class="nav-item">
              <a href="{{ url('/recommend') }}" class="nav-link"><i class="far fa-user"></i><span>担当のおすすめ商品</span></a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/special_price') }}" class="nav-link"><i class="fas fa-fire"></i><span>市況商品（時価）</span></a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/favoriteitem') }}" class="nav-link"><i class="far fa-heart"></i><span>お気に入り商品</span></a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/firstguide') }}" class="nav-link" style="font-weight:bold;"><i class="far fa-flag"></i><span>はじめてのお客様へ</span></a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/guide') }}"  class="nav-link"><i class="fa fa-map-signs"></i><span>ご利用ガイド<span></a>
            </li>


{{--

            <li class="nav-item dropdown active">
              <a href="#" class="nav-link has-dropdown">
                <i class="fas fa-heart"></i></i><span>お気に入りカテゴリ</span>
              </a>
              <ul class="dropdown-menu">
              @if($favorite_categories)
              @foreach($favorite_categories as $favorite_category)
                      @if($favorite_category->category->items->count() == 0)
                      @else
                      <li class="nav-item"><a class="nav-link" href="/category/{{$favorite_category->category->category_id}}"><span>{{ $favorite_category->category->category_name }}</span>（{{ $favorite_category->category->items->count() }}）</a></li>
                      @endif
              @endforeach
              @endif
              </ul>
            </li>


            <!-- <li class="menu-header">特集カテゴリ</li>
            <li class="nav-item mb-1">
              <a href="{{ url('/setonagi') }}" class="nav-link"><img src="https://setonagi.net/wp-content/themes/welcart_basic-beldad/assets/images/top-assets/setonagi_top36.jpg" width="100%"/></a>
            </li>
            <li class="nav-item mb-1">
              <a href="{{ url('') }}/category/8193" class="nav-link"><img src="https://setonagi.net/wp-content/themes/welcart_basic-beldad/assets/images/top-assets/setonagi_top17.jpg" width="100%"/></a>
            </li>
            <li class="nav-item mb-1">
              <a href="{{ url('/setonagi') }}" class="nav-link"><img src="https://setonagi.net/wp-content/themes/welcart_basic-beldad/assets/images/top-assets/setonagi_top19.jpg" width="100%"/></a>
            </li>
            <li class="nav-item mb-1">
              <a href="{{ url('') }}/category/2908" class="nav-link"><img src="https://setonagi.net/wp-content/themes/welcart_basic-beldad/assets/images/top-assets/setonagi_top20.jpg" width="100%"/></a>
            </li> -->

            <li class="menu-header">カテゴリ一覧</li>


            @foreach($categories as $key=>$vals)
            <li class="nav-item dropdown active">
              <a href="#" class="nav-link has-dropdown">
                <i class="fas fal-circle"></i><span>{{$key}}</span>
              </a>
              <ul class="dropdown-menu">
                @foreach($vals as $val)
                      @if($val->items->count() == 0)
                      @else
                      <li class="nav-item"><a class="nav-link" href="/category/{{$val->category_id}}"><span>{{ $val->category_name }}</span>（{{ $val->items->count() }}）</a></li>
                      @endif
                @endforeach
              </ul>
            </li>
            @endforeach
            --}}


            <li class="nav-item nav_banner"><a target="_blank" href="https://setonagi.net/column"><img class="" src="https://setonagi.net/wp-content/themes/welcart_basic-beldad/assets/images/top-assets/setonagi_top53.jpg" /></a></li>
        		<li class="nav-item nav_banner"><a href="https://www.youtube.com/c/KurahashiCoJp" target="_blank"><img class="" src="{{ asset('img/kurahashi_channel.jpg') }}" /></a>
        <?php
        $xml = simplexml_load_file('https://www.youtube.com/feeds/videos.xml?channel_id=UCJpBCLAQ00jMf8zzdEW3x5A');
        if( $xml !== false):
        $count = 0;
        foreach($xml as $item){
        	if($item->id) {
        		$title = $item->title;
        		$id = $item->children('yt', true)->videoId[0];
        		$html = '<a href="https://www.youtube.com/watch?v='.$id.'" target="_blank"><img src="https://i1.ytimg.com/vi/'.$id.'/hqdefault.jpg"><br>';
        		echo $html;
        		$count++;
        	}
        	if($count >= 1) {
        		break;
        	}
        }
        endif;
        ?>
        </li>
		<li class="nav-item nav_banner"><a href="https://zeitaku-shiko.com/" target="_blank"><img class="" src="https://setonagi.net/wp-content/themes/welcart_basic-beldad/assets/images/top-assets/setonagi_top26.jpg" /></a></li>


          </ul><br /><br /><br /><br /><br />
          @endif
        </aside>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        @yield('content')
      </div>
      <!-- <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; KURAHASHI
        </div>
        <div class="footer-right small">
          <span class="" ></span>運営：<a href="https://www.kurahashi.co.jp/" target="_blank">株式会社クラハシ</a></span>
          <span class="">開発：<a href="http://u-midas.com/" target="_blank">株式会社U-midas</a></span>
        </div>
      </footer> -->

      @if ( Auth::guard('user')->check() )
      <footer id="colophon" class="main-footer">
  			<div class="inner">
  				<div class="bottom">
  					<div class="column_footer">
  						<div class="flex-container">
  							<div class="footer_logo">
  								<img src="{{ asset('img/logo-w.png') }}">
  								<!-- <p>【運営元】<br><strong>株式会社クラハシ</strong><br>〒721-0942広島県福山市引野町1-1-1<br>福山市地方卸売市場内</p> -->
  								<p>【運営元】<br><strong>株式会社U-midas</strong><br>広島県三原市糸崎７丁目８番２２号</p>
  							</div>
  							<div class="footer_menu">
  								<ul class="">
  									<li><a href="{{ url('/guide') }}">ご利用ガイド</a></li>
  									<li><a href="{{ url('/law') }}">特商法取引に基づく表記</a></li>
  									<li><a href="{{ url('/privacypolicy') }}">個人情報保護方針</a></li>
  								</ul>
  							</div>
  							<div class="footer_contact">
  								<h3>お問い合わせ窓口<span>CONTACT</span></h3>
  								<!-- <p><strong>TEL 084-941-3510</strong></p> -->
  								<p><strong>TEL 080-2943-7978</strong></p>
  								<p class="small">平日9：00～18：00（定休 土日祝）</p>
  								<a href="{{ url('/contact') }}"><div class="btn navy">お問い合わせフォーム</div></a>
  							</div>
  						</div>
  						<div class="flex-container external_link">
  							<ul class="">
  								<!-- <li><a href="https://www.kurahashi.co.jp/" target="_blank">株式会社クラハシオフィシャルサイト<img src="https://setonagi.net/wp-content/themes/welcart_basic-beldad-expo/assets/images/expo/footer_link.png"></a></li> -->
  								<li><a href="http://u-midas.com/" target="_blank">株式会U-midasフィシャルサイト<img src="https://setonagi.net/wp-content/themes/welcart_basic-beldad-expo/assets/images/expo/footer_link.png"></a></li>
  							</ul>
  						</div>
  						<div class="flex-container sns_link">
  							<ul class="">
  								<!-- <li><a href="https://twitter.com/U_midas" target="_blank"><img src="https://setonagi.net/wp-content/themes/welcart_basic-beldad-expo/assets/images/expo/footer_twitter.png"></a></li> -->
  								<li><a href="https://www.youtube.com/c/KurahashiCoJp" target="_blank"><img src="https://setonagi.net/wp-content/themes/welcart_basic-beldad-expo/assets/images/expo/footer_youtube.png"></a></li>
  							</ul>
  							<p class="copyright">© UMIDASU Co., Ltd.  All rights reserved.</p>
  						</div>
  						</div><!-- .flex-container -->
  					</div><!-- .column1070 -->
  				</div>
  		</footer>
      @endif

    </div>
    <div id="overlayajax">
      <div class="cv-spinner">
        <span class="spinner"></span>
      </div>
    </div>
  </div>




    <!-- General JS Scripts -->

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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth-register.js') }}"></script>
    <script src="{{ asset('js/calculation.js') }}"></script>

    <script>
    //ＵＲＬのパラメータを取得するための関数
    function getUrlParam(param){
        var pageUrl = window.location.search.substring(1);
        var urlVar = pageUrl.split('&');
        for (var i = 0; i < urlVar.length; i++)
        {
            var paramName = urlVar[i].split('=');
            if (paramName[0] == param)
            {
                return decodeURI(paramName[1]);
            }
        }
    }
    $(function() {
        var message = getUrlParam('message');
        var icon = getUrlParam('icon');
        console.log(message);
        console.log(icon);
        if (message) {
          Swal.fire({
            html: message,
            // position: 'top-end',
            // toast: true,
            icon: 'warning',
            showConfirmButton: false,
            // timer: 1500
          });
        }
        if (icon) {
          Swal.fire({
            html: message,
            // position: 'top-end',
            // toast: true,
            icon: 'success',
            showConfirmButton: false,
            // timer: 1500
          });
        }
    });
    </script>
    <!-- <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.3.1/jquery.cookie.min.js"></script>
    <script src="{{ asset('js/jquery.utility-kit.js') }}"></script> -->
    <!-- <script src="{{ asset('js/realtime.js') }}"></script> -->

    @if ( Auth::guard('admin')->check() )
    <style>
    .navbar-bg {
        background-color: #00A8D9;
    }
    </style>
    @endif


    @if ( Auth::guard('user')->check() )
    @if ( Auth::guard('user')->user()->setonagi == 1)
    @else
    <style>
    .navbar-bg {
        background-color: #1D2C6B;
    }
    </style>
    @endif
    @endif



</body>
</html>
