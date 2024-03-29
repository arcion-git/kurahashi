@extends('layouts.app')

@section('content')




  <section class="section">
    <div class="section-header">
      <h1>オーダー内容確認</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="/">HOME</a></div>
        <div class="breadcrumb-item">カート編集</div>
      </div>
    </div>


    <!-- <div class="input-group">
      <input type="text" name="nouhinbi[]" class="nouhinbi text-center form-control datepicker" value="2021-06-26">
    </div> -->



    <div class="section-body">
      <div class="invoice">
        <div class="invoice-print">


          <!-- <form action="{{ url('/change_store') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            <input name="order_id" value="1" />
            <input name="store_name" value="引野" />
            <input name="tokuisaki_name" value="ﾊﾛｰｽﾞ 水産" />
            <div class="float-right">
                <button type="submit" class="btn btn-warning">この内容で問い合わせる</button>
            </div>
          </form> -->


          <form id="cart_form" action="{{ url('/adddeal') }}" method="POST" name="cart_id" value="" class="form-horizontal">
            {{ csrf_field() }}
            <div class="row mt-4 order">
              <div class="col-md-12">
                <!-- <div class="section-title">オーダー内容</div> -->
                <div id="order"></div>

                @if(app('request')->input('uketori_siharai') == 'クレジットカード払い')
                @endif

              </div>
            </div>
            <div class="float-right approval_btn_div">
                <input type="hidden" name="token_api" id="token_api" value="{{app('request')->input('token_api')}}"/>
                @if($user->setonagi == 1)
                @else
                  @if($user->koushou == 1)
                  <!-- <button type="submit" name="adddeal_btn" class="btn btn-warning">この内容で問い合わせる</button> -->
                  @endif
                @endif
                <input type="hidden" name="addtype" value="{{$addtype}}" />
                <input type="hidden" name="show_favorite" value="{{$show_favorite}}" @if($show_favorite) checked @endif/>
                <input type="hidden" name="change_all_store" value="{{$change_all_store}}" />
                <input type="hidden" name="set_tokuisaki_name" value="{{$set_tokuisaki_name}}" />
                <input type="hidden" name="change_all_nouhin_yoteibi" value="{{$change_all_nouhin_yoteibi}}" />
                <input type="hidden" name="current_time" value="{{$current_time}}" />
                <button type="submit" name="addsuscess_btn" value="1" id="addsuscess_btn" class="btn btn-success">この内容で注文する</button>
            </div>
          </form>
          <div class="float-right approval_btn_div">
            <form id="cart_form" action="{{ url('/confirm') }}" method="GET" name="cart_id" value="" class="form-horizontal">
              {{ csrf_field() }}
              <input type="hidden" name="addtype" value="{{$addtype}}" />
              <input type="hidden" name="show_favorite" value="{{$show_favorite}}" @if($show_favorite) checked @endif/>
              <input type="hidden" name="change_all_store" value="{{$change_all_store}}" />
              <input type="hidden" name="set_tokuisaki_name" value="{{$set_tokuisaki_name}}" />
              <input type="hidden" name="change_all_nouhin_yoteibi" value="{{$change_all_nouhin_yoteibi}}" />
              <button id="back_confirm" type="submit" name="" class="btn btn-info">戻って編集する</button>
            </div>
          </div>
          <br style="clear:both;" />
          <p class="approval_message">※ブラウザの「戻る」ボタンを使うと情報の再入力が必要となります。<br />※注文後の内容変更は承れませんのであらかじめご了承ください。</p>
        </div>
      </div>
    </div>
  </section>


<script>
$(document).ready( function(){
  var addtype = '{{ $addtype }}';
  var show_favorite = '{{ $show_favorite }}';
  var store_name = '{{ $change_all_store }}';
  var tokuisaki_name = '{{ $set_tokuisaki_name }}';
  var nouhin_yoteibi = '{{ $change_all_nouhin_yoteibi }}'
  var url = 'approval';
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }, //Headersを書き忘れるとエラーになる
    url: location.origin + '/order',
    type: "POST", // GETメソッドで通信
    data: {
      'addtype': addtype,
      'show_favorite': show_favorite,
      'store_name': store_name,
      'tokuisaki_name': tokuisaki_name,
      'nouhin_yoteibi': nouhin_yoteibi,
      'url': url,
    },
    cache: false, // キャッシュしないで読み込み
    // 通信成功時に呼び出されるコールバック
    success: function (data) {
      $('#order').html(data);
    },
    // 通信エラー時に呼び出されるコールバック
    error: function () {
        alert("オーダー内容をアップデートできません。");
    }
  });
});

$(document).on("click", "#show_favorite", function() {
    order_update();
});
function order_update() {
  var params = new URLSearchParams(window.location.search);
  var addtype = '{{ $addtype }}';
  var url = window.location.href;
  var path = url.split('?')[0];
  var url = path.substr(path.lastIndexOf('/') + 1);

  var tokuisaki_name = $('#change_all_store option:selected').attr('id');
  var store_name = $('#change_all_store').val();
  var nouhin_yoteibi = $('#change_all_nouhin_yoteibi').val();

  // チェックボックスの状態を取得
  var isChecked = $('#show_favorite').prop('checked');
  // チェックが入っているかどうかを確認
  if (isChecked) {
    var show_favorite = 1;
  } else {
    var show_favorite = null;
  }

  $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }, //Headersを書き忘れるとエラーになる
      url: location.origin + '/order',
      type: "POST", // GETメソッドで通信
      data: {
        'addtype': addtype,
        'show_favorite': show_favorite,
        'url': url,
        'tokuisaki_name': tokuisaki_name,
        'store_name': store_name,
        'nouhin_yoteibi': nouhin_yoteibi,
      },
      cache: false, // キャッシュしないで読み込み
      // 通信成功時に呼び出されるコールバック
      success: function (data) {
        $('#order').html(data);
        // console.log(message.hasOwnProperty('message'));
        // if(message.hasOwnProperty('message')){
        //   var message = message.message;
        //   Swal.fire({
        //     title: message,
        //     position: 'center',
        //     toast: true,
        //     icon: 'info',
        //     showConfirmButton: false,
        //     timer: 3000
        //   });
        // }
      },
      // 通信エラー時に呼び出されるコールバック
      error: function () {
        Swal.fire({
          title: "商品が見つかりませんでした。",
          position: 'center',
          toast: true,
          icon: 'info',
          showConfirmButton: false,
          timer: 3000
        });
        // alert("オーダー内容をアップデートできません。");
      }
  });
}
$(document).ready(function() {
  $("#addsuscess_btn").click(function() {
    var submitButton = $(this);
    // 二重送信を防止するためにボタンを無効化
    submitButton.addClass("disabled_btn");
    $('#cart_form').submit();
  });
});




$(document).ready(function() {
  // 初回ページ遷移時に履歴を追加
  history.pushState(null, null, null);

  // Safari向けにwindow.onpageshowイベントもリッスン
  window.addEventListener('pageshow', function (event) {
    // event.persisted が true の場合、ページがキャッシュから復元されたことを示す
    if (event.persisted) {
      setTimeout(showBackWarning, 0);
    }
  });

  // popstate イベントのリスナーを追加
  $(window).on("popstate", function(){
    // ブラウザの履歴を追加して警告を表示
    history.pushState(null, null, null);
    setTimeout(showBackWarning, 0);
  });

  function showBackWarning() {
    // 警告を表示
    Swal.fire({
      title: '前の画面に戻りますか？',
      html: 'クレジットカード決済を選択されている場合、情報の再入力が必要となります。',
      icon: 'warning',
      showCancelButton: true,
      cancelButtonText: '閉じる',
      confirmButtonText: '前の画面に戻る'
    }).then(function(result) {
      if (result.value) {
        // 確認ボタンがクリックされた場合、指定されたイベントをトリガー
        $('#back_confirm').trigger('click');
      }
    });
  }
});





// リロード時に警告を表示
// $(document).ready(function() {
//   let isReloading = false;
//   // ページがリロードされる前に警告を表示する
//   window.addEventListener("beforeunload", function (e) {
//     if (!isReloading) {
//       e.preventDefault(); // ブラウザのデフォルト動作を無効化
//       e.returnValue = "ページを離れますか？"; // メッセージを設定
//     }
//   });
//   // ページがリロードされた場合にフラグを設定
//   window.addEventListener("unload", function () {
//     isReloading = true;
//   });
// });

</script>


@endsection
