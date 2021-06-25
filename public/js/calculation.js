/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */



  // カートに追加
$(function() {

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



  //HOME画面でカートの外側をクリックしたときの処理
  document.addEventListener('click', (e) => {
    if(!e.target.closest('.dropdown-list-content')) {
      $('#cart-container').removeClass("show");
      $('#dropdown').removeClass("show");
    } else {
      //ここに内側をクリックしたときの処理
    }
  })






  // 配送先を追加
  $(document).on("click", ".clonecart", function() {

    var item_id = $(this).get(0).id;
    // $('.cloneid_'+item_id).parent().parent().clone(true).insertAfter($(this).parent().parent().prev());
    $(this).parent().parent().clone(true).insertAfter($(this).parent().parent().prev());
    console.log(item_id);

      // $.ajax({
      //   headers: {
      //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      //   }, //Headersを書き忘れるとエラーになる
      //   url: location.origin + '/clonecart',
      //   type: 'POST', //リクエストタイプ
      //   data: {
      //     'item_id': item_id,
      //   } //Laravelに渡すデータ
      // })
      // // Ajaxリクエスト成功時の処理
      // .done(function(data) {
      //   console.log(data);
      // })
      // // Ajaxリクエスト失敗時の処理
      // .fail(function(jqXHR, textStatus, errorThrown) {
      //   alert('Ajaxリクエスト失敗');
      //   console.log("ajax通信に失敗しました");
      //   console.log("XMLHttpRequest : " + XMLHttpRequest.status);
      //   console.log("textStatus     : " + textStatus);
      //   console.log("errorThrown    : " + errorThrown.message);
      // });
  });


  // カートから削除

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
});




// 単品料理追加
$(document).on("click", ".add", function() {
    $(this).prev().find(".box:last").clone(true).insertAfter($(this).prev());
});
$(document).on("click", ".del", function() {
    var target = $(this).parent().parent();
    if (target.parent().children().length > 1) {
        target.remove();
    }
});





if(!document.URL.match(/confirm/)){



}



// 合計金額アップデート
function update_field(){
    $('input').on('keyup change',function(){

      var target = $('input').map(function (index, el) {
      $(this).closest('tr').find('.total').text(
      $(this).closest('tr').find('input.teika').val() *
      $(this).closest('tr').find('input.quantity').val());});
      console.log(target);

    var sum = 0;
    $('.total').each(function () {
        sum += parseInt(this.innerText);
        var item_total = $('#item_total').map(function (index, el) {
        var item_total = Number(item_total).toLocaleString()
        $(this).text("¥ "+ sum.toLocaleString() );});

        var all_total = $('#all_total').map(function (index, el) {
        var all_total = sum * 1.1;
        var all_total = Math.round(all_total);
        $(this).text("¥ "+ all_total.toLocaleString());});

        var tax = $('#tax').map(function (index, el) {
        var tax = sum * 1.1 - sum;
        var tax = Math.round(tax);
        $(this).text("¥ "+ tax.toLocaleString());});
    });


    if(document.URL.match(/admin/)){
      var discount = $(this).closest('tr').find('input.discount').val();
      var quantity = $(this).closest('tr').find('input.quantity').val();
      var cart_id = $(this).closest('tr').find('.cart_id').val();

      $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }, //Headersを書き忘れるとエラーになる
          url: location.origin + '/admin/updatecart',
          type: 'POST', //リクエストタイプ
          data: {
            'discount': discount,
            'quantity': quantity,
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
      }

    if(document.URL.match(/user/)){
      var discount = $(this).closest('tr').find('input.teika').val();
      var quantity = $(this).closest('tr').find('input.quantity').val();
      var cart_id = $(this).closest('tr').find('input.cart_id').val();

      $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }, //Headersを書き忘れるとエラーになる
          url: location.origin + '/updatecart',
          type: 'POST', //リクエストタイプ
          data: {
            'discount': discount,
            'quantity': quantity,
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
      }
   });
}

// 合計金額アップデート画面を開たとき
$(document).ready( function(){
    update_field();

      var target = $('.total').map(function (index, el) {
      $(this).closest('tr').find('.total').text(
      $(this).closest('tr').find('input.teika').val() *
      $(this).closest('tr').find('input.quantity').val());});
      // console.log(target);

      var sum = 0;
      $('.total').each(function () {
          sum += parseInt(this.innerText);
          var item_total = $('#item_total').map(function (index, el) {
          $(this).text("¥ "+ sum.toLocaleString() );});

          var all_total = $('#all_total').map(function (index, el) {
          var all_total = sum * 1.1;
          var all_total = Math.round(all_total);
          $(this).text("¥ "+ all_total.toLocaleString());});

          var tax = $('#tax').map(function (index, el) {
          var tax = sum * 1.1 - sum;
          var tax = Math.round(tax);
          $(this).text("¥ "+ tax.toLocaleString());});
      });


});




// value値を取得
// const str1 = $(".store").val();
// alert(str1);
// alert('JavaScriptのアラート');



// if(document.URL.match(/user/)){
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
