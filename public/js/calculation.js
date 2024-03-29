/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */


$(function() {

  $(document).ajaxSend(function() {
    $("#overlayajax").fadeIn();　

  });

  $(document).ajaxComplete(function() {
    $("#overlayajax").fadeOut();
  });

  // リロード処理
  function doReload() {
      window.location.reload();
  }


  // 管理者側検索時取引一覧post処理
  if(document.URL.match("/admin/")) {
    $(document).on("change", "#admin_deal_search", function() {
      $('#admin_deal_search').submit();
    });
  }

  // 得意先ごとのおすすめ商品登録
  // if(document.URL.match("/admin/buyer/recommend/")) {
  //   $(document).on("change", "#hidden_price, #zaikosuu, #zaikokanri, #uwagaki_item_name, #uwagaki_kikaku, .gentei_store",
  //   function() {
  //     $('#buyerrecommend').submit();
  //   });
  // }

  // カートに追加
  $(document).on("click", ".addcart", function() {
    var item_id = $(this).get(0).id;
    var quantity = $(this).parent().parent().find('.quantity').val();
    var setonagi_item_id = $(this).parent().find('.setonagi_item_id').val();
    // console.log(item_id);
    // console.log(quantity);
    // console.log(setonagi_item_id);
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/addcart',
        type: 'POST', //リクエストタイプ
        data: {
          'item_id': item_id,
          'quantity': quantity,
          'setonagi_item_id' : setonagi_item_id,
        }
      })
      .done(function(json) {
        // 既にカートにあるときの分岐
        console.log(json['message']);
        if(json['message']=='cart_in'){
          // $('#toggle').addClass('beep');
          // $('#toggle').trigger('click');
          Swal.fire({
            title: "既にカートに追加されています",
            position: 'center-center',
            toast: true,
            icon: 'info',
            showConfirmButton: false,
            timer: 3000
          });
        }else{
          // $('#toggle').addClass('beep');
          // $('#toggle').trigger('click');
          Swal.fire({
            type:"success",
            title: "カートに追加しました",
            position: 'center-center',
            toast: true,
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
          });
        }
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 401) {
          // セッションが切れた場合の処理
          window.location.href = location.origin + '/user/login'; // ログインページへのリダイレクト
        } else {
          alert('ユーザーに紐づく得意先店舗がありません');
          console.log("ajax通信に失敗しました");
          console.log("XMLHttpRequest : " + XMLHttpRequest.status);
          console.log("textStatus     : " + textStatus);
          console.log("errorThrown    : " + errorThrown.message);
        }
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
          error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 401) {
                // セッションが切れた場合の処理
                window.location.href = location.origin + '/user/login'; // ログインページへのリダイレクト
            } else {
            alert('カートを更新できませんでした。');
            console.log("ajax通信に失敗しました");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
            }
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
        // setTimeout(order_update);
        // setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "商品を削除しました",
          position: 'center-center',
          toast: true,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
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
if (document.URL.includes("user") && !document.URL.includes("admin/user") &&  !document.URL.includes("admin/setonagiuser")) {
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
        if (jqXHR.status === 401) {
            // セッションが切れた場合の処理
            window.location.href = location.origin + '/user/login'; // ログインページへのリダイレクト
        } else {
        alert('オーダー内容が取得できません。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
        }
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

if(document.URL.match("/approval")) {
    // var params = new URLSearchParams(window.location.search);
    // var addtype = params.get('addtype');
    // var show_favorite = params.get('show_favorite');
    // var change_all_store = '{{ $request->input('change_all_store') }}';
    // $.ajax({
    //   headers: {
    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //   }, //Headersを書き忘れるとエラーになる
    //   url: location.origin + '/order',
    //   type: "POST", // GETメソッドで通信
    //   data: {
    //     'addtype': addtype,
    //     'show_favorite': show_favorite,
    //     'change_all_store': change_all_store,
    //   },
    //   cache: false, // キャッシュしないで読み込み
    //   // 通信成功時に呼び出されるコールバック
    //   success: function (data) {
    //         $('#order').html(data);
    //   },
    //   // 通信エラー時に呼び出されるコールバック
    //   error: function () {
    //       alert("オーダー内容をアップデートできません。");
    //   }
    // });
}


  // オーダー内容を取得する関数
  function order_update() {
    var params = new URLSearchParams(window.location.search);
    var addtype = params.get('addtype');
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
        },
        // 通信エラー時に呼び出されるコールバック
        error: function () {
            alert("商品が見つかりませんでした。");
        }
    });
  }

  // お気に入り商品チェックがされたら、アップデート





  // 個数入力画面を開いたらオーダー内容を取得
  // if(document.URL.match("/confirm")) {
  //   $(document).ready( function(){
  //   setTimeout(order_update);
  //   });
  // }

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
          position: 'center-center',
          toast: true,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
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


  // リピートオーダーの配送先を追加
  $(document).on("click", ".clonerepeatorder", function() {
    // var kaiin_number = $('#kaiin_number').val();
    var item_id = $(this).get(0).id;
    var cart_id = $(this).parent().parent().parent().parent().parent().parent().get(0).id;
    console.log(item_id);
    console.log(cart_id);
    // $(this).parent().parent().clone(true).insertAfter($(this).parent().parent());

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/admin/user/clonerepeatorder',
        type: 'POST', //リクエストタイプ
        data: {
          'item_id': item_id,
          'cart_id': cart_id,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
        setTimeout(doReload);
        // setTimeout(order_update);
        // setTimeout(dealorder_update);
        // Swal.fire({
        //   type:"success",
        //   title: "配送先を追加しました",
        //   // position: 'top-end',
        //   // toast: true,
        //   icon: 'success',
        //   showConfirmButton: false,
        //   // timer: 1500
        // });
        // setTimeout(function(){
        //   $("#overlay").fadeOut(300);
        // },500);
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
    var store_name = $(".change_all_store").val();
    var nouhin_yoteibi = $(".change_all_nouhin_yoteibi").val();
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
          'store_name': store_name,
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
          title: "配送先を追加しました",
          position: 'center-center',
          toast: true,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
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
    var deal_id = $(this).get(0).id;
    var kaiin_number = $(this).prev().get(0).id;
    var params = new URLSearchParams(window.location.search);
    var addtype = $("#addtype").val();
    console.log(deal_id);
    console.log(kaiin_number);
    console.log(addtype);
    // $(this).parent().parent().clone(true).insertAfter($(this).parent().parent());

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/addniniorder',
        type: 'POST', //リクエストタイプ
        data: {
          'deal_id': deal_id,
          'kaiin_number': kaiin_number,
          'addtype': addtype,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
        // setTimeout(doReload);
        setTimeout(order_update);
        setTimeout(dealorder_update);
        Swal.fire({
          // type:"success",
          title: "任意の商品を追加しました",
          position: 'center-center',
          toast: true,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
        });
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('任意の商品を追加できませんでした');
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
          position: 'center-center',
          toast: true,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
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
            position: 'center-center',
            toast: true,
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
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


    // 価格変更を保存（管理側担当のおすすめ商品編集ページは除く）
    if(document.URL.match("/admin/user/recommend") || document.URL.match("/admin/user/repeatorder") || document.URL.match("/admin/buyer/recommend")) {
    }else{
      $(document).on("change", ".price", function() {
        var order_id = $(this).parent().parent().get(0).id;
        var price = $(this).val();
        if( price.match( /[^0-9]+/ ) ) {
          alert("半角数字で入力して下さい。");
          return false;
        }
        console.log(price);
        console.log(order_id);
        $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, //Headersを書き忘れるとエラーになる
            url: location.origin + '/change_price',
            type: 'POST', //リクエストタイプ
            data: {
              'order_id': order_id,
              'price': price,
            } //Laravelに渡すデータ
          })
          // Ajaxリクエスト成功時の処理
          .done(function(data) {
            // console.log(data);
            // setTimeout(doReload);
            // setTimeout(order_update);
            // setTimeout(dealorder_update);
            Swal.fire({
              type:"success",
              title: "金額を変更しました",
              position: 'center-center',
              toast: true,
              icon: 'success',
              showConfirmButton: false,
              timer: 1500
            });
          })
          // Ajaxリクエスト失敗時の処理
          .fail(function(jqXHR, textStatus, errorThrown) {
            // alert('金額を変更できませんでした。');
            console.log("ajax通信に失敗しました");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
          });
      });
    }

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
      .done(function(json) {
        // 既にカートにあるときの分岐
        console.log(json['message']);
        if(json['message']=='fail'){
          // $('#toggle').addClass('beep');
          // $('#toggle').trigger('click');
          Swal.fire({
            text: "在庫数が0を下回るため変更できません。",
            position: 'center-center',
            icon: 'info',
            showConfirmButton: false,
            timer: 3000
          });
          // 2秒後にリロード
          setTimeout(function() {
              location.reload();
          }, 2000);
        }else{
          Swal.fire({
            type:"success",
            title: "個数を変更しました",
            position: 'center-center',
            toast: true,
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
          });
        }
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 401) {
          // セッションが切れた場合の処理
          window.location.href = location.origin + '/user/login'; // ログインページへのリダイレクト
        } else {
          alert('個数を変更できませんでした。半角数字で入力してください。');
          console.log("ajax通信に失敗しました");
          console.log("XMLHttpRequest : " + XMLHttpRequest.status);
          console.log("textStatus     : " + textStatus);
          console.log("errorThrown    : " + errorThrown.message);
        }
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
          position: 'center-center',
          toast: true,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
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
    var kaiin_number = $(this).parent().parent().parent().parent().parent().parent().parent().parent().get(0).id;
    var order_id = $(this).parent().parent().get(0).id;
    var store_name = $(this).val();
    var tokuisaki_name = $(this).find('option:selected').get(0).id;
    console.log(order_id);
    console.log(store_name);
    console.log(tokuisaki_name);
    console.log(kaiin_number);
    // 全店舗に追加の分岐
    if(store_name == 'all_store') {
      Swal.fire({
        // title: "納品予定日を変更しました",
        text: "全店舗に追加する場合、この商品の納品先、個数の設定は削除されます。よろしいですか？",
        // position: 'top-end',
        // toast: true,
        icon: 'warning',
        // showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: '#47c363',
        cancelButtonColor: '#3abaf4',
        confirmButtonText: 'はい',
        cancelButtonText: 'いいえ',
        // timer: 1500
      }).then((result) => {
      if (result.value) {
        $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, //Headersを書き忘れるとエラーになる
            url: location.origin + '/add_all_store',
            type: 'POST', //リクエストタイプ
            data: {
              'kaiin_number': kaiin_number,
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
              position: 'center-center',
              toast: true,
              icon: 'success',
              showConfirmButton: false,
              timer: 1500
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
      }else{
        setTimeout(order_update);
        setTimeout(dealorder_update);
      }
      });
    }else{
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/change_store',
        type: 'POST', //リクエストタイプ
        data: {
          'kaiin_number': kaiin_number,
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
          position: 'center-center',
          toast: true,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
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
    }
  });


  // 受け取り方法を保存
  // $(document).on("change", ".uketori_place", function() {
  //   var user_id = $(".user_id").first().attr("id");
  //   var uketori_place = $(this).val();
  //   console.log(uketori_place);
  //   $.ajax({
  //       headers: {
  //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //       },
  //       url: location.origin + '/change_uketori_place',
  //       type: 'POST',
  //       data: {
  //         'user_id' : user_id,
  //         'uketori_place': uketori_place,
  //       }
  //     })
  //     .done(function(data) {
  //       Swal.fire({
  //         type:"success",
  //         title: "配送方法を保存しました",
  //         position: 'center-center',
  //         toast: true,
  //         icon: 'success',
  //         showConfirmButton: false,
  //         timer: 1500
  //       });
  //     })
  //     .fail(function(jqXHR, textStatus, errorThrown) {
  //       alert('配送方法を保存できませんでした');
  //       console.log("ajax通信に失敗しました");
  //       console.log("XMLHttpRequest : " + XMLHttpRequest.status);
  //       console.log("textStatus     : " + textStatus);
  //       console.log("errorThrown    : " + errorThrown.message);
  //     });
  // });

  // 任意の商品名を保存






  // $('.nouhin_yoteibi_c').datepicker().on('change', function(){
  $(document).on("change", ".nouhin_yoteibi_c", function() {
    if ($(this).val() == '') {
    } else {
      var deal_id = $(".deal_id").first().attr("id");
      var user_id = $(".user_id").first().attr("id");
      var nouhin_yoteibi_c = $(".nouhin_yoteibi_c").val();
      $(".nouhin_yoteibi_c").removeClass("default");
      console.log(deal_id);
      console.log(user_id);
      console.log(nouhin_yoteibi_c);
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: location.origin + '/change_nouhin_yoteibi_c',
        type: 'POST',
        data: {
          'nouhin_yoteibi_c': nouhin_yoteibi_c,
          'user_id': user_id,
          'deal_id': deal_id,
        }
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        // console.log(data);
        // setTimeout(doReload);
        // setTimeout(order_update);
        // setTimeout(dealorder_update);
        // Swal.fire({
        //   type:"success",
        //   title: "納品日を保存しました",
        //   position: 'center-center',
        //   toast: true,
        //   icon: 'success',
        //   showConfirmButton: false,
        //   timer: 1500
        // });
        $('.nouhin_yoteibi_c').removeClass('bg_red');
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('納品日を保存できませんでした。');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
    }
  });

  if(document.URL.match('/confirm')) {
    $(document).on("change", ".charge_form input,.c_uketori_place,.nouhin_yoteibi_c,.uketori_time", function() {

  		// フラグの初期化
      var flag_a = true;
      var flag_b = true;
      let uketori_time = $('#uketori_time').val();


      if ($('#uketori_time').is(':visible') && $('.nouhin_yoteibi_c').is(':visible')) {
		    if ($('#uketori_place').val() !== '' && $('.nouhin_yoteibi_c').val() !== '' && $('#uketori_time').val() !== '') {
		    } else {
          var flag_b = false;
		    }
		  }else if ($('#uketori_time').is(':hidden') && $('.nouhin_yoteibi_c').is(':visible')) {
		    if ($('#uketori_place').val() !== '' && $('.nouhin_yoteibi_c').val() !== '') {

		    } else {
          var flag_b = false;
		    }
		  } else {
		    if ($('#uketori_place').val() !== '') {

		    } else {
          var flag_b = false;
		    }
		  }


      // if ($('#uketori_time').is(':visible') && $('.nouhin_yoteibi_c').is(':visible')) {
		  //   if ($('#uketori_place').val() !== '' && $('.nouhin_yoteibi_c').val() !== '' && $('#uketori_time option:selected').val() !== '') {
      //     // c_uketori_placeに「set」というクラスが付与されていない場合、flag_bをfalseにする
      //     if ($('.nouhin_yoteibi_c.c_set').length > 0) {
      //       console.log('nouhin_yoteibi_c：入力済');
      //       flag_b = true;
      // 		}else{
      //       flag_b = false;
      //     }
      //     if (uketori_time !== null && uketori_time !== "") {
      //       // 選択された値が空でない場合の処理
      //       console.log('uketori_time：入力済');
      //       flag_b = true;
      //     } else {
      //       // 選択された値が空の場合の処理
      //       flag_b = false;
      //     }
		  //   } else {
		  //   }
		  // }else if ($('#uketori_time').is(':hidden') && $('.nouhin_yoteibi_c').is(':visible')) {
		  //   if ($('#uketori_place').val() !== '' && $('.nouhin_yoteibi_c').val() !== '') {
      //     if ($('.nouhin_yoteibi_c.c_set').length > 0) {
      //       console.log('nouhin_yoteibi_c：入力済');
      //       flag_b = true;
      // 		}else{
      //       flag_b = false;
      //     }
		  //   } else {
		  //   }
		  // } else {
		  //   if ($('#uketori_place').val() !== '') {
      // 		flag_b = false;
		  //   } else {
		  //   }
		  // }

      // 項目をひとつずつチェック
      $('.charge_form input').each(function(e) {
          // もし項目が1つでも空なら
          if ($('.charge_form input').eq(e).val() === "") {
              flag_a = false;
          }
      });

  		console.log('flag_a:', flag_a);
  		console.log('flag_b:', flag_b);

      // 全て埋まっていたら
      if (flag_a == true && flag_b == true) {
          // 送信ボタンをアクティブ
          $('#card_approval_btn').removeClass('disabled_btn');
      } else {
          // 送信ボタン解除
          $('#card_approval_btn').addClass('disabled_btn');
      }
    });
  }

  $(document).on("change", ".c_uketori_place", function() {
    c_uketori_place_set();
    // $('.nouhin_yoteibi_c').removeClass('set');
  });

  function c_uketori_place_set() {
    var methodId = $(".c_uketori_place").val();
    var user_id = $(".user_id").first().attr("id");
    var sano_nissuu = $("#sano_nissuu").val();
    console.log(methodId);
    console.log(user_id);
    console.log(sano_nissuu);
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: location.origin + '/get-delivery-times',
        type: 'POST',
        data: {
          'methodId': methodId,
          'user_id': user_id,
          'sano_nissuu': sano_nissuu,
        }
    })
    // Ajaxリクエスト成功時の処理
    .done(function(data) {
      console.log("Data received:", data); // データをコンソールに表示
      if(data.ukewatasibi_nyuuryoku_umu == 1) {

        var dates = data.holidays.map(function(item) {
            return "'" + item.date + "'";
        });
        var holidays = dates.join(',');
        console.log(holidays);

        $('.datepicker').datepicker('destroy'); //Destroy it before re-initing
        $('.nouhin_yoteibi_c').datepicker({
					format: 'yyyy-mm-dd',
					autoclose: true,
					assumeNearbyYear: true,
					language: 'ja',
					startDate: sano_nissuu,
					endDate: '+31d',
          setDate: null,
					// defaultViewDate: Date(),
					// defaultViewDate: { year: null, month: null, day: null },
					datesDisabled: holidays,
				});
        // 表示を有効にする
        $("#c_shipping_date").show();
        // confirmのみ予め設定されている入力値をクリア
        if(document.URL.match("/confirm")) {
          $('.nouhin_yoteibi_c').datepicker('setDate', null);
      		$('.nouhin_yoteibi_c').val('');
      		$('.nouhin_yoteibi_c').addClass('bg_red');
        }
      } else {
        $("#c_shipping_date").hide();
      }
      if(data.ukewatasi_kiboujikan_umu == 1) {
        $("#c_shipping_time").show();
    		$('#uketori_time').val($('#uketori_time option:first').val());
      } else {
        $("#c_shipping_time").hide();
    		$('#uketori_time').val('');
      }
      if(data.shipping_price > 1) {
        $("#c_shipping_price").show();
        var c_shipping_price = data.shipping_price.toLocaleString();
        $('.c_shipping_price').text('¥ ' + c_shipping_price);
        $('input[name="c_shipping_price"]').val(c_shipping_price);

        var shipping_price = $('#c_shipping_price_val').val();
        console.log(shipping_price);

        // 商品合計
        var sum = 0;
        $('.total').each(function () {
          var price = $(this).closest('tr').find('input.price').data('price');
          var quantity = $(this).closest('tr').find('select.quantity').val();
          var total = price * quantity;
          $(this).text(total);
          sum += total;
        });

        // 商品合計
        var itemTotal = sum.toLocaleString();
        $('#item_total').text('¥ ' + itemTotal);

        // 送料税込額
        var shipping_price_zei = Math.floor(shipping_price * 110 / 100);
        console.log(shipping_price_zei);

        // 送料税額
        var shipping_price_zei_only = shipping_price_zei - shipping_price;

    		// 税込合計金額
        var allTotal = Math.floor(sum * 108 / 100) + shipping_price_zei;
        $('#all_total').text('¥ ' + allTotal.toLocaleString());
        $('#all_total_val').val(allTotal);

    		// 税額
        var tax = Math.round(allTotal - sum - shipping_price);
        $('#tax').text('¥ ' + tax.toLocaleString());
        $('#tax_val').val(tax);


        // $('.nouhin_yoteibi_c').datepicker('setDate', null);
        // $('.nouhin_yoteibi_c').val('');
        // $('#change_all_nouhin_yoteibi').datepicker('setDate', null);
        // $('#change_all_nouhin_yoteibi').val('');
        // $('#change_all_nouhin_yoteibi').trigger('change');

      } else {
        $("#c_shipping_price").hide();
        // $('input[name="security_code"]').val('');
        // $('#card_approval_btn').addClass('disabled_btn');

        // 商品合計
        var sum = 0;

        $('.total').each(function () {
          var price = $(this).closest('tr').find('input.price').data('price');
          var quantity = $(this).closest('tr').find('select.quantity').val();
          var total = price * quantity;
          $(this).text(total);
          sum += total;
        });

        // 商品合計
        var itemTotal = sum.toLocaleString();
        $('#item_total').text('¥ ' + itemTotal);

    		// 税込合計金額
        var allTotal = Math.floor(sum * 108 / 100);
        $('#all_total').text('¥ ' + allTotal.toLocaleString());
        $('#all_total_val').val(allTotal);

    		// 税額
        var tax = Math.round(allTotal - sum);
        $('#tax').text('¥ ' + tax.toLocaleString());
        $('#tax_val').val(tax);

      }



      // Swal.fire({
      //   type:"success",
      //   title: "変更完了",
      //   position: 'center-center',
      //   toast: true,
      //   icon: 'success',
      //   showConfirmButton: false,
      //   timer: 1500
      // });
    })
    // Ajaxリクエスト失敗時の処理
    .fail(function(jqXHR, textStatus, errorThrown) {
      alert('配送方法を変更できませんでした。');
      console.log("ajax通信に失敗しました");
      console.log("XMLHttpRequest : " + XMLHttpRequest.status);
      console.log("textStatus     : " + textStatus);
      console.log("errorThrown    : " + errorThrown.message);
    });
  }






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
        // setTimeout(order_update);
        // setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "任意の商品名を保存しました",
          position: 'center-center',
          toast: true,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
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
        // setTimeout(order_update);
        // setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "担当を変更しました",
          position: 'center-center',
          toast: true,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
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

  // 任意の価格変更を保存
  $(document).on("change", ".nini_price", function() {
    var order_nini_id = $(this).parent().parent().get(0).id;
    var nini_price = $(this).val();
    console.log(nini_price);
    console.log(order_nini_id);
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/nini_change_price',
        type: 'POST', //リクエストタイプ
        data: {
          'order_nini_id': order_nini_id,
          'nini_price': nini_price,
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
          title: "価格を変更しました",
          position: 'center-center',
          toast: true,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
        });
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('価格を変更できませんでした。');
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
        // setTimeout(order_update);
        // setTimeout(dealorder_update);
        Swal.fire({
          type:"success",
          title: "数量（単位）を変更しました",
          position: 'center-center',
          toast: true,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
        });
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('個数を変更できませんでした。半角数字で入力してください。');
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
          position: 'center-center',
          toast: true,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
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
    var kaiin_number = $(this).parent().parent().parent().parent().parent().parent().parent().parent().get(0).id;
    var order_nini_id = $(this).parent().parent().get(0).id;
    var nini_store_name = $(this).val();
    var nini_tokuisaki_name = $(this).find('option:selected').get(0).id;
    console.log(kaiin_number);
    console.log(order_nini_id);
    console.log(nini_store_name);
    console.log(nini_tokuisaki_name);
    // 全店舗に追加の分岐
    if(nini_store_name == 'all_store_nini') {
      Swal.fire({
        // title: "納品予定日を変更しました",
        text: "全店舗に追加する場合、この商品の納品先、個数の設定は削除されます。よろしいですか？",
        // position: 'top-end',
        // toast: true,
        icon: 'warning',
        // showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: '#47c363',
        cancelButtonColor: '#3abaf4',
        confirmButtonText: 'はい',
        cancelButtonText: 'いいえ',
        // timer: 1500
      }).then((result) => {
      if (result.value) {
        $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, //Headersを書き忘れるとエラーになる
            url: location.origin + '/nini_add_all_store',
            type: 'POST', //リクエストタイプ
            data: {
              'kaiin_number': kaiin_number,
              'order_nini_id': order_nini_id,
              'nini_store_name': nini_store_name,
              'nini_tokuisaki_name': nini_tokuisaki_name,
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
              position: 'center-center',
              toast: true,
              icon: 'success',
              showConfirmButton: false,
              timer: 1500
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
      }else{
        setTimeout(order_update);
        setTimeout(dealorder_update);
      }
      });
    }else{
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/nini_change_store',
        type: 'POST', //リクエストタイプ
        data: {
          'kaiin_number': kaiin_number,
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
          position: 'center-center',
          toast: true,
          icon: 'success',
          showConfirmButton: false,
          timer: 1500
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
    }
  });




// 顧客側オーダー内容を随時取得
//
// if(document.URL.match("/user/deal")) {
//   $(function(){
//     setInterval(function(){
//
//
//     var prices = $(".price").map(function (index, el) {
//       var prices = $(this).data('price');
//       return (prices);
//     }).get();
//
//     var ids = $(".order_id").map(function (index, el) {
//       var ids = $(this).val();
//       return (ids);
//     }).get();
//
//     console.log(prices);
//     console.log(ids);
//
//     var array = [];
//     for (var i = 0, l = ids.length, obj = Object.create(null); i < l; ++i) {
//       if (prices.hasOwnProperty(i)) {
//         obj[ids[i]] = prices[i];
//       }
//     }
//     array = obj;
//     console.log(array);
//
//     $.ajax({
//         headers: {
//           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }, //Headersを書き忘れるとエラーになる
//         url: location.origin + '/updateorder',
//         type: 'POST', //リクエストタイプ
//         global: false,
//         data: {
//           'array': array,
//         } //Laravelに渡すデータ
//       })
//       // Ajaxリクエスト成功時の処理
//       .done(function(data) {
//         console.log(data);
//         if (data == 0) {
//           location.reload(true);
//       	}
//       })
//       // Ajaxリクエスト失敗時の処理
//       .fail(function(jqXHR, textStatus, errorThrown) {
//         // alert('Ajaxリクエスト失敗');
//         console.log("オーダー内容を取得できません。");
//         console.log("XMLHttpRequest : " + XMLHttpRequest.status);
//         console.log("textStatus     : " + textStatus);
//         console.log("errorThrown    : " + errorThrown.message);
//       });
//     },1000);
// });
// }



// 担当のおすすめ商品グループ名変更（formタグ回避）
if(document.URL.match("/admin/buyer/recommend")) {
  $(document).on("change", ".buyerrecommend_change_groupe_name", function() {
    var tokuisaki_id = $("#tokuisaki_id").val();
    var buyerrecommend_change_groupe_name = $(this).val();
    var buyerrecommend_change_old_groupe_name = $(this).get(0).id;
    console.log(tokuisaki_id);
    console.log(buyerrecommend_change_groupe_name);
    console.log(buyerrecommend_change_old_groupe_name);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }, //Headersを書き忘れるとエラーになる
      url: location.origin + '/admin/buyer/buyerrecommend_change_groupe_name',
      type: 'POST', //リクエストタイプ
      data: {
        'tokuisaki_id': tokuisaki_id,
        'groupe_name': buyerrecommend_change_groupe_name,
        'old_groupe_name': buyerrecommend_change_old_groupe_name,
      } //Laravelに渡すデータ
    })
    // Ajaxリクエスト成功時の処理
    .done(function(data) {
      // Swal.fire({
      //   type:"success",
      //   title: "変更しました",
      //   position: 'center-center',
      //   toast: true,
      //   icon: 'success',
      //   showConfirmButton: false,
      //   timer: 1500
      // });
    })
    // Ajaxリクエスト失敗時の処理
    .fail(function(jqXHR, textStatus, errorThrown) {
      alert('保存できませんでした');
      console.log("ajax通信に失敗しました");
      console.log("XMLHttpRequest : " + XMLHttpRequest.status);
      console.log("textStatus     : " + textStatus);
      console.log("errorThrown    : " + errorThrown.message);
    });
  });


  $(document).on("change", ".uwagaki_item_name", function() {
    var tokuisaki_id = $("#tokuisaki_id").val();
    var buyerrecommend_id = $(this).parent().parent().get(0).id;
    var uwagaki_item_name = $(this).val();
    console.log(tokuisaki_id);
    console.log(buyerrecommend_id);
    console.log(uwagaki_item_name);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }, //Headersを書き忘れるとエラーになる
      url: location.origin + '/admin/buyer/buyerrecommend_change_uwagaki_item_name',
      type: 'POST', //リクエストタイプ
      data: {
        'tokuisaki_id': tokuisaki_id,
        'buyerrecommend_id': buyerrecommend_id,
        'uwagaki_item_name': uwagaki_item_name,
      }
    })
    // Ajaxリクエスト成功時の処理
    .done(function(data) {
    })
    // Ajaxリクエスト失敗時の処理
    .fail(function(jqXHR, textStatus, errorThrown) {
      alert('保存できませんでした');
      console.log("ajax通信に失敗しました");
      console.log("XMLHttpRequest : " + XMLHttpRequest.status);
      console.log("textStatus     : " + textStatus);
      console.log("errorThrown    : " + errorThrown.message);
    });
  });

  $(document).on("change", ".uwagaki_kikaku", function() {
    var tokuisaki_id = $("#tokuisaki_id").val();
    var buyerrecommend_id = $(this).parent().parent().get(0).id;
    var uwagaki_kikaku = $(this).val();
    console.log(tokuisaki_id);
    console.log(buyerrecommend_id);
    console.log(uwagaki_kikaku);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: location.origin + '/admin/buyer/buyerrecommend_change_uwagaki_kikaku',
      type: 'POST',
      data: {
        'tokuisaki_id': tokuisaki_id,
        'buyerrecommend_id': buyerrecommend_id,
        'uwagaki_kikaku': uwagaki_kikaku,
      }
    })
    // Ajaxリクエスト成功時の処理
    .done(function(data) {
    })
    // Ajaxリクエスト失敗時の処理
    .fail(function(jqXHR, textStatus, errorThrown) {
      alert('保存できませんでした');
      console.log("ajax通信に失敗しました");
      console.log("XMLHttpRequest : " + XMLHttpRequest.status);
      console.log("textStatus     : " + textStatus);
      console.log("errorThrown    : " + errorThrown.message);
    });
  });

  // 価格
  $(document).on("change", ".price", function() {
    var tokuisaki_id = $("#tokuisaki_id").val();
    var buyerrecommend_id = $(this).parent().parent().get(0).id;
    var price = $(this).val();

    if (!/^[0-9]+$/.test(price)) {
      // 入力が0から9までの文字で構成される整数ではない場合
      alert("0から9までの半角数字のみを入力してください");
      $(input).val(""); // 入力をクリアする場合はコメントアウトを解除してください
    }

    console.log(tokuisaki_id);
    console.log(buyerrecommend_id);
    console.log(price);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: location.origin + '/admin/buyer/buyerrecommend_change_price',
      type: 'POST',
      data: {
        'tokuisaki_id': tokuisaki_id,
        'buyerrecommend_id': buyerrecommend_id,
        'price': price,
      }
    })
    // Ajaxリクエスト成功時の処理
    .done(function(data) {
    })
    // Ajaxリクエスト失敗時の処理
    .fail(function(jqXHR, textStatus, errorThrown) {
      alert('保存できませんでした');
      console.log("ajax通信に失敗しました");
      console.log("XMLHttpRequest : " + XMLHttpRequest.status);
      console.log("textStatus     : " + textStatus);
      console.log("errorThrown    : " + errorThrown.message);
    });
  });

  // 掲載開始
  // $(document).on("change", ".start", function() {
  //   var tokuisaki_id = $("#tokuisaki_id").val();
  //   var buyerrecommend_id = $(this).parent().parent().get(0).id;
  //   var start = $(this).val();
  //   console.log(tokuisaki_id);
  //   console.log(buyerrecommend_id);
  //   console.log(start);
  //   $.ajax({
  //     headers: {
  //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //     },
  //     url: location.origin + '/admin/buyer/buyerrecommend_change_start',
  //     type: 'POST',
  //     data: {
  //       'tokuisaki_id': tokuisaki_id,
  //       'buyerrecommend_id': buyerrecommend_id,
  //       'start': start,
  //     }
  //   })
  //   .done(function(data) {
  //   })
  //   .fail(function(jqXHR, textStatus, errorThrown) {
  //     alert('保存できませんでした');
  //   });
  // });

  // 掲載終了
  // $(document).on("change", ".end", function() {
  //   var tokuisaki_id = $("#tokuisaki_id").val();
  //   var buyerrecommend_id = $(this).parent().parent().get(0).id;
  //   var end = $(this).val();
  //   console.log(tokuisaki_id);
  //   console.log(buyerrecommend_id);
  //   console.log(end);
  //   $.ajax({
  //     headers: {
  //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //     },
  //     url: location.origin + '/admin/buyer/buyerrecommend_change_end',
  //     type: 'POST',
  //     data: {
  //       'tokuisaki_id': tokuisaki_id,
  //       'buyerrecommend_id': buyerrecommend_id,
  //       'end': end,
  //     }
  //   })
  //   .done(function(data) {
  //   })
  //   .fail(function(jqXHR, textStatus, errorThrown) {
  //     alert('保存できませんでした');
  //   });
  // });

  // 納品期限
  // $(document).on("change", ".nouhin_end", function() {
  //   var tokuisaki_id = $("#tokuisaki_id").val();
  //   var buyerrecommend_id = $(this).parent().parent().get(0).id;
  //   var nouhin_end = $(this).val();
  //   console.log(tokuisaki_id);
  //   console.log(buyerrecommend_id);
  //   console.log(nouhin_end);
  //   $.ajax({
  //     headers: {
  //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //     },
  //     url: location.origin + '/admin/buyer/buyerrecommend_change_nouhin_end',
  //     type: 'POST',
  //     data: {
  //       'tokuisaki_id': tokuisaki_id,
  //       'buyerrecommend_id': buyerrecommend_id,
  //       'nouhin_end': nouhin_end,
  //     }
  //   })
  //   .done(function(data) {
  //   })
  //   .fail(function(jqXHR, textStatus, errorThrown) {
  //     alert('保存できませんでした');
  //   });
  // });

  // 限定店舗
  $(document).on("change", ".gentei_store", function() {
    var tokuisaki_id = $("#tokuisaki_id").val();
    var buyerrecommend_id = $(this).parent().parent().get(0).id;
    var gentei_store = $(this).val();
    console.log(tokuisaki_id);
    console.log(buyerrecommend_id);
    console.log(gentei_store);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: location.origin + '/admin/buyer/buyerrecommend_change_gentei_store',
      type: 'POST',
      data: {
        'tokuisaki_id': tokuisaki_id,
        'buyerrecommend_id': buyerrecommend_id,
        'gentei_store': gentei_store,
      }
    })
    .done(function(data) {
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
      alert('保存できませんでした');
    });
  });

  // 価格非表示
  $(document).on("change", ".hidden_price", function() {
    var tokuisaki_id = $("#tokuisaki_id").val();
    var buyerrecommend_id = $(this).parent().parent().get(0).id;
    if ($(this).is(':checked')) {
      var hidden_price = 1;
      console.log("チェックが入りました");
    } else {
      var hidden_price = null;
      console.log("チェックが外れました");
    }
    console.log(tokuisaki_id);
    console.log(buyerrecommend_id);
    console.log(hidden_price);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: location.origin + '/admin/buyer/buyerrecommend_change_hidden_price',
      type: 'POST',
      data: {
        'tokuisaki_id': tokuisaki_id,
        'buyerrecommend_id': buyerrecommend_id,
        'hidden_price': hidden_price,
      }
    })
    .done(function(data) {
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
      alert('保存できませんでした');
    });
  });

  // 在庫管理しない
  $(document).on("change", ".zaikokanri", function() {
    var tokuisaki_id = $("#tokuisaki_id").val();
    var buyerrecommend_id = $(this).parent().parent().get(0).id;
    if ($(this).is(':checked')) {
      var zaikokanri = 1;
      console.log("チェックが入りました");
    } else {
      var zaikokanri = null;
      console.log("チェックが外れました");
    }
    console.log(tokuisaki_id);
    console.log(buyerrecommend_id);
    console.log(zaikokanri);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: location.origin + '/admin/buyer/buyerrecommend_change_zaikokanri',
      type: 'POST',
      data: {
        'tokuisaki_id': tokuisaki_id,
        'buyerrecommend_id': buyerrecommend_id,
        'zaikokanri': zaikokanri,
      }
    })
    .done(function(data) {
      window.location.reload();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
      alert('保存できませんでした');
    });
  });

  // 在庫数
  $(document).on("change", ".zaikosuu", function() {
    var tokuisaki_id = $("#tokuisaki_id").val();
    var buyerrecommend_id = $(this).parent().parent().get(0).id;
    var zaikosuu = $(this).val();

    if (!/^[0-9]+$/.test(zaikosuu)) {
      // 入力が0から9までの文字で構成される整数ではない場合
      alert("0から9までの半角数字のみを入力してください");
      $(input).val(""); // 入力をクリアする場合はコメントアウトを解除してください
    }

    console.log(tokuisaki_id);
    console.log(buyerrecommend_id);
    console.log(zaikosuu);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: location.origin + '/admin/buyer/buyerrecommend_change_zaikosuu',
      type: 'POST',
      data: {
        'tokuisaki_id': tokuisaki_id,
        'buyerrecommend_id': buyerrecommend_id,
        'zaikosuu': zaikosuu,
      }
    })
    .done(function(data) {
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
      alert('保存できませんでした');
    });
  });

  // 全ての在庫管理にチェックを入れる
  $(document).on("change", ".zaikokanri", function() {
    var tokuisaki_id = $("#tokuisaki_id").val();
    var buyerrecommend_id = $(this).parent().parent().get(0).id;
    if ($(this).is(':checked')) {
      var zaikokanri = 1;
      console.log("チェックが入りました");
    } else {
      var zaikokanri = null;
      console.log("チェックが外れました");
    }
    console.log(tokuisaki_id);
    console.log(buyerrecommend_id);
    console.log(zaikokanri);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: location.origin + '/admin/buyer/buyerrecommend_change_zaikokanri',
      type: 'POST',
      data: {
        'tokuisaki_id': tokuisaki_id,
        'buyerrecommend_id': buyerrecommend_id,
        'zaikokanri': zaikokanri,
      }
    })
    .done(function(data) {
      window.location.reload();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
      alert('保存できませんでした');
    });
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

// 担当のおすすめ商品削除（formタグ回避）
if(document.URL.match("/admin/buyer/recommend")) {
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

// 担当のおすすめ商品複製（formタグ回避）
if(document.URL.match("/admin/user/recommend")) {
  $(function(){
    $(".userduplicatercommend_button").on("click",function(){
      var remove_id = $(this).data('id');
      console.log(remove_id);
      $("#userduplicatercommend_id").val(remove_id);
      $('#userduplicatercommend_form').submit();
    });
  });
}

// 担当のおすすめ商品複製（formタグ回避）
if(document.URL.match("/admin/buyer/recommend")) {
  $(function(){
    $(".buyerduplicatercommend_button").on("click",function(){
      var remove_id = $(this).data('id');
      console.log(remove_id);
      $("#buyerduplicatercommend_id").val(remove_id);
      $('#buyerduplicatercommend_form').submit();
    });
  });
}

// リピートオーダー商品削除（formタグ回避）
// if(document.URL.match("/admin/user/repeatorder")) {
//   $(function(){
//     $(".delete_button").on("click",function(){
//       var cart_id = $(this).parent().parent().parent().parent().parent().parent().get(0).id;
//       var remove_id = $(this).data('id');
//       console.log(cart_id);
//       console.log(remove_id);
//       $("#remove_id").val(remove_id);
//       $("#cart_id").val(cart_id);
//       $('#remove_form').submit();
//     });
//   });
// }



// リピートオーダー停止申請（formタグ回避）
$(".stoprepeatorder").on("click",function(){
  var stop_id = $(this).val();
  console.log(stop_id);
  $("#stop_id").val(stop_id);
  Swal.fire({
    title: '停止申請',
    html : 'このリピートオーダーを本当に停止しますか？<br>※営業担当が状況確認後に停止をいたします。',
    icon : 'warning',
    showCancelButton: true,
	  cancelButtonText: '前の画面に戻る',
    confirmButtonText: '停止申請する'
  }).then(function(result){
    if (result.value) {
      // Swal.fire({
      //   type:"success",
      //   title: "キャンセル処理を行いました。",
      //   position: 'center-center',
      //   toast: true,
      //   icon: 'success',
      //   showConfirmButton: false,
      //   timer: 1500
      // });
      $('#stop_repeatorder').submit();
    }
  });
});




// 取引キャンセル前のポップアップ
$("#deal_cancel_button").on("click",function(e){
  Swal.fire({
    title: 'キャンセル確認',
    html : 'この取引を本当にキャンセルしますか？',
    icon : 'warning',
    showCancelButton: true,
	  cancelButtonText: '前の画面に戻る',
    confirmButtonText: 'キャンセルする'
  }).then(function(result){
    if (result.value) {
      // Swal.fire({
      //   type:"success",
      //   title: "キャンセル処理を行いました。",
      //   position: 'center-center',
      //   toast: true,
      //   icon: 'success',
      //   showConfirmButton: false,
      //   timer: 1500
      // });
      $('#deal_cancel').submit();
    }
  });
});

// SETOnagiユーザーの利用を許可する
$(".riyoukyoka_btn").click(function(e){
  e.preventDefault();
  var form = $(this).parents('form');
  Swal.fire({
    title: '送信確認',
    html : 'ユーザーにメールを送信して利用を許可しますか？',
    icon : 'warning',
    showCancelButton: true,
	  cancelButtonText: 'キャンセル',
    confirmButtonText: '利用を許可'
  }).then((result) => {
    if (result.value) {
      form.submit();
    }
  });
});

// $("#btn09").click(function(){
//   Swal.fire({
//     title: "好きなタイトルを入力",
//     text: "好きなテキストを入力",
//     input: "text",
//     confirmButtonText: '送信',
//     allowOutsideClick: false
//   }).then(function(result){
//     if (result.value) {
//       Swal.fire({
//         type: 'success',
//         title: '送信は成功しました',
//         html: ' 送信内容：' + result.value
//       });
//     }
//   });
// });




    $(document).on("change", "#houjin_kojin", function() {
      var selected = $(this).val();
      alert('test');
          console.log(selected);
      // if ( selected === '法人' ) {
      //   alert($('#houjin_kojin').val());
      //   $('#maekabu').show();
      // }
    });

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
    // update_field();

      // var target = $('.total').map(function (index, el) {
      // $(this).closest('tr').find('.total').text(
      // $(this).closest('tr').find('input.price').val() *
      // $(this).closest('tr').find('select.quantity').val());});
      // console.log(target);

      // var sum = 0;
      // $('.total').each(function () {
      //     sum += parseInt(this.innerText);
      //     var item_total = $('#item_total').map(function (index, el) {
      //     $(this).text("¥ "+ sum.toLocaleString() );});
      //
      //     var all_total = $('#all_total').map(function (index, el) {
      //     var all_total = sum * 1.1;
      //     var all_total = Math.round(all_total);
      //     $(this).text("¥ "+ all_total.toLocaleString());});
      //
      //     var tax = $('#tax').map(function (index, el) {
      //     var tax = sum * 1.1 - sum;
      //     var tax = Math.round(tax);
      //     $(this).text("¥ "+ tax.toLocaleString());});
      // });
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
