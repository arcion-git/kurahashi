@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>リピートオーダー</h1>
    <div class="section-header-breadcrumb">
      @if ( Auth::guard('user')->check() )
      <div class="breadcrumb-item"><a href="{{ url('/') }}">HOME</a></div>
      <div class="breadcrumb-item">リピートオーダー</div>
      @endif
      @if ( Auth::guard('admin')->check() )
      <div class="breadcrumb-item active"><a href="{{ url('/admin/home') }}">HOME</a></div>
      <div class="breadcrumb-item"><a href="{{ url('/admin/user') }}">顧客一覧</a></div>
      <div class="breadcrumb-item">リピートオーダー登録（{{$user->name}} 様）</a></div>
      @endif
    </div>
  </div>
  <div class="section-body repeatorder">
    <div class="invoice">
      <div class="invoice-print">
        <div class="row">
          <div class="col-lg-12">
            <div class="invoice-title">
              <h3>{{$user->name}} <span class="small">様</span></h3>
            </div>
            <hr>
            <div class="row">
              <div class="col-md-6">
                <address>
                  <strong>ご連絡先:</strong><br>
                  {{ $user->tel }}<br>
                  {{ $user->email }}
                </address>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <div class="section-title">リピートオーダー</div>
            <div class="clearfix mb-3"></div>
            <form id="saveform" action="{{ url('/admin/user/saverepeatorder') }}" enctype="multipart/form-data" method="POST" class="form-horizontal">
              @csrf
              <div class="table-responsive">
                <table class="table table-striped table-md" style="table-layout: auto;">
                  <tr>
                    <th class="text-center">商品番号</th>
                    <th class="text-center">商品名</th>
                    <th class="text-center">産地</th>
                    <th class="text-center">規格</th>
                    <th class="text-center head-price">単価</th>
                    <th class="text-center head-quantity">数量</th>
                    <th class="text-center head-tani">単位</th>
                    <th class="text-center head-nouhin_youbi">納品</th>
                    <th class="text-center head-store">納品先店舗</th>
                    <th class="text-center head-startdate">開始日</th>
                    <!-- <th class="text-center head-shoukei">小計</th> -->
                    @if ( Auth::guard('admin')->check() )
                    <th class="text-center head-yuukou">有効/無効</th>
                    <th class="text-center head-sousa">操作</th>
                    @else
                    <th class="text-center head-yuukou">有効/無効</th>
                    <th class="text-center head-tyuusi">操作</th>
                    @endif
                  </tr>
  		            @foreach($repeatcarts as $repeatcart)
                  <tr id="{{$repeatcart->id}}">
                    <td class="text-center">
                      {{$repeatcart->item()->item_id}}
                    </td>
                    <td class="text-center">
                      {{$repeatcart->item()->item_name}}
                    </td>
                    <td class="text-center">
                      {{$repeatcart->item()->sanchi_name}}
                    </td>
                    <td class="text-center">
                      {{$repeatcart->item()->kikaku}}
                    </td>

                    <td colspan="8" class="order-table repeat_order_table">
              				<table class="table table-striped table-hover table-md" style="table-layout: fixed;">
              				@foreach($repeatcart->orders as $val)
              					<tr id="{{$val->id}}">
                          <td class="text-center head-price">
                            <input name="repeatorder[{{$val->id}}][price]" class="price text-center form-control" value="{{$val->price}}" pattern="^[0-9]+$" title="0から9の半角数字" required>
                          </td>
                          <td class="text-center head-quantity">
                            <input name="repeatorder[{{$val->id}}][quantity]" class="repeatorder_quantity text-center form-control" value="{{$val->quantity}}" pattern="^[0-9]+$" title="0から9の半角数字" required>
                          </td>
                          <td class="text-center head-tani">
                            @if ($repeatcart->item()->tani == 1)
              							ｹｰｽ
              							@elseif ($repeatcart->item()->tani == 2)
              							ﾎﾞｰﾙ
              							@elseif ($repeatcart->item()->tani == 3)
              							ﾊﾞﾗ
              							@elseif ($repeatcart->item()->tani == 4)
              							Kg
              							@endif
                          </td>
                          <td id="" class="nouhin_youbi head-nouhin_youbi" class="text-center">

                            <input class="nouhin_youbi_checkbox" type="checkbox" id="repeatorder[{{$val->id}}][nouhin_youbi]月" name="repeatorder[{{$val->id}}][nouhin_youbi][]" value="mon" @if(strstr($val->nouhin_youbi,'mon'))
                            checked
                            @endif
                            >
                            <label for="repeatorder[{{$val->id}}][nouhin_youbi]月" class="checkbox-label">月</label>
                            <input class="nouhin_youbi_checkbox" type="checkbox" id="repeatorder[{{$val->id}}][nouhin_youbi]火" name="repeatorder[{{$val->id}}][nouhin_youbi][]" value="tue" @if(strstr($val->nouhin_youbi,'tue'))
                            checked
                            @endif
                            >
                            <label for="repeatorder[{{$val->id}}][nouhin_youbi]火" class="checkbox-label">火</label>
                            <input class="nouhin_youbi_checkbox" type="checkbox" id="repeatorder[{{$val->id}}][nouhin_youbi]水" name="repeatorder[{{$val->id}}][nouhin_youbi][]" value="wed" @if(strstr($val->nouhin_youbi,'wed'))
                            checked
                            @endif
                            >
                            <label for="repeatorder[{{$val->id}}][nouhin_youbi]水" class="checkbox-label">水</label>
                            <input class="nouhin_youbi_checkbox" type="checkbox" id="repeatorder[{{$val->id}}][nouhin_youbi]木" name="repeatorder[{{$val->id}}][nouhin_youbi][]" value="thu" @if(strstr($val->nouhin_youbi,'thu'))
                            checked
                            @endif
                            >
                            <label for="repeatorder[{{$val->id}}][nouhin_youbi]木" class="checkbox-label">木</label>
                            <input class="nouhin_youbi_checkbox" type="checkbox" id="repeatorder[{{$val->id}}][nouhin_youbi]金" name="repeatorder[{{$val->id}}][nouhin_youbi][]" value="fri" @if(strstr($val->nouhin_youbi,'fri'))
                            checked
                            @endif
                            >
                            <label for="repeatorder[{{$val->id}}][nouhin_youbi]金" class="checkbox-label">金</label>
                            <input class="nouhin_youbi_checkbox" type="checkbox" id="repeatorder[{{$val->id}}][nouhin_youbi]土" name="repeatorder[{{$val->id}}][nouhin_youbi][]" value="sat" @if(strstr($val->nouhin_youbi,'sat'))
                            checked
                            @endif
                            >
                            <label for="repeatorder[{{$val->id}}][nouhin_youbi]土" class="checkbox-label">土</label>
                            <input class="nouhin_youbi_checkbox" type="checkbox" id="repeatorder[{{$val->id}}][nouhin_youbi]日" name="repeatorder[{{$val->id}}][nouhin_youbi][]" value="sun" @if(strstr($val->nouhin_youbi,'sun'))
                            checked
                            @endif
                            >
                            <label for="repeatorder[{{$val->id}}][nouhin_youbi]日" class="checkbox-label">日</label>

                            <!-- <input id="repeatorder[{{$val->id}}][nouhin_youbi]" name="repeatorder[{{$val->id}}][nouhin_youbi]" class="nouhin_youbi text-center form-control" value="{{$val->nouhin_youbi}}" data-toggle="modal" data-target="#nouhin_youbi"> -->

                          </td>
                          <td class="head-store text-center">
              							<select name="repeatorder[{{$val->id}}][store]" class="repeatorder_store text-center form-control" value="{{$val->tokuisaki_name}},{{$val->store_name}}" required>
              								<option id="{{$val->tokuisaki_name}}" value="{{$val->tokuisaki_name}},{{$val->store_name}}">{{$val->tokuisaki_name}} {{$val->store_name}}</option>
              								@foreach($stores as $store)
              								<option id="{{$store->tokuisaki_name}}" value="{{$store->tokuisaki_name}},{{$store->store_name}}">{{$store->tokuisaki_name}} {{$store->store_name}}</option>
              								@endforeach
              							</select>
              						</td>
                          <td class="text-center head-startdate" width="150">
        										<input type="text" name="repeatorder[{{$val->id}}][startdate]" class="startdate text-center form-control daterange-cus datepicker" value="{{$val->startdate}}" autocomplete="off" required>
                          </td>
                          @if ( Auth::guard('user')->check() )
                          <td class="text-center head-yuukou">
        										{{$val->status}}
                          </td>
                          <!-- <td class="text-center head-shoukei"></td> -->
                          <!-- リピートオーダー停止申請 -->
                          <td class="head-tyuusi text-center">
                              @if($val->stop_flg == 1)
                              <spna class="stop_sinsei" style="margin-bottom:0px !important">停止申請中</spna>
                              @else
                              @if ( $val->status == "有効" )
                							<button type="button" name="repeatorder_id" id="{{$val->id}}" class="stoprepeatorder btn btn-success" value="{{$val->id}}">停止申請</button>
                              @endif
                              @endif
              						</td>
                          @endif
                          @if ( Auth::guard('admin')->check() )
                          <td class="text-center head-yuukou">
                            <div class="form-group">
                              <label class="mt-4">
                                <div class="selectgroup w-100">
                                  <label class="selectgroup-item">
                                    <input type="radio" name="repeatorder[{{$val->id}}][status]" value="有効" class="selectgroup-input"
                                    @if($val->status == "有効")
                                    checked
                                    @elseif($val->status == "")
                                    checked
                                    @else
                                    @endif
                                    >
                                    <span class="selectgroup-button">On</span>
                                  </label>
                                  <label class="selectgroup-item">
                                    <input type="radio" name="repeatorder[{{$val->id}}][status]" value="無効" class="selectgroup-input"
                                    @if($val->status == "無効")
                                    checked
                                    @else
                                    @endif
                                    >
                                    <span class="selectgroup-button">Off</span>
                                  </label>
                                </div>
                                <!-- <input type="checkbox" name="repeatorder[{{$val->id}}][status]" value="{{$val->status}}" class="custom-switch-input">
                                <span class="custom-switch-indicator"></span> -->
                              </label>
                            </div>
                          </td>
              						<td class="head-sousa text-center">
                            @if($val->stop_flg == 1)
                            <spna class="stop_sinsei">停止申請あり</spna>
                            @endif
              							<button type="button" id="{{$val->id}}" data-id="{{$val->id}}" class="removeid_{{$val->id}} delete_button btn btn-info">削除</button>
              							<button style="margin-top:10px;" type="button" id="{{$repeatcart->item()->id}}" class="cloneid_{{$val->id}} clonerepeatorder btn btn-success">配送先を追加</button>
              						<input name="order_id[]" class="order_id" type="hidden" value="{{$val->id}}" />
              						</td>
                          @endif
              					</tr>
              				@endforeach
              				</table>
              			</td>
                  </tr>
                  @endforeach

                </table>
              </div>
              @if ( Auth::guard('admin')->check() )
              <input id="kaiin_number" name="kaiin_number" type="hidden" value="{{$id}}">
              <button id="send" type="submit" class="btn btn-warning float-right">内容を保存</button>
              @endif
            </form>
            @if ( Auth::guard('admin')->check() )
            <button class="addrepeatorder btn btn-success" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> 商品を追加</button>
            @endif
          </div>
        </div>
      </div>
      <hr>
    </div>
  </div>
</section>


<form id="remove_form" action="{{ url('/admin/user/removerepeatorder') }}" method="POST">
  @csrf
  <input name="kaiin_number" type="hidden" value="{{$id}}">
  <input id="remove_id" name="delete" type="hidden" value="">
  <input id="cart_id" name="cart_id" type="hidden" value="">
</form>


<form id="stop_repeatorder" action="{{ url('/stoprepeatorder') }}" enctype="multipart/form-data" method="POST" class="form-horizontal">
  @csrf
  <input id="stop_id" name="stop_id" type="hidden" value="">
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
              <input type="text" id="search-text" class="form-control" placeholder="検索">
              <div class="input-group-append">
                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
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
        <!-- 検索リスト -->
        <form action="{{ url('/admin/user/addrepeatorder') }}" method="POST" class="form-horizontal">
          {{ csrf_field() }}
          <div class="table-responsive">
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
                <th class="text-center" style="min-width:180px;">操作</th>
              </tr>
              @foreach($items as $item)
              @if($item->zaikosuu == 0)
              @else
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
                <td class="text-center"><button id="{{$item->id}}" name="item_id" type="submit" value="{{$item->id}}" class="addrepeatorder btn btn-warning">追加</button></td>
              </tr>
              @endif
              @endforeach
            </table>
          </div>
          <input type="hidden" name="kaiin_number" value="{{$id}}" />
        </form>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>

<!-- Modal -->
<!-- <div class="modal fade" id="nouhin_youbi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">納品曜日選択</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <input class="checkbox-input" type="checkbox" id="月" name="nouhin_youbi" value="月">
          <label for="月" class="checkbox-label">月</label>
          <input class="checkbox-input" type="checkbox" id="火" name="nouhin_youbi" value="火">
          <label for="火" class="checkbox-label">火</label>
          <input class="checkbox-input" type="checkbox" id="水" name="nouhin_youbi" value="水">
          <label for="水" class="checkbox-label">水</label>
          <input class="checkbox-input" type="checkbox" id="木" name="nouhin_youbi" value="木">
          <label for="木" class="checkbox-label">木</label>
          <input class="checkbox-input" type="checkbox" id="金" name="nouhin_youbi" value="金">
          <label for="金" class="checkbox-label">金</label>
          <input class="checkbox-input" type="checkbox" id="土" name="nouhin_youbi" value="土">
          <label for="土" class="checkbox-label">土</label>
          <input class="checkbox-input" type="checkbox" id="日" name="nouhin_youbi" value="日">
          <label for="日" class="checkbox-label">日</label>
          <button id="nouhin_youbi_set" name="item_id" data-dismiss="modal" aria-label="Close" value="" class="addrepeatorder btn btn-warning">設定</button></td>
      </div>
    </div>
  </div>
</div> -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ja.min.js"></script>


<!-- <link rel="stylesheet" href="{{ asset('css/jquery.weekLine.css') }}">
<link rel="stylesheet" href="{{ asset('css/jquery.weekLine-white.css') }}">
<script src="{{ asset('js/jquery.weekLine.js') }}"></script> -->


<!-- <script>
$(document).ready(function(){
  $("#demo1").weekLine();
  $("#demo2").weekLine({
    dayLabels: ["日", "月", "火", "水", "木", "金", "土"]
  });
});
</script> -->


<script>
@if ( Auth::guard('admin')->check() )
$('.datepicker').datepicker({
	format: 'yyyy-mm-dd',
	autoclose: true,
	assumeNearbyYear: true,
	language: 'ja',
	startDate: '+2d',
	endDate: '+31d',
	defaultViewDate: Date()
});
@endif
// $('.nouhin_youbi_checkbox').on('click', function(){
//   $(this).toggleClass('isActive');
// })
$(document).on("click", ".nouhin_youbi", function() {
  var input = $(this).get(0).id;
	var search = document.getElementsByName("nouhin_youbi").value;
  console.log(input);
  // console.log(search);
  return input;
  $("#nouhin_youbi_set").click(function () {
    const colors = [];
    $(':checkbox[name="nouhin_youbi"]:checked').each(function () {
      colors.push($(this).val());
      $("#nouhin_youbi_set").val(colors);
      $(input).val(colors);
      console.log(colors);
      console.log(input);
    });
  });
});

// function OK() {
// 	var search = document.getElementsByName(input).value;
//   console.log(search);
// 	document.getElementById(input).value = search;
// }

@if ( Auth::guard('user')->check() )
if(document.URL.match("/repeatorder")) {
  $(function(){
		$('input').attr('readonly',true);
		$('select').attr('readonly',true);
		$('select').addClass('arrow_hidden');
		$("select[readonly] > option:not(:selected)").attr('disabled', 'disabled');
		$('textarea').attr('readonly',true);
		$('select').attr("disabled", true);
		$('.nouhin_youbi_checkbox').attr("disabled", true);
		$('.head-sousa').remove();
		$('.addniniorder').remove();
  });
}
@endif
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
<script>
$(function () {
  searchWord = function(){
    var searchResult,
        searchText = $(this).val(), // 検索ボックスに入力された値
        targetText,
        hitNum;

    // 検索結果を格納するための配列を用意
    searchResult = [];

    // 検索結果エリアの表示を空にする
    $('#search-result__list').empty();
    $('.search-result__hit-num').empty();

    // 検索ボックスに値が入ってる場合
    if (searchText != '') {
      $('.target-area tr').each(function() {
        targetText = $(this).text();

        // 検索対象となるリストに入力された文字列が存在するかどうかを判断
        if (targetText.indexOf(searchText) != -1) {
          // 存在する場合はそのリストのテキストを用意した配列に格納
          searchResult.push($(this).html());
        }
        $('.target-area').hide();
      });
      console.log(searchResult);

      // 検索結果にヘッダーを追加
      header = '<tr><th class="text-center">商品番号</th><th class="">商品名</th><th class="text-center">産地</th><th class="text-center">規格</th><th class="text-center">単位</th><th class="text-center">在庫数</th><th class="text-center">特記事項</th><th class="text-center" style="min-width:130px;">操作</th></tr>';

      $('#search-result__list').append(header);

      // 検索結果をページに出力
      for (var i = 0; i < searchResult.length; i ++) {
        $('<tr>').html(searchResult[i]).appendTo('#search-result__list');
      }

      // ヒットの件数をページに出力
      hitNum = '<span>検索結果</span>：' + searchResult.length + '件見つかりました。';
      $('.search-result__hit-num').append(hitNum);
    }else{
      // 検索ボックスに値が入っていない場合
      $('.target-area').show();
    }
  };

  // searchWordの実行
  $('#search-text').on('input', searchWord);
});
</script>
@endsection
