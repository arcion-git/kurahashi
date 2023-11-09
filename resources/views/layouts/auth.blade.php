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
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
            <div class="login-brand">
              <a href="{{ route('welcom', ['type' => request()->input('type')]) }}"><img src="{{ asset('img/logo.png') }}" alt="logo" width="300" class=""></a>
            </div>

            @yield('content')

            <div class="simple-footer">
              Copyright &copy; UMIDASU Co., Ltd. All rights reserved.
            </div>
          </div>
        </div>
      </div>
    </section>
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
