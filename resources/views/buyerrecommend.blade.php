
@extends('layouts.app')

@section('content')
<!--
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-datetimepicker/build/jquery.datetimepicker.min.css">

<script src="https://cdn.jsdelivr.net/npm/jquery-datetimepicker/js/locales/jquery.datetimepicker.ja.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js"></script> -->




<section class="section">
  <div class="section-header">
    <h1>得意先ごとのおすすめ商品登録</h1>
    <div class="section-header-breadcrumb">
      @if ( Auth::guard('user')->check() )
      <div class="breadcrumb-item"><a href="{{ url('/') }}">HOME</a></div>
      <div class="breadcrumb-item">得意先ごとのおすすめ商品</div>
      @endif
      @if ( Auth::guard('admin')->check() )
      <div class="breadcrumb-item active"><a href="{{ url('/admin/home') }}">HOME</a></div>
      <div class="breadcrumb-item"><a href="{{ url('/admin/buyer') }}">得意先一覧</a></div>
      <div class="breadcrumb-item">得意先ごとのおすすめ商品登録（{{$store->tokuisaki_name}} 様）</a></div>
      @endif
    </div>
  </div>
  <div class="section-body">
    <div class="invoice">
      <div class="invoice-print">
        <div class="row">
          <div class="col-lg-12">
            <div class="invoice-title">
              <h3>{{$store->tokuisaki_name}} <span class="small">様</span></h3>
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <div class="section-title">得意先ごとのおすすめ商品</div>
            <div class="clearfix mb-3"></div>




<!-- 添付のソースを、以下のように変更したい。
・「buyerrecommends」は、グループごとにまとめられた配列に変更、配列名は、「groupedItems」とする。
・グループ名を出力するソースは、「$buyerrecommend->groupe」とする。
・テーブルレコードごとに、グループ名でまとめ、各グループの上にグループ名のテーブル行を作成するものとする。 -->


            <form id="buyerrecommend" action="{{ url('/admin/buyer/saverecommend') }}" enctype="multipart/form-data" method="POST" class="form-horizontal">
              @csrf
              <div class="table-responsive">
                <table class="table table-striped">
                <thead>
                  <tr>
                    <!-- <th class="text-center">並び順</th> -->
                    <!-- <th class="text-center">ソート</th> -->
                    <th class="text-center">商品番号</th>
                    <th class="text-center">商品名</th>
                    <th class="text-center">産地</th>
                    <th class="text-center">規格</th>
                    <th class="text-center">単位</th>
                    <th class="text-center">在庫数</th>
                    <th class="text-center price">単価</th>
                    <th class="text-center start">掲載開始</th>
                    <th class="text-center end">掲載終了</th>
                    <th class="text-center nouhin_end">納品期限</th>
                    <th class="text-center">限定店舗</th>
                    <th class="text-center">操作</th>
                    <th class="text-center">価格非表示<input type="checkbox" id="all_hidden_price" name="all_hidden_price"></th>
                    <th class="text-center">在庫管理しない<input type="checkbox" id="all_zaikokanri" name="all_zaikokanri"></th>
                    <th class="text-center">限定数</th>
                  </tr>
                </thead>
                <tbody id="sortdata">
                  @foreach($groupedItems as $group => $buyerrecommends)
                      <tr id="{{$group}}" class="groupeditems">
                          <input type="hidden" name="buyerrecommend[{{$group}}][order_no]" class="order_no text-center form-control" value="">
                          <th colspan="15" class=""><input id="{{ $group }}" type="text" name="buyerrecommend_change_groupe_name" class="buyerrecommend_change_groupe_name form-control" value="{{ $group }}"></th>
                      </tr>
        	            @foreach($buyerrecommends as $buyerrecommend)
                      <tr id="{{$buyerrecommend->id}}">
                        <!-- オーダー番号を記録 -->
                        <input type="hidden" name="buyerrecommend[{{$buyerrecommend->id}}][order_no]" class="order_no text-center form-control" value="{{$buyerrecommend->order_no}}">
                        <!-- <td class="text-center">
                          <span class="ui-icon ui-icon-arrowthick-2-n-s ui-corner-all ui-state-hover">↑↓</span>
                        </td> -->
                        <td class="text-center">
                          {{$buyerrecommend->item()->item_id}}
                        </td>
                        <td class="text-center">
                          <input type="text" id="uwagaki_item_name" class="uwagaki_item_name form-control text-center" name="buyerrecommend[{{$buyerrecommend->id}}][uwagaki_item_name]" value="{{$buyerrecommend->uwagaki_item_name()}}">
                        </td>
                        <td class="text-center">
                          {{$buyerrecommend->item()->sanchi_name}}
                        </td>
                        <td class="text-center">
                          <input type="text" id="uwagaki_kikaku" class="uwagaki_kikaku form-control text-center" name="buyerrecommend[{{$buyerrecommend->id}}][uwagaki_kikaku]" value="{{$buyerrecommend->uwagaki_kikaku()}}">
                        </td>
                        <td class="text-center">
                          @if ($buyerrecommend->item()->tani == 1)
            							ｹｰｽ
            							@elseif ($buyerrecommend->item()->tani == 2)
            							ﾎﾞｰﾙ
            							@elseif ($buyerrecommend->item()->tani == 3)
            							ﾊﾞﾗ
            							@elseif ($buyerrecommend->item()->tani == 4)
            							Kg
            							@endif
                        </td>
                        <td class="text-center">
                          {{$buyerrecommend->item()->zaikosuu}}
                        </td>
                        <td class="text-center">
                          <input pattern="^[0-9]+$" name="buyerrecommend[{{$buyerrecommend->id}}][price]" class="price text-center form-control" value="{{$buyerrecommend->price}}" title="0から9の半角数字" required>
                        </td>
                        <td class="text-center">
                          <input type="text" name="buyerrecommend[{{$buyerrecommend->id}}][start]" class="start text-center form-control daterange-cus start_datepicker" value="{{$buyerrecommend->start}}" autocomplete="off" required>
                        </td>
                        <td class="text-center">
                          <input type="text" name="buyerrecommend[{{$buyerrecommend->id}}][end]" class="end text-center form-control daterange-cus end_datepicker" value="{{$buyerrecommend->end}}" autocomplete="off" required>
                        </td>
                        <td class="text-center">
                          <input type="text" name="buyerrecommend[{{$buyerrecommend->id}}][nouhin_end]" class="nouhin_end text-center form-control daterange-cus nouhin_end_datepicker" value="{{$buyerrecommend->nouhin_end}}" autocomplete="off" required>
                        </td>
                        <td class="text-center">
                          <select name="buyerrecommend[{{$buyerrecommend->id}}][gentei_store]" class="gentei_store text-center form-control" value="{{$buyerrecommend->store}}">
                            <option id="{{$buyerrecommend->gentei_store}}" value="{{$buyerrecommend->gentei_store}}">{{$buyerrecommend->gentei_store}}</option>
                              <option value="">全ての店舗</option>
                            @foreach($stores as $store)
                  						<option value="{{$store->store_name}}">{{$store->store_name}}</option>
                  					@endforeach
                          </select>
                        </td>
                        <td class="text-center">
                          <div class="btn btn-primary delete_button" data-id="{{$buyerrecommend->id}}"/>削除</div>
                          <!-- <div class="addrecommend btn btn-success" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> 追加</div> -->
                          <div class="btn btn-success buyerduplicatercommend_button" data-id="{{$buyerrecommend->id}}"><i class="fas fa-plus"></i> 複製</div>
                        </td>
                        <td class="text-center">
                          <input type="checkbox" id="hidden_price" class="hidden_price" name="buyerrecommend[{{$buyerrecommend->id}}][hidden_price]" @if($buyerrecommend->hidden_price == '1') checked @endif>
                        </td>
                        <td class="text-center">
                          <input type="checkbox" id="zaikokanri" class="zaikokanri" name="buyerrecommend[{{$buyerrecommend->id}}][zaikokanri]" @if($buyerrecommend->zaikokanri == '1') checked @endif>
                        </td>
                        <td class="text-center">
                          @if($buyerrecommend->zaikokanri == '1')
                          @else
                          <input type="text" id="zaikosuu" class="zaikosuu form-control text-center" name="buyerrecommend[{{$buyerrecommend->id}}][zaikosuu]" value="{{$buyerrecommend->zaikosuu}}">
                          @endif
                        </td>
                      </tr>
                      @endforeach
                  @endforeach
                </tbody>
                </table>
              </div>
              <input id="tokuisaki_id" name="tokuisaki_id" type="hidden" value="{{$id}}">
              <button type="submit" class="save_btn btn btn-warning float-right">内容を保存</button>
            </form>
            <!-- <a href="{{ url('/admin/buyer/recommend/') }}/{{$store->tokuisaki_id}}/add" class="addrecommend btn btn-success"><i class="fas fa-plus"></i> 商品を追加</a> -->
            <button class="addendrecommend btn btn-success" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> 商品を追加</button>
            <!-- <button class="addendrecommend btn btn-success" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> カテゴリを追加</button> -->
          </div>
        </div>
      </div>
      <hr>
    </div>
  </div>
</section>

<form id="buyerrecommend_change_groupe_name_form" action="{{ url('/admin/buyer/buyerrecommend_change_groupe_name') }}" method="POST">
  @csrf
  <input type="hidden" name="tokuisaki_id" type="hidden" value="{{$id}}">
  <input id="buyerrecommend_change_old_groupe_name" name="old_groupe_name" type="hidden" value="">
  <input id="buyerrecommend_change_groupe_name" name="groupe_name" type="hidden" value="">
</form>

<form id="buyerduplicatercommend_form" action="{{ url('/admin/buyer/duplicatercommend') }}" method="POST">
  @csrf
  <input type="hidden" name="tokuisaki_id" type="hidden" value="{{$id}}">
  <input id="buyerduplicatercommend_id" name="duplicate" type="hidden" value="">
</form>

<form id="remove_form" action="{{ url('/admin/buyer/removercommend') }}" method="POST">
  @csrf
  <input type="hidden" name="tokuisaki_id" type="hidden" value="{{$id}}">
  <input id="remove_id" name="delete" type="hidden" value="">
</form>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header serch-form-header">
        <h5 class="modal-title" id="exampleModalLabel">商品一覧</h5>
        <!-- 検索窓 -->
        <div class="search-text">
          <form>
            <div class="input-group">
              <input type="text" id="search-text" value="{{$item_search}}" name="item_search" class="form-control" placeholder="検索">
              <input type="hidden" class="ordernosave" name="ordernosave" value="{{$order_no}}"/>
              <div class="input-group-append">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
              </div>
            </div>
          </form>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <!-- 検索リスト -->
        <div class="search-result">
          <div class="search-result__hit-num"></div>
        </div>

        <form id="buyerrecommend" action="{{ url('/admin/buyer/addrecommend') }}" method="POST" class="form-horizontal">
          {{ csrf_field() }}
          <div class="table-responsive">
            <input type="hidden" class="item_search" value="{{$item_search}}" name="item_search" class="form-control">
            <table id="search-result__list" class="table table-striped">

            </table>
            <table class="table table-striped target-area">
              <tr>
                <th class="text-center">商品番号</th>
                <th class="">商品名</th>
                <th class="text-center">産地</th>
                <th class="text-center">規格</th>
                <th class="text-center">単位</th>
                <th class="text-center">在庫数</th>
                <!-- <th class="text-center">特記事項</th> -->
                <!-- <th class="text-center">納品予定日</th>
                <th class="text-center">参考価格</th>
                <th class="text-center">個数</th> -->
                <th class="text-center" style="min-width:130px;">操作</th>
              </tr>
              @foreach($items as $item)
              <tr>
                <td class="text-center">{{$item->item_id}}</td>
                <td class="">{{$item->item_name}}</td>
                <td class="text-center">{{$item->sanchi_name}}</td>
                <td class="text-center">{{$item->kikaku}}</td>
                <td class="text-center">
                  @if ($item->tani == 1)
                  ｹｰｽ
                  @elseif ($item->tani == 2)
                  ﾎﾞｰﾙ
                  @elseif ($item->tani == 3)
                  ﾊﾞﾗ
                  @elseif ($item->tani == 4)
                  Kg
                  @endif
                </td>
                <td class="text-center">{{$item->zaikosuu}}</td>
                <!-- <td class="text-center">{{$item->tokkijikou}}</td> -->

                <!-- <td class="text-center">{{$item->nouhin_yoteibi_start}}</td>
                <td class="text-center">
                  ¥ {{$item->teika}}
                </td>
                <td class="text-center"><input name="quantity" class="quantity form-control" value="1"></td> -->
                <td class="text-center"><button id="{{$item->id}}" name="item_id" type="submit" value="{{$item->id}}" class="btn btn-warning">追加</button></td>
              </tr>
              @endforeach
            </table>
          </div>
          <input type="hidden" name="tokuisaki_id" value="{{$id}}" />
          <input type="hidden" class="ordernosave" name="ordernosave" value="{{$order_no}}"/>
        </form>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>






 <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->


<style>
.datepicker{
  background-color: #fff !important;
  border: 1px solid #c7e2fe !important;
}
</style>




<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script> -->
<script>
// URLを取得
var url = new URL(window.location.href);
// URLSearchParamsオブジェクトを取得
var params = url.searchParams;
if( params.has('item_search') ) {
  console.log(params.get('item_search'));
  $(function() {
    $('#exampleModal').modal('show');
  });
}
</script>
<script>


  // $('.datepicker').datetimepicker({
  //   format: 'Y-m-d H:i:s',
  //   step: 60, // 15 minute intervals
  //   timepickerScrollbar: false,
  //   timepicker: false, // 時間の選択を無効化
  //   language: 'ja',
  //   onSelectDate: function(ct, $i) {
  //     var start = $i.val();
  //     var tokuisaki_id = $("#tokuisaki_id").val();
  //     var buyerrecommend_id = $i.attr('id');
  //     console.log(tokuisaki_id);
  //     console.log(buyerrecommend_id);
  //     console.log(start);
  //     $.ajax({
  //       headers: {
  //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //       },
  //       url: location.origin + '/admin/buyer/buyerrecommend_change_start',
  //       type: 'POST',
  //       data: {
  //         'tokuisaki_id': tokuisaki_id,
  //         'buyerrecommend_id': buyerrecommend_id,
  //         'start': start,
  //       }
  //     })
  //     .done(function(data) {
  //     })
  //     .fail(function(jqXHR, textStatus, errorThrown) {
  //       alert('保存できませんでした');
  //     });
  //   }
  // });

  $('.start_datepicker').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    language: 'ja',
    }).on('changeDate', function(e) {
    var selectedDate = e.format('yyyy-mm-dd');
    var start = selectedDate + ' 00:00:00';
    var tokuisaki_id = $("#tokuisaki_id").val();
    var buyerrecommend_id = $(this).parent().parent().get(0).id;
    console.log(start);
    console.log(tokuisaki_id);
    console.log(buyerrecommend_id);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: location.origin + '/admin/buyer/buyerrecommend_change_start',
      type: 'POST',
      data: {
        'tokuisaki_id': tokuisaki_id,
        'buyerrecommend_id': buyerrecommend_id,
        'start': start,
      }
    })
    .done(function(data) {
      // 成功時の処理
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
      alert('保存できませんでした');
    });
  });



$('.end_datepicker').datepicker({
	format: 'yyyy-mm-dd',
	autoclose: true,
	language: 'ja',

}).on('changeDate', function(e) {
var selectedDate = e.format('yyyy-mm-dd');
var end = selectedDate + ' 17:00:00';
var tokuisaki_id = $("#tokuisaki_id").val();
var buyerrecommend_id = $(this).parent().parent().get(0).id;
console.log(end);
console.log(tokuisaki_id);
console.log(buyerrecommend_id);
$.ajax({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },
  url: location.origin + '/admin/buyer/buyerrecommend_change_end',
  type: 'POST',
  data: {
    'tokuisaki_id': tokuisaki_id,
    'buyerrecommend_id': buyerrecommend_id,
    'end': end,
  }
})
.done(function(data) {
  // 成功時の処理
})
.fail(function(jqXHR, textStatus, errorThrown) {
  alert('保存できませんでした');
});

});


$('.nouhin_end_datepicker').datepicker({
  format: 'yyyy-mm-dd',
  autoclose: true,
  language: 'ja',
}).on('changeDate', function(e) {
  var selectedDate = e.format('yyyy-mm-dd');
  var nouhin_end = selectedDate + ' 00:00:00';
  var tokuisaki_id = $("#tokuisaki_id").val();
  var buyerrecommend_id = $(this).parent().parent().get(0).id;
  console.log(nouhin_end);
  console.log(tokuisaki_id);
  console.log(buyerrecommend_id);
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: location.origin + '/admin/buyer/buyerrecommend_change_nouhin_end',
    type: 'POST',
    data: {
      'tokuisaki_id': tokuisaki_id,
      'buyerrecommend_id': buyerrecommend_id,
      'nouhin_end': nouhin_end,
    }
  })
  .done(function(data) {
    // 成功時の処理
  })
  .fail(function(jqXHR, textStatus, errorThrown) {
    alert('保存できませんでした');
  });
});

</script>
<script>

$(function() {
	// inputにフォーカス時、readonlyに変更
	$('.datepicker')
	.focusin(function(e) {
		$(this).attr('readOnly', 'tlue');
	})
	.focusout(function(e) {
		$(this).removeAttr('readOnly');
	});
});

</script>
<script>
$(window).on('load',function(){
  $('#sortdata').find('.order_no').each(function(idx){
    // タグ内に通し番号を設定（idxは0始まりなので+1する）
    $(this).val(idx+1);
  });
});
$('#sortdata').sortable({
    // handle: 'span',
});

// ソート完了時に発火
$('#sortdata').bind('sortstop',function(){
  // 番号を設定している要素に対しループ処理
  $(this).find('.order_no').each(function(idx){
    // タグ内に通し番号を設定（idxは0始まりなので+1する）
    $(this).val(idx+1);
  });
  $('.save_btn').click();
});


// $('#sortdata').bind('sortstop',function(){
//   // 番号を設定している要素に対しループ処理
//   $(this).find('.order_no').each(function(idx){
//     // タグ内に通し番号を設定（idxは0始まりなので+1する）
//     $(this).val(idx+1);
//     $('.save_btn').click();
//   });
// });

document.getElementById('buyerrecommend').addEventListener('submit', function(event) {
  event.preventDefault(); // フォームのデフォルトの送信をキャンセル

  // フォームのデータを取得
  var formData = new FormData(event.target);

  // フォームのデータをオブジェクトに変換
  var formDataObject = {};
  for (var pair of formData.entries()) {
    var key = pair[0];
    var value = pair[1];

    // buyerrecommend_change_groupe_name、tokuisaki_id、_tokenは残す
    if (key === 'buyerrecommend_change_groupe_name' || key === 'tokuisaki_id' || key === '_token' || key === 'all_zaikokanri' || key === 'all_hidden_price') {
      formDataObject[key] = value;
    }

    // [price]と[order_no]のみを残して送信する
    if (key.includes('buyerrecommend') && (key.includes('[price]') || key.includes('[order_no]'))) {
      formDataObject[key] = value;
    }
  }

  console.log(formDataObject);

  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: location.origin + '/admin/buyer/saverecommend',
    type: 'POST',
    data: formDataObject,
  })
  .done(function(data) {
    if (data.status === 'reload') {
      window.location.reload();
    } else {

    }
    console.log(data);
  })
  .fail(function(jqXHR, textStatus, errorThrown) {
    alert('保存できませんでした');
  });

  // 例：formDataObjectの中身を表示する
  // console.log(formDataObject);

});

// $('#sortdata').bind('sortstop',function(){
//   var data = {}; // データを格納するオブジェクト
//   // 番号を設定している要素に対しループ処理
//   $(this).find('.order_no').each(function(idx){
//     var parentId = $(this).parent().attr('id'); // １つ上の親要素のIDを取得
//     var number = idx + 1; // タグ内の番号
//     data[parentId] = number; // 親要素のIDをキーとして、番号を格納
//   });
//   console.log(data); // データの確認（開発者ツールのコンソールに表示）
//   $.ajax({
//     headers: {
//       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//     },
//     url: location.origin + '/admin/buyer/buyerrecommend_change_sort',
//     type: 'POST',
//     data: {
//       'tokuisaki_id': tokuisaki_id,
//       'data': data,
//     }
//   })
//   .done(function(data) {
//     // 成功時の処理
//   })
//   .fail(function(jqXHR, textStatus, errorThrown) {
//     alert('保存できませんでした');
//   });
// });



$('#all_zaikokanri,#all_hidden_price').change(function() {
    $('.save_btn').click();
});






$(window).scroll(function() {
  sessionStorage.scrollTop = $(this).scrollTop();
});

$(document).ready(function() {
  if (sessionStorage.scrollTop != "undefined") {
    $(window).scrollTop(sessionStorage.scrollTop);
  }
});



// $(document).ready(function() {
//   $('#buyerrecommend').submit(function(event) {
//     event.preventDefault(); // フォームのデフォルトの送信を防止
//
//     var formData = new FormData(this);
//
//     // 特定の値（order_no）以外のデータを削除
//     formData.delete('buyerrecommend_change_groupe_name');
//     formData.delete('all_hidden_price');
//     formData.delete('all_zaikokanri');
//
//     // 特定の値以外のデータを削除
//     $('input.order_no').each(function() {
//       var name = $(this).attr('name');
//       if (name && name.indexOf('buyerrecommend') === 0) {
//         formData.delete(name);
//       }
//     });
//
//     // フォームデータをサーバーサイドのスクリプトに送信
//     $.ajax({
//       type: 'POST',
//       url: '{{ url('/admin/buyer/saverecommend') }}',
//       data: formData,
//       processData: false,
//       contentType: false,
//       success: function(response) {
//         // 成功時の処理
//         console.log(response);
//       },
//       error: function(xhr, status, error) {
//         // エラー時の処理
//         console.log(error);
//       }
//     });
//   });
// });


</script>




@endsection
