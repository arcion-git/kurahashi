@extends('layouts.app')

@section('content')




  <section class="section">
    <div class="section-header">
      <h1>カート編集</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="/">HOME</a></div>
        <div class="breadcrumb-item">カート編集</div>
      </div>
    </div>


    <div class="section-body">
      <div class="invoice">
        <div class="invoice-print">

          <!-- <form action="{{ url('/adddeal') }}" method="POST" class="form-horizontal"> -->
          <form action="{{ url('/approval') }}" method="GET" class="form-horizontal">
            {{ csrf_field() }}

            @if(isset($setonagi))
            <div class="row mt-4 order">
              <div class="col-md-12">
                <div class="section-title">只今ご注文いただいた場合の商品受け渡しは{{$today_plus}}です。</div>
              </div>
            </div>
            @endif

            <div class="row mt-4 order">
              <div class="col-md-12">
                <div class="section-title">オーダー内容</div>
                <div id="order"></div>
              </div>
            </div>


            <div class="float-right">
                <button id="approval_btn" type="submit" class="btn btn-warning">内容確認画面に進む</button>
                <div id="card_approval_btn" class="btn btn-warning" onclick="executePay">内容確認画面に進む</div>
            </div>
          </form>


          <br style="clear:both;" />

        </div>
      </div>
    </div>
  </section>

<script>
$(function(){
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
      var nokori_zaiko = getUrlParam('nokori_zaiko');
      var nokori_zaiko = -(nokori_zaiko);
      var item_name = getUrlParam('item_name');
      var message = getUrlParam('message');
      if (nokori_zaiko) {
        console.log(nokori_zaiko);
        console.log(item_name);
        Swal.fire({
          html: item_name + ' は在庫が ' + nokori_zaiko + ' 不足しています。<br />数量を変更・もしくは削除してください。',
          // position: 'top-end',
          // toast: true,
          icon: 'warning',
          showConfirmButton: false,
          // timer: 1500
        });
      }
      if (message) {
        Swal.fire({
          html: 'かけ払い決済エラーのため別の決済方法をお試しください。',
          // position: 'top-end',
          // toast: true,
          icon: 'warning',
          showConfirmButton: false,
          // timer: 1500
        });
      }
  });
});


</script>

@endsection
