<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SETOnagi | セトナギ（新鮮な市場のお魚をかんたん仕入れ）</title>

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
<body>
    現在メンテナンス中です。
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

    $(function() {
      $(".start_btn01").click(function(){
        Swal.fire({
          title: '',
          html : '弊社とのお取引が初めてですか？<br />会員登録画面に移動します。',
          icon : 'warning',
          showCancelButton: true,
      	  cancelButtonText: 'いいえ',
          confirmButtonText: 'はい'
        }).then((result) => {
          if (result.value) {
            window.location.href = "{{ route('register') }}";
          }
        });
      });
      $(".start_btn02").click(function(){
        Swal.fire({
          title: '',
          html : '過去に弊社と取引がありますか？<br />お問い合わせ画面に移動します。',
          icon : 'warning',
          showCancelButton: true,
      	  cancelButtonText: 'いいえ',
          confirmButtonText: 'はい'
        }).then((result) => {
          if (result.value) {
            window.location.href = "{{ url('/welcomecontact') }}";
          }
        });
      });
    });
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
          $('a').click(function(){
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
