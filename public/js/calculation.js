/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */


$(function() {

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
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        console.log(data);
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('Ajaxリクエスト失敗');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
      $('#toggle').addClass('beep');
      $('#toggle').trigger('click');
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
              // alert("Ajax通信エラー");
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
    var item_id = $(this).get(0).id;
    $('.removeid_'+item_id).parent().parent().remove();
    console.log(item_id);

    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/removecart',
        type: 'POST', //リクエストタイプ
        data: {
          'item_id': item_id,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        console.log(data);
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('Ajaxリクエスト失敗');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
      // 送信画面のときは、カートのポップアップを出さない
      if(!document.URL.match(/confirm/)){
      $('#toggle').addClass('beep');
      $('#toggle').trigger('click');
      }
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
        alert('Ajaxリクエスト失敗');
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
        alert('Ajaxリクエスト失敗');
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
              // alert("Ajax通信エラー");
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
        console.log(data);
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('Ajaxリクエスト失敗');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
      setTimeout(order_update);
      setTimeout(dealorder_update);
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
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('Ajaxリクエスト失敗');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
      setTimeout(order_update);
      setTimeout(dealorder_update);
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
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('Ajaxリクエスト失敗');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
      setTimeout(order_update);
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
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('Ajaxリクエスト失敗');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
      setTimeout(order_update);
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
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('Ajaxリクエスト失敗');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      });
      setTimeout(order_update);
  });





  if(document.URL.match("/user/deal")) {
  $(document).on("click", ".updateorder", function() {

    // var prices = $(".price").map(function (index, el) {
    //   return $(this).val();
    // });

    var prices = 'text';
    console.log(prices);

    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/prices',
        type: 'POST', //リクエストタイプ
        data: {
          'prices': prices,
        } //Laravelに渡すデータ
      })
      // Ajaxリクエスト成功時の処理
      .done(function(data) {
        console.log(data);
      })
      // Ajaxリクエスト失敗時の処理
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert('Ajaxリクエスト失敗');
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
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
