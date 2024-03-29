@extends('layouts.app')

@section('content')



  <section class="section">
    <div class="section-header">
      <h1>オーダー内容確認
        @if ($addtype == 'addsetonagi')
          @if(Auth::guard('user')->user()->c_user())
          @else
           （限定お買い得商品）
          @endif
        @elseif ($addtype == 'addbuyerrecommend')
        （担当のおすすめ商品）
        @elseif ($addtype == 'addspecialprice')
        （市況商品）
        @else
        @endif
      </h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="/">HOME</a></div>
        <div class="breadcrumb-item">オーダー内容確認</div>
      </div>
    </div>


    <div class="section-body">
      <div class="invoice">
        <div class="invoice-print">

          <!-- <form action="{{ url('/adddeal') }}" method="POST" class="form-horizontal"> -->
          <form action="{{ url('/approval') }}" method="POST" class="form-horizontal" onsubmit="return false;">
            @csrf

            @if(isset($setonagi))
              @if(isset($shipping_code))
              @else
                <div class="row mt-4 order">
                  <div class="col-md-12">
                    <div class="section-title">只今ご注文いただいた場合の商品受け渡しは{{$nouhin_yoteibi}}です。</div>
                  </div>
                </div>
              @endif
            @endif

            <div class="row @if(isset($shipping_code))@else mt-4 @endif order">
              <div class="col-md-12">
                <div id="order"></div>
              </div>
            </div>


            <div class="float-right approval_btn_div">
                <input id="addtype" type="hidden" name="addtype" value="{{$addtype}}" />
                <button id="approval_btn" type="button" onclick="submit();" class="btn btn-warning">内容確認画面に進む</button>
                @if($user->setonagi == 1)
                <div id="card_approval_btn" class="btn btn-warning" onclick="executePay">内容確認画面に進む</div>
                @endif
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
  $(function(){
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
      // if (message) {
      //   Swal.fire({
      //     html: message,
      //     // position: 'top-end',
      //     // toast: true,
      //     icon: 'warning',
      //     showConfirmButton: false,
      //     // timer: 1500
      //   });
      // }
  });
});


</script>

<style>
label,
.uketori_siharai_radio{
  pointer-events: auto;
}
.memo_note{
  display: block;
}
.nouhin_yoteibi_c{
    display: block;
    width: 100%;
    height: calc(2.25rem + 6px) !important;
    padding: 0.375rem 0.75rem !important;
    font-size: 1rem !important;
    font-weight: 400 !important;
    line-height: 1.5 !important;
    color: #495057 !important;
    background-color: #fff !important;
    background-clip: padding-box !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.25rem !important;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out !important;
    font-size: 14px !important;
    padding: 10px 5px !important;
    background-color: #fdfdff !important;
    border-color: #c7e2fe !important;
}
.bg_red{
  background-color: rgb(253, 238, 241) !important;
}
.bg_white{
  background-color: #ffffff !important;
}
</style>




<script>
$(document).ready(function () {


    function reloadOnBack() {
      // ページをリロード
      $('.nouhin_yoteibi_c').val('');
      location.reload();
    }
    // popstate イベントのリスナーを追加
    window.addEventListener('popstate', reloadOnBack);

    // pageshow イベントのリスナーを追加
    window.addEventListener('pageshow', function (event) {
      // event.persisted が true の場合、ページがキャッシュから復元されたことを示す
      if (event.persisted) {
        reloadOnBack();
      }
    });


  function order_update_ready() {
    var addtype = '{{ $addtype }}';
    var show_favorite = '{{ $show_favorite }}';
    var store_name = '{{ $change_all_store }}';
    var tokuisaki_name = '{{ $set_tokuisaki_name }}';
    var nouhin_yoteibi = '{{ $change_all_nouhin_yoteibi }}';
    var url = 'confirm';

    console.log(addtype);
    console.log(show_favorite);
    console.log(store_name);
    console.log(tokuisaki_name);
    console.log(nouhin_yoteibi);
    console.log(url);

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: location.origin + '/order',
      type: "POST",
      data: {
        'addtype': addtype,
        'show_favorite': show_favorite,
        'url': url,
        'tokuisaki_name': tokuisaki_name,
        'store_name': store_name,
        'nouhin_yoteibi': nouhin_yoteibi,
      },
      cache: false,
      success: function (data) {
        $('#order').html(data);
      },
      // error: function () {
      //   alert("オーダー内容をアップデートできません。");
      // }
      error: function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 401) {
            alert('カート内全ての商品の在庫が切れています。');
            // セッションが切れた場合の処理
            window.location.href = location.origin + '/user/login'; // ログインページへのリダイレクト
        } else {
        // alert('オーダー内容を取得できません。');
        // console.log("ajax通信に失敗しました");
        // console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        // console.log("textStatus     : " + textStatus);
        // console.log("errorThrown    : " + errorThrown.message);
        Swal.fire({
          text: "カート内全ての商品の在庫が切れています。",
          position: 'center',
          // toast: true,
          icon: 'warning',
          showConfirmButton: false,
          timer: 3000
        });
        // 3秒後にリダイレクトする関数
        function redirectToHomepage() {
          window.location.href = location.origin + '/';
        }
        // 3秒後にredirectToHomepage関数を呼び出す
        setTimeout(redirectToHomepage, 2000);
        }
      }
    });
  }

  setTimeout(order_update_ready);

  function order_update() {
    var params = new URLSearchParams(window.location.search);
    var addtype = '{{ $addtype }}';
    var url = window.location.href;
    var path = url.split('?')[0];
    var url = path.substr(path.lastIndexOf('/') + 1);

    var tokuisaki_name = $('#change_all_store option:selected').attr('id');
    var store_name = $('#change_all_store').val();
    var nouhin_yoteibi = $('#change_all_nouhin_yoteibi').val();

    console.log(store_name);
    console.log(nouhin_yoteibi);

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

  $(document).on("change", ".change_all_store,.change_all_nouhin_yoteibi", function () {
    order_update();
  });

  // $(document).on("change", ".change_all_store", function () {
  //   var element = $(".user_id:first");
  //   var user_id = element.attr("id");
  //   var addtype = '{{ $addtype }}';
  //   var store_name = $(this).val();
  //   var tokuisaki_name = $(this).find('option:selected').attr("id");
  //
  //   $.ajax({
  //     headers: {
  //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //     },
  //     url: location.origin + '/change_all_store',
  //     type: 'POST',
  //     data: {
  //       'user_id': user_id,
  //       'addtype': addtype,
  //       'store_name': store_name,
  //       'tokuisaki_name': tokuisaki_name,
  //     },
  //     success: function (data) {
  //
  //       setTimeout(order_update, 0);
  //       // Swal.fire({
  //       //   type: "success",
  //       //   title: "配送先店舗を変更しました",
  //       //   position: 'center-center',
  //       //   toast: true,
  //       //   icon: 'success',
  //       //   showConfirmButton: false,
  //       //   timer: 1500
  //       // });
  //     },
  //     error: function () {
  //       alert("配送先店舗を保存できません。");
  //     }
  //   });
  // });

  // $(document).on("change", ".change_all_nouhin_yoteibi", function () {
  //   var element = $(".user_id:first");
  //   var user_id = element.attr("id");
  //   var addtype = '{{ $addtype }}';
  //   var nouhin_yoteibi = $(this).val();
  //
  //   $.ajax({
  //     headers: {
  //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //     },
  //     url: location.origin + '/change_all_nouhin_yoteibi',
  //     type: 'POST',
  //     data: {
  //       'user_id': user_id,
  //       'addtype': addtype,
  //       'nouhin_yoteibi': nouhin_yoteibi,
  //     },
  //     success: function (data) {
  //
  //       setTimeout(order_update, 0);
  //       // Swal.fire({
  //       //   type: "success",
  //       //   title: "納品予定日を変更しました。",
  //       //   position: 'center-center',
  //       //   toast: true,
  //       //   icon: 'success',
  //       //   showConfirmButton: false,
  //       //   timer: 1500
  //       // });
  //     },
  //     error: function () {
  //       alert("納品予定日を変更できませんでした。");
  //     }
  //   });
  // });

  $(document).on("click", "#show_favorite", function() {
      order_update();
  });

  // お気に入りの商品に追加
  $(document).on("click", ".addfavoriteitem", function() {
    var item_id = $(this).get(0).id;
    // var setonagi_item_id = $(this).parent().find('.setonagi_item_id').val();
    // console.log(item_id);
    // console.log(quantity);
    // console.log(setonagi_item_id);
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/addfavoriteitem',
        type: 'POST', //リクエストタイプ
        data: {
          'item_id': item_id,
          // 'setonagi_item_id' : setonagi_item_id,
        }
      })
      .done(function(json) {
        // 既にカートにあるときの分岐
        console.log(json['message']);
        if(json['message']=='favorite_in'){
          // $('#toggle').addClass('beep');
          // $('#toggle').trigger('click');
          Swal.fire({
            title: "既にお気に入りに追加されています",
            position: 'center-center',
            toast: true,
            icon: 'info',
            showConfirmButton: false,
            timer: 3000
          });
        }else{
          // $('#toggle').addClass('beep');
          // $('#toggle').trigger('click');
          // setTimeout(order_update);
          // location.reload();
          // Swal.fire({
          //   type:"success",
          //   title: "お気に入りに追加しました",
          //   position: 'center-center',
          //   toast: true,
          //   iconColor: "rgba(241,73,41,1)",
          //   icon: 'success',
          //   showConfirmButton: false,
          //   timer: 1500
          // });
        }
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('追加できませんでした。しばらくして再度お試しください。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  });


  // HOME画面でお気に入り商品をカートから削除
  $(document).on("click", ".removefavoriteitem", function() {
    // var cart_id = $(this).get(0).id;
    // $(this).parent().parent().remove();
    // console.log(cart_id);
    var item_id = $(this).get(0).id;

    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/removefavoriteitem',
        type: 'POST', //リクエストタイプ
        data: {
          'item_id': item_id,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
        // setTimeout(order_update);
        // setTimeout(dealorder_update);
        // location.reload();
        // Swal.fire({
        //   type:"success",
        //   title: "お気に入り商品を削除しました",
        //   position: 'center-center',
        //   toast: true,
        //   icon: 'success',
        //   showConfirmButton: false,
        //   timer: 1500
        // });
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('お気に入り商品を削除できませんでした。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  });

  // お気に入りボタン
  $(document).ready(function() {
    $(document).on('click', '.favoritebutton', function() {
      if ($(this).hasClass('removefavoriteitem')) {
        $(this).removeClass('removefavoriteitem');
        $(this).addClass('addfavoriteitem');
        $(this).find('i').removeClass('fa fa-heart');
        $(this).find('i').addClass('far fa-heart');
      } else if ($(this).hasClass('addfavoriteitem')) {
        $(this).removeClass('addfavoriteitem');
        $(this).addClass('removefavoriteitem');
        $(this).find('i').removeClass('far fa-heart');
        $(this).find('i').addClass('fa fa-heart');
      }
    });
  });


});
</script>

@endsection
