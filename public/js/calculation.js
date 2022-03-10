/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */


$(function() {

  $(document).ajaxSend(function() {
    $("#overlayajax").fadeIn(300);　
  });

  $(document).ajaxSuccess(function() {
    $("#overlayajax").fadeOut(300);
  });

  function doReload() {
      window.location.reload();
  }

  // カートに追加
  $(document).on("click", ".addcart", function() {
    var item_id = $(this).get(0).id;
    var quantity = $(this).parent().parent().find('.quantity').val();
    console.log(item_id);
    console.log(quantity);
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/addcart',
        type: 'POST', //リクエストタイプ
        data: {
          'item_id': item_id,
          'quantity': quantity,
        }
      })
      .done(function(data) {
        // console.log(data);
        $('#toggle').addClass('beep');
        // $('#toggle').trigger('click');
        Swal.fire({
          type:"success",
          title: "カートに追加しました",
          // position: 'top-end',
          // toast: true,
          icon: 'success',
          showConfirmButton: false,
          // timer: 1500
        });
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('ユーザーに紐づく得意先店舗がありません');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  });

  // カートの中身を更新
  function dojQueryAjax() {
      // jQueryのajaxメソッドを使用しajax通信
      $.ajax({
          type: "GET", // GETメソッドで通信
          url: location.origin + '/cart',
          cache: false, // キャッシュしないで読み込み
          // 通信成功時に呼び出されるコールバック
          success: function (data) {
                $('#cart').html(data);
          },
          // 通信エラー時に呼び出されるコールバック
          error: function () {
              alert("カートの中身を更新できませんでした。");
          }
      });
      $('#cart-container').addClass('show');
  }


  $('#toggle').click(function(){
    $(this).removeClass("beep");
      setTimeout(dojQueryAjax);
  });

  //HOME画面でカートの外側をクリックしたらカートの内容を非表示
  document.addEventListener('click', (e) => {
    if(!e.target.closest('.dropdown-list-content')) {
      $('#cart-container').removeClass("show");
      $('#dropdown').removeClass("show");
    } else {
      //ここに内側をクリックしたときの処理
    }
  })


  // HOME画面でカートから削除
  $(document).on("click", ".removecart", function() {
    var cart_id = $(this).get(0).id;
    $(this).parent().parent().remove();
    console.log(cart_id);

    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/removecart',
        type: 'POST', //リクエストタイプ
        data: {
          'cart_id': cart_id,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
        setTimeout(order_update);
        setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "商品を削除しました",
          // position: 'top-end',
          // toast: true,
          icon: 'success',
          showConfirmButton: false,
          // timer: 1500
        });
        // 送信画面のときは、カートのポップアップを出さない
        if(!document.URL.match(/confirm/)){
        $('#toggle').addClass('beep');
        $('#toggle').trigger('click');
        }
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('カートの中身を削除できませんでした。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  });









// 取引詳細画面でオーダー内容を取得する関数（ユーザー側）
if(document.URL.match("/user")) {
  function dealorder_update() {
    var deal_id = $('#deal_id').val();
    console.log(deal_id);
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/dealorder',
        type: 'POST', //リクエストタイプ
        data: {
          'deal_id': deal_id,
        },
				cache: false,
        success: function (data) {
              $('#dealorder').html(data);
        },
        error: function () {
            // alert("Ajax通信エラー");
        }
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('オーダー内容が取得できません。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  	}
  // 個数入力画面を開いたらオーダー内容を取得
  $(document).ready( function(){
  setTimeout(dealorder_update);
  });
}


// 取引詳細画面でオーダー内容を取得する関数（管理者側）
if(document.URL.match("/admin/deal")) {
  function dealorder_update() {
    var deal_id = $('#deal_id').val();
    console.log(deal_id);
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/admin/dealorder',
        type: 'POST', //リクエストタイプ
        data: {
          'deal_id': deal_id,
        },
				cache: false,
        success: function (data) {
              $('#dealorder').html(data);
        },
        error: function () {
            // alert("Ajax通信エラー");
        }
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('オーダー内容を取得できません。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  	}
  // 個数入力画面を開いたらオーダー内容を取得
  $(document).ready( function(){
  setTimeout(dealorder_update);
  });
}








  // オーダー内容を取得する関数
    function order_update() {
        $.ajax({
            type: "GET", // GETメソッドで通信
            url: location.origin + '/order',
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
      }

    // 個数入力画面を開いたらオーダー内容を取得
    $(document).ready( function(){
    setTimeout(order_update);
    });


  // 配送先を追加
  $(document).on("click", ".clonecart", function() {
    var item_id = $(this).get(0).id;
    var cart_id = $(this).parent().parent().parent().parent().parent().parent().get(0).id;
    console.log(cart_id);
    // $(this).parent().parent().clone(true).insertAfter($(this).parent().parent());

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/clonecart',
        type: 'POST', //リクエストタイプ
        data: {
          'item_id': item_id,
          'cart_id': cart_id,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
        // setTimeout(doReload);
        setTimeout(order_update);
        setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "配送先を追加しました",
          // position: 'top-end',
          // toast: true,
          icon: 'success',
          showConfirmButton: false,
          // timer: 1500
        });
        setTimeout(function(){
          $("#overlay").fadeOut(300);
        },500);
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('配送先を追加できません。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  });


  // 任意の配送先を追加
  $(document).on("click", ".addordernini", function() {
    var cart_nini_id = $(this).get(0).id;
    console.log(cart_nini_id);
    // $(this).parent().parent().clone(true).insertAfter($(this).parent().parent());

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/addordernini',
        type: 'POST', //リクエストタイプ
        data: {
          'cart_nini_id': cart_nini_id,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
        // setTimeout(doReload);
        setTimeout(order_update);
        setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "配送先を追加しました",
          // position: 'top-end',
          // toast: true,
          icon: 'success',
          showConfirmButton: false,
          // timer: 1500
        });
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('配送先を追加できません。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  });


  // 任意の商品を追加
  $(document).on("click", ".addniniorder", function() {
    // var deal_id = $(this).parent().parent().parent().parent().parent().parent().get(0).id;
    // console.log(deal_id);
    // $(this).parent().parent().clone(true).insertAfter($(this).parent().parent());

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/addniniorder',
        type: 'POST', //リクエストタイプ
        data: {
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
        // setTimeout(doReload);
        setTimeout(order_update);
        setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "任意の商品を追加しました",
          // position: 'top-end',
          // toast: true,
          icon: 'success',
          showConfirmButton: false,
          // timer: 1500
        });
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('配送先を追加できません。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  });



  // 確認画面カートから削除
  $(document).on("click", ".removeorder", function() {
    var order_id = $(this).get(0).id;
    var cart_id = $(this).parent().parent().parent().parent().parent().parent().get(0).id;

    // $(this).parent().parent().remove();
    console.log(cart_id);
    console.log(order_id);
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/removeorder',
        type: 'POST', //リクエストタイプ
        data: {
          'order_id': order_id,
          'cart_id': cart_id,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
        // setTimeout(doReload);
        setTimeout(order_update);
        setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "カートから削除しました",
          // position: 'top-end',
          // toast: true,
          icon: 'success',
          showConfirmButton: false,
          // timer: 1500
        });
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('カートから削除できませんでした。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  });




    // 任意の商品をカートから削除
    $(document).on("click", ".removeordernini", function() {
      var order_nini_id = $(this).get(0).id;
      var cart_nini_id = $(this).parent().parent().parent().parent().parent().parent().get(0).id;

      // $(this).parent().parent().remove();
      console.log(order_nini_id);
      console.log(cart_nini_id);
      $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }, //Headersを書き忘れるとエラーになる
          url: location.origin + '/removeordernini',
          type: 'POST', //リクエストタイプ
          data: {
            'order_nini_id': order_nini_id,
            'cart_nini_id': cart_nini_id,
          } //Laravelに渡すデータ
        })
        // Ajaxリクエスト成功時の処理
        .done(function(data) {
          // console.log(data);
          // setTimeout(doReload);
          setTimeout(order_update);
          setTimeout(dealorder_update);
          Swal.fire({
            type:"success",
            title: "カートから削除しました",
            // position: 'top-end',
            // toast: true,
            icon: 'success',
            showConfirmButton: false,
            // timer: 1500
          });
        })
        // Ajaxリクエスト失敗時の処理
        .fail(function(jqXHR, textStatus, errorThrown) {
          alert('カートから削除できませんでした。');
          console.log("ajax通信に失敗しました");
          console.log("XMLHttpRequest : " + XMLHttpRequest.status);
          console.log("textStatus     : " + textStatus);
          console.log("errorThrown    : " + errorThrown.message);
        });
    });



  // 個数変更を保存
  $(document).on("change", ".quantity", function() {
    var order_id = $(this).parent().parent().get(0).id;
    var quantity = $(this).val();
    console.log(quantity);
    console.log(order_id);
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/change_quantity',
        type: 'POST', //リクエストタイプ
        data: {
          'order_id': order_id,
          'quantity': quantity,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
        // setTimeout(doReload);
        setTimeout(order_update);
        setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "個数を変更しました",
          // position: 'top-end',
          // toast: true,
          icon: 'success',
          showConfirmButton: false,
          // timer: 1500
        });
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('個数を変更できませんでした。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  });


  // 納品予定日を保存
  $(document).on("change", ".nouhin_yoteibi", function() {
    var order_id = $(this).parent().parent().get(0).id;
    var nouhin_yoteibi = $(this).val();
    console.log(order_id);
    console.log(nouhin_yoteibi);
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/change_nouhin_yoteibi',
        type: 'POST', //リクエストタイプ
        data: {
          'order_id': order_id,
          'nouhin_yoteibi': nouhin_yoteibi,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
        // setTimeout(doReload);
        setTimeout(order_update);
        setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "納品予定日を変更しました",
          // position: 'top-end',
          // toast: true,
          icon: 'success',
          showConfirmButton: false,
          // timer: 1500
        });
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('予定日を保存できません。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  });

  // 配送先店舗を保存
  $(document).on("change", ".store", function() {
    var order_id = $(this).parent().parent().get(0).id;
    var store_name = $(this).val();
    var tokuisaki_name = $(this).find('option:selected').get(0).id;
    console.log(order_id);
    console.log(store_name);
    console.log(tokuisaki_name);
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/change_store',
        type: 'POST', //リクエストタイプ
        data: {
          'order_id': order_id,
          'store_name': store_name,
          'tokuisaki_name': tokuisaki_name,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
        // setTimeout(doReload);
        setTimeout(order_update);
        setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "配送先店舗を変更しました",
          // position: 'top-end',
          // toast: true,
          icon: 'success',
          showConfirmButton: false,
          // timer: 1500
        });
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('配送先店舗を保存できません。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  });


  // 任意の商品名を保存
  $(document).on("change", ".nini_item_name", function() {
    var cart_nini_id = $(this).parent().parent().get(0).id;
    var nini_item_name = $(this).val();
    console.log(nini_item_name);
    console.log(cart_nini_id);
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/nini_change_item_name',
        type: 'POST', //リクエストタイプ
        data: {
          'cart_nini_id': cart_nini_id,
          'nini_item_name': nini_item_name,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
        // setTimeout(doReload);
        setTimeout(order_update);
        setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "任意の商品名を保存しました",
          // position: 'top-end',
          // toast: true,
          icon: 'success',
          showConfirmButton: false,
          // timer: 1500
        });
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('商品名を変更できませんでした。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  });

  // 任意の担当を保存
  $(document).on("change", ".nini_tantou", function() {
    var cart_nini_id = $(this).parent().parent().get(0).id;
    var nini_tantou = $(this).val();
    console.log(nini_tantou);
    console.log(cart_nini_id);
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/nini_change_tantou',
        type: 'POST', //リクエストタイプ
        data: {
          'cart_nini_id': cart_nini_id,
          'nini_tantou': nini_tantou,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
        // setTimeout(doReload);
        setTimeout(order_update);
        setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "担当を変更しました",
          // position: 'top-end',
          // toast: true,
          icon: 'success',
          showConfirmButton: false,
          // timer: 1500
        });
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('担当を変更できませんでした。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  });

  // 任意の個数変更を保存
  $(document).on("change", ".nini_quantity", function() {
    var order_nini_id = $(this).parent().parent().get(0).id;
    var nini_quantity = $(this).val();
    console.log(nini_quantity);
    console.log(order_nini_id);
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/nini_change_quantity',
        type: 'POST', //リクエストタイプ
        data: {
          'order_nini_id': order_nini_id,
          'nini_quantity': nini_quantity,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
        // setTimeout(doReload);
        setTimeout(order_update);
        setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "数量（単位）を変更しました",
          // position: 'top-end',
          // toast: true,
          icon: 'success',
          showConfirmButton: false,
          // timer: 1500
        });
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('個数を変更できませんでした。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  });

  // 任意の納品予定日を保存
  $(document).on("change", ".nini_nouhin_yoteibi", function() {
    var order_nini_id = $(this).parent().parent().get(0).id;
    var nini_nouhin_yoteibi = $(this).val();
    console.log(order_nini_id);
    console.log(nini_nouhin_yoteibi);
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/nini_change_nouhin_yoteibi',
        type: 'POST', //リクエストタイプ
        data: {
          'order_nini_id': order_nini_id,
          'nini_nouhin_yoteibi': nini_nouhin_yoteibi,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // setTimeout(doReload);
        setTimeout(order_update);
        setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "納品予定日を変更しました",
          // position: 'top-end',
          // toast: true,
          icon: 'success',
          showConfirmButton: false,
          // timer: 1500
        });
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('予定日を保存できません。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  });

  // 任意の配送先店舗を保存
  $(document).on("change", ".nini_store", function() {
    var order_nini_id = $(this).parent().parent().get(0).id;
    var nini_store_name = $(this).val();
    var nini_tokuisaki_name = $(this).find('option:selected').get(0).id;
    console.log(order_nini_id);
    console.log(nini_store_name);
    console.log(nini_tokuisaki_name);
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/nini_change_store',
        type: 'POST', //リクエストタイプ
        data: {
          'order_nini_id': order_nini_id,
          'nini_store_name': nini_store_name,
          'nini_tokuisaki_name': nini_tokuisaki_name,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // setTimeout(doReload);
        setTimeout(order_update);
        setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "納品先店舗を変更しました",
          // position: 'top-end',
          // toast: true,
          icon: 'success',
          showConfirmButton: false,
          // timer: 1500
        });
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('配送先店舗を保存できません。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
  });




// 顧客側オーダー内容を随時取得

if(document.URL.match("/user/deal")) {
  $(function(){
      setInterval(function(){


    var prices = $(".price").map(function (index, el) {
      var prices = $(this).val();
      return (prices);
    }).get();

    var ids = $(".order_id").map(function (index, el) {
      var ids = $(this).val();
      return (ids);
    }).get();

    console.log(prices);
    console.log(ids);

    var array = [];
    for (var i = 0, l = ids.length, obj = Object.create(null); i < l; ++i) {
      if (prices.hasOwnProperty(i)) {
        obj[ids[i]] = prices[i];
      }
    }
    array = obj;
    console.log(array);

    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/updateorder',
        type: 'POST', //リクエストタイプ
        data: {
          'array': array,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        console.log(data);
        if (data == 0) {
          location.reload(true);
      	}
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        // alert('Ajaxリクエスト失敗');
        console.log("オーダー内容を取得できません。");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
    },1000);
});
}





// 担当のおすすめ商品削除（formタグ回避）
if(document.URL.match("/admin/user/recommend")) {
  $(function(){
    $(".delete_button").on("click",function(){
      var remove_id = $(this).data('id');
      console.log(remove_id);
      $("#remove_id").val(remove_id);
      $('#remove_form').submit();
    });
  });
}

// カテゴリーごとのおすすめ商品削除（formタグ回避）
if(document.URL.match("/admin/recommendcategory")) {
  $(function(){
    $(".delete_button").on("click",function(){
      var remove_id = $(this).data('id');
      console.log(remove_id);
      $("#remove_id").val(remove_id);
      $('#remove_form').submit();
    });
  });
}



});


















// 合計金額アップデート
// function update_field(){
//     $('input').on('keyup change',function(){
//
//       var target = $('input').map(function (index, el) {
//       $(this).closest('tr').find('.total').text(
//       $(this).closest('tr').find('input.teika').val() *
//       $(this).closest('tr').find('input.quantity').val());});
//       console.log(target);
//
//     var sum = 0;
//     $('.total').each(function () {
//         sum += parseInt(this.innerText);
//         var item_total = $('#item_total').map(function (index, el) {
//         var item_total = Number(item_total).toLocaleString()
//         $(this).text("¥ "+ sum.toLocaleString() );});
//
//         var all_total = $('#all_total').map(function (index, el) {
//         var all_total = sum * 1.1;
//         var all_total = Math.round(all_total);
//         $(this).text("¥ "+ all_total.toLocaleString());});
//
//         var tax = $('#tax').map(function (index, el) {
//         var tax = sum * 1.1 - sum;
//         var tax = Math.round(tax);
//         $(this).text("¥ "+ tax.toLocaleString());});
//     });
//
//
//     if(document.URL.match(/admin/)){
//       var discount = $(this).closest('tr').find('input.discount').val();
//       var quantity = $(this).closest('tr').find('input.quantity').val();
//       var cart_id = $(this).closest('tr').find('.cart_id').val();
//
//       $.ajax({
//           headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//           },
//           url: location.origin + '/admin/updatecart',
//           type: 'POST',
//           data: {
//             'discount': discount,
//             'quantity': quantity,
//             'cart_id': cart_id,
//           }
//         })
//
//         .done(function(data) {
//           console.log(data);
//         })
//
//         .fail(function(jqXHR, textStatus, errorThrown) {
//           alert('Ajaxリクエスト失敗');
//           console.log("ajax通信に失敗しました");
//           console.log("XMLHttpRequest : " + XMLHttpRequest.status);
//           console.log("textStatus     : " + textStatus);
//           console.log("errorThrown    : " + errorThrown.message);
//         });
//       }
//
//     if(document.URL.match(/user/)){
//       var discount = $(this).closest('tr').find('input.teika').val();
//       var quantity = $(this).closest('tr').find('input.quantity').val();
//       var cart_id = $(this).closest('tr').find('input.cart_id').val();
//
//       $.ajax({
//           headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//           },
//           url: location.origin + '/updatecart',
//           type: 'POST',
//           data: {
//             'discount': discount,
//             'quantity': quantity,
//             'cart_id': cart_id,
//           }
//         })
//
//         .done(function(data) {
//           console.log(data);
//         })
//
//         .fail(function(jqXHR, textStatus, errorThrown) {
//           alert('Ajaxリクエスト失敗');
//           console.log("ajax通信に失敗しました");
//           console.log("XMLHttpRequest : " + XMLHttpRequest.status);
//           console.log("textStatus     : " + textStatus);
//           console.log("errorThrown    : " + errorThrown.message);
//         });
//       }
//    });
// }

// 合計金額アップデート画面を開たとき
// $(document).ready( function(){
//     update_field();
//
//       var target = $('.total').map(function (index, el) {
//       $(this).closest('tr').find('.total').text(
//       $(this).closest('tr').find('input.teika').val() *
//       $(this).closest('tr').find('input.quantity').val());});
//       // console.log(target);
//
//       var sum = 0;
//       $('.total').each(function () {
//           sum += parseInt(this.innerText);
//           var item_total = $('#item_total').map(function (index, el) {
//           $(this).text("¥ "+ sum.toLocaleString() );});
//
//           var all_total = $('#all_total').map(function (index, el) {
//           var all_total = sum * 1.1;
//           var all_total = Math.round(all_total);
//           $(this).text("¥ "+ all_total.toLocaleString());});
//
//           var tax = $('#tax').map(function (index, el) {
//           var tax = sum * 1.1 - sum;
//           var tax = Math.round(tax);
//           $(this).text("¥ "+ tax.toLocaleString());});
//       });
//
//
// });




// if(document.URL.match("/user/deal")) {
//   window.addEventListener('load', function () {
//       setInterval(function () {
//         $(".price").each( function() {
//
//           const inputs = $('.price').each(function(index, element){
//               return element.value;  // valueを取り出す
//           }).get();  // 標準的な配列に変換
//
//           console.log(inputs);  // ["input1", "input2", "input3"]
//
//         $.ajax({
//             headers: {
//               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             }, //Headersを書き忘れるとエラーになる
//             url: location.origin + '/updatecart',
//             type: 'POST', //リクエストタイプ
//             data: {
//               'discount': discount,
//               'quantity': quantity,
//               'cart_id': cart_id,
//             } //Laravelに渡すデータ
//           })
//           .done(function(data) {
//             console.log(data);
//             console.log(quantity);
//           })
//         $(this).closest('tr').find('input.quantity').val(quantity);
//         });
//       }, 1000);
//   })
// }















// value値を取得
// const str1 = $(".store").val();
// alert(str1);
// alert('JavaScriptのアラート');


// if(document.URL.match("/user/deal")) {
//   window.addEventListener('load', function () {
//       setInterval(function () {
//         $(".cart_id").each( function() {
//         var discount = $(this).closest('tr').find('input.teika').val();
//         var quantity = $(this).closest('tr').find('input.change_quantity').val();
//         var cart_id = $(this).closest('tr').find('.cart_id').val();
//         $.ajax({
//             headers: {
//               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             }, //Headersを書き忘れるとエラーになる
//             url: location.origin + '/updatecart',
//             type: 'POST', //リクエストタイプ
//             data: {
//               'discount': discount,
//               'quantity': quantity,
//               'cart_id': cart_id,
//             } //Laravelに渡すデータ
//           })
//           .done(function(data) {
//             console.log(data);
//             console.log(quantity);
//           })
//         $(this).closest('tr').find('input.quantity').val(quantity);
//         });
//       }, 1000);
//   })
// }



// if(document.URL.match(/admin/)){
//   window.addEventListener('load', function () {
//       setInterval(function () {
//         $(".cart_id").each( function() {
//         var discount = $(this).closest('tr').find('input.discount').val();
//         var quantity = $(this).closest('tr').find('input.change_quantity').val();
//         var cart_id = $(this).closest('tr').find('input.cart_id').val();
//         $.ajax({
//             headers: {
//               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             }, //Headersを書き忘れるとエラーになる
//             url: location.origin + '/updatecart',
//             type: 'POST', //リクエストタイプ
//             data: {
//               'discount': discount,
//               'quantity': quantity,
//               'cart_id': cart_id,
//             } //Laravelに渡すデータ
//           })
//           .done(function(data) {
//             console.log(discount);
//             console.log(quantity);
//           })
//         $(this).closest('tr').find('input.teika').val(discount);
//         $(this).closest('tr').find('input.quantity').val(quantity);
//         });
//       }, 1000);
//   })
// }
