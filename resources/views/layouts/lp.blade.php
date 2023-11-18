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
    <link href="{{ asset('css/lp.css') }}" rel="stylesheet" >
    <link href="{{ asset('css/components.css') }}" rel="stylesheet" >


</head>
<body class="top_welcome">
  <div id="app">
    <div class="header_bg">
      <header>
        <div class="logo">
          <a href="{{ url('/') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}"><img src="{{ asset('img/logo.png') }}" /></a>
        </div>
        <ul class="menu pc">
          <li><a href="{{ url('/') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}#NEWS">新着情報</a></li>
          <li><a href="{{ url('/') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}#ABOUT">サービスについて</a></li>
          <li><a href="{{ url('/') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}#FUNCTION">機能紹介</a></li>
          <li><a href="{{ url('/') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}#PAY">決済方法</a></li>
          <li><a href="{{ url('/') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}#START">ご利用スタートの流れ</a></li>
          <?php if(!isset($_GET['type'])) { ?>
            <li class="register_btn"><a href="{{ route('register') }}">会員登録</a></li>
          <?php } else { ?>
            <li class="register_btn">
              <form action="{{ url('/user/register_c') }}" method="GET" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" id="type" name="type" value="<?php echo isset($_GET['type']) ? htmlspecialchars($_GET['type']) : ''; ?>">
                <button id="" type="submit" class="">会員登録</button>
              </form>
            </li>
          <?php } ?>
          <li class="login_btn"><a href="{{ route('login') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}">ログイン</a></li>
        </ul>
        <div id="nav-wrapper" class="nav-wrapper sp">
          <div class="hamburger" id="js-hamburger">
            <span class="hamburger__line hamburger__line--1"></span>
            <span class="hamburger__line hamburger__line--2"></span>
            <span class="hamburger__line hamburger__line--3"></span>
          </div>
          <nav class="sp-nav">
            <ul>
              <li><a href="{{ url('/') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}#NEWS">新着情報</a></li>
              <li><a href="{{ url('/') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}#ABOUT">サービスについて</a></li>
              <li><a href="{{ url('/') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}#FUNCTION">機能紹介</a></li>
              <li><a href="{{ url('/') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}#PAY">決済方法</a></li>
              <li><a href="{{ url('/') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}#START">ご利用スタートの流れ</a></li>
              <li><a href="{{ url('/welcomecontact') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}">お問い合わせ</a></li>
              <li class="register_btn"><a href="{{ route('register') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}">会員登録</a></li>
              <li class="login_btn"><a href="{{ route('login') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}">ログイン</a></li>
            </ul>
          </nav>
          <div class="black-bg" id="js-black-bg"></div>
         </div>
      </header>
    </section>
  </div>
  @yield('content')
  <footer id="colophon" class="">
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
                <li><a href="{{ url('/welcomeguide') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}">ご利用ガイド</a></li>
                <li><a href="{{ url('/welcomelow') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}">特商法取引に基づく表記</a></li>
                <li><a href="{{ url('/welcomeprivacypolicy') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}">個人情報保護方針</a></li>
              </ul>
            </div>
            <div class="footer_contact">
              <h3>お問い合わせ窓口<span>CONTACT</span></h3>
              <!-- <p><strong>TEL 084-941-3510</strong></p> -->
              <!-- <p><strong>TEL 080-2943-7978</strong></p>
              <p class="small">平日9：00～18：00（定休 土日祝）</p> -->
              <a href="{{ url('/welcomecontact') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}"><div class="btn navy">メールでお問い合わせ</div></a>
            </div>
          </div>
          <div class="flex-container external_link">
            <ul class="">
              <!-- <li><a href="https://www.kurahashi.co.jp/" target="_blank">株式会社クラハシオフィシャルサイト<img src="https://setonagi.net/wp-content/themes/welcart_basic-beldad-expo/assets/images/expo/footer_link.png"></a></li> -->
              <li><a href="http://u-midas.com/" target="_blank">株式会社U-midasオフィシャルサイト<img src="https://setonagi.net/wp-content/themes/welcart_basic-beldad-expo/assets/images/expo/footer_link.png"></a></li>
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth-register.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

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
        console.log(message);
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
    });
    </script>

    <script>
    $(function() {
      window.onload = function () {
          var nav = document.getElementById('nav-wrapper');
          var hamburger = document.getElementById('js-hamburger');
          var blackBg = document.getElementById('js-black-bg');

          hamburger.addEventListener('click', function () {
              nav.classList.toggle('open');
          });
          blackBg.addEventListener('click', function () {
              nav.classList.remove('open');
          });
      };

      $(document).ready(function(){
        var selected = $('#hjkjKbn').val();
        // alert('test');
        //     console.log(selected);
        if ( selected == '1' ) {
          $('#maekabu').show();
          $('#kojin').hide();
        }else if ( selected == '2' ){
          $('#kojin').show();
          $('#maekabu').hide();
        }else{
          $('#maekabu').hide();
          $('#kojin').hide();
        }
      });
      $(document).on("change", "#hjkjKbn", function() {
        var selected = $(this).val();
        // alert('test');
        //     console.log(selected);
        if ( selected == '1' ) {
          $('#maekabu').show();
          $('#kojin').hide();
        }else if ( selected == '2' ){
          $('#kojin').show();
          $('#maekabu').hide();
        }else{
          $('#maekabu').hide();
          $('#kojin').hide();
        }
      });
      $(document).on("change", "#unei_company_hjkjKbn", function() {
        var selected = $(this).val();
        // alert('test');
        //     console.log(selected);
        if ( selected === '1' ) {
          $('#unei_company_detail_houjinkaku').show();
        } else{
          $('#unei_company_detail_houjinkaku').hide();
        }
      });
      $('#unei_company').click(function(){
          if($('#unei_company').prop('checked')){
              $('#unei_company_detail').show();
              // alert('checked!');
          }else{
              $('#unei_company_detail').hide();
              // alert('not checked!');
          }
      });
      $('[name="sqssfKbn"]:radio').change( function() {
        if($('[id=その他]').prop('checked')){
              $('#soufu_detail').show();
        }else{
              $('#soufu_detail').hide();
        }
      });
    });
    </script>
</body>
</html>
