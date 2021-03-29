/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

 $(document).ready(function(){
   var deal_id = $('#deal_id').val();

   $.ajax({
       headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }, //Headersを書き忘れるとエラーになる
       url: location.origin + '/dealcart',
       type: 'POST', //リクエストタイプ
       data: {
         'deal_id': deal_id,
       }, //Laravelに渡すデータ
       cache: false, // キャッシュしないで読み込み
       // 通信成功時に呼び出されるコールバック
       success: function (data) {
             $('#dealcart').html(data);
       },
       // 通信エラー時に呼び出されるコールバック
       error: function () {
           alert("Ajax通信エラー");
       }
   });
 });

$(function(){
  setInterval(function(){
    var deal_id = $('#deal_id').val();

    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, //Headersを書き忘れるとエラーになる
        url: location.origin + '/dealcart',
        type: 'POST', //リクエストタイプ
        data: {
          'deal_id': deal_id,
        }, //Laravelに渡すデータ
        cache: false, // キャッシュしないで読み込み
        // 通信成功時に呼び出されるコールバック
        success: function (data) {
              $('#dealcart').html(data);
        },
        // 通信エラー時に呼び出されるコールバック
        error: function () {
            alert("Ajax通信エラー");
        }
    });
  },3000);





});
