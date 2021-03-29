/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */




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
  });

  // jQueryを使う方法
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
  }
  $('#toggle').click(function(){
    $(this).removeClass("beep");
      setTimeout(dojQueryAjax);
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
  });
});







// 合計金額アップデート
function update_field(){
    $('input').on('keyup change',function(){

      var target = $('input').map(function (index, el) {
      $(this).closest('tr').find('.total').text(
      $(this).closest('tr').find('input:eq(0)').val() *
      $(this).closest('tr').find('input:eq(1)').val());});
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
      var discount = $(this).closest('tr').find('input:eq(0)').val();
      var quantity = $(this).closest('tr').find('input:eq(1)').val();
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

    if(document.URL.match(/deal/)){
      var discount = $(this).closest('tr').find('input:eq(0)').val();
      var quantity = $(this).closest('tr').find('input:eq(1)').val();
      var cart_id = $(this).closest('tr').find('.cart_id').val();

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

      var target = $('input').map(function (index, el) {
      $(this).closest('tr').find('.total').text(
      $(this).closest('tr').find('input:eq(0)').val() *
      $(this).closest('tr').find('input:eq(1)').val());});
      console.log(target);


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
