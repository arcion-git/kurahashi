
@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>担当のおすすめ商品登録</h1>
    <div class="section-header-breadcrumb">
      @if ( Auth::guard('user')->check() )
      <div class="breadcrumb-item"><a href="{{ url('/') }}">HOME</a></div>
      <div class="breadcrumb-item">担当のおすすめ商品</div>
      @endif
      @if ( Auth::guard('admin')->check() )
      <div class="breadcrumb-item active"><a href="{{ url('/admin/home') }}">HOME</a></div>
      <div class="breadcrumb-item"><a href="{{ url('/admin/user') }}">顧客一覧</a></div>
      <div class="breadcrumb-item">担当のおすすめ商品登録（{{$user->name}} 様）</a></div>
      @endif
    </div>
  </div>
  <div class="section-body">
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
            <div class="section-title">担当のおすすめ商品</div>
            <div class="clearfix mb-3"></div>


            <form id="saveform" action="{{ url('/admin/user/saverecommend') }}" enctype="multipart/form-data" method="POST" class="form-horizontal">
              @csrf
              <div class="table-responsive">
                <table class="table table-striped">
                <thead>
                  <tr>
                    <!-- <th class="text-center">並び順</th> -->
                    <th class="text-center">商品番号</th>
                    <th class="text-center">商品名</th>
                    <th class="text-center">産地</th>
                    <th class="text-center">規格</th>
                    <th class="text-center">単位</th>
                    <th class="text-center">在庫数</th>
                    <th class="text-center">単価</th>
                    <th class="text-center">掲載開始</th>
                    <th class="text-center">掲載終了</th>
                    <!-- <th class="text-center nouhin_end">納品期限</th>
                    <th class="text-center">限定店舗</th>
                    <th class="text-center">操作</th>
                    <th class="text-center">価格非表示<input type="checkbox" id="all_hidden_price" name="all_hidden_price"></th>
                    <th class="text-center">在庫管理しない<input type="checkbox" id="all_zaikokanri" name="all_zaikokanri"></th>
                    <th class="text-center">限定数</th> -->
                    <th class="text-center">操作</th>
                  </tr>
                </thead>
                <tbody id="sortdata">
    	            @foreach($recommends as $recommend)
                  <tr>
                      <!-- オーダー番号を記録 -->
                      <input type="hidden" name="recommend[{{$recommend->id}}][order_no]" class="order_no text-center form-control" value="{{$recommend->order_no}}">
                    <!-- <td class="text-center">
                      <span class="ui-icon ui-icon-arrowthick-2-n-s ui-corner-all ui-state-hover">↑↓</span>
                    </td> -->
                    <td class="text-center">
                      {{$recommend->item()->item_id}}
                    </td>
                    <td class="text-center">
                      {{$recommend->item()->item_name}}
                    </td>
                    <td class="text-center">
                      {{$recommend->item()->sanchi_name}}
                    </td>
                    <td class="text-center">
                      {{$recommend->item()->kikaku}}
                    </td>
                    <td class="text-center">
                      @if ($recommend->item()->tani == 1)
        							ｹｰｽ
        							@elseif ($recommend->item()->tani == 2)
        							ﾎﾞｰﾙ
        							@elseif ($recommend->item()->tani == 3)
        							ﾊﾞﾗ
        							@elseif ($recommend->item()->tani == 4)
        							Kg
        							@endif
                    </td>
                    <td class="text-center">
                      {{$recommend->item()->zaikosuu}}
                    </td>
                    <td class="text-center" width="150">
                      <input pattern="^[0-9]+$" name="recommend[{{$recommend->id}}][price]" class="price text-center form-control" value="{{$recommend->price}}">
                    </td>
                    <td class="text-center" width="150">
                      <input type="text" name="recommend[{{$recommend->id}}][start]" class="start text-center form-control daterange-cus datepicker" value="{{$recommend->start}}">
                    </td>
                    <td class="text-center" width="150">
                      <input type="text" name="recommend[{{$recommend->id}}][end]" class="end text-center form-control daterange-cus datepicker" value="{{$recommend->end}}">
                    </td>
                    <td class="text-center">
                      <div class="btn btn-primary delete_button" data-id="{{$recommend->id}}"/>削除</div>
                      <!-- <div class="addrecommend btn btn-success" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> 追加</div> -->
                      <div class="btn btn-success userduplicatercommend_button" data-id="{{$recommend->id}}"><i class="fas fa-plus"></i> 複製</div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
                </table>
              </div>
              <input name="tokuisaki_id" type="hidden" value="{{$id}}">
              <button type="submit" class="save_btn btn btn-warning float-right">内容を保存</button>
            </form>
            <button class="addendrecommend btn btn-success" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> 商品を追加</button>
          </div>
        </div>
      </div>
      <hr>
    </div>
  </div>
</section>


<form id="userduplicatercommend_form" action="{{ url('/admin/user/duplicatercommend') }}" method="POST">
@csrf
<input type="hidden" name="tokuisaki_id" type="hidden" value="{{$id}}">
<input id="userduplicatercommend_id" name="duplicate" type="hidden" value="">
</form>

<form id="remove_form" action="{{ url('/admin/user/removercommend') }}" method="POST">
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

      <form id="recommend" action="{{ url('/admin/user/addrecommend') }}" method="POST" class="form-horizontal">
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
 $('.datepicker').datepicker({
 	format: 'yyyy-mm-dd',
 	autoclose: true,
 	assumeNearbyYear: true,
 	language: 'ja',
 	// startDate: '+1d',
 	// endDate: '+31d',
 	defaultViewDate: Date()
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
     // $(this).val(idx+1);
   });
 });
 $('#sortdata').sortable( {
     // handle: 'span',
 } );
 // sortstopイベントをバインド
 $('#sortdata').bind('sortstop',function(){
   // 番号を設定している要素に対しループ処理
   $(this).find('.order_no').each(function(idx){
     // タグ内に通し番号を設定（idxは0始まりなので+1する）
     $(this).val(idx+1);
     $('.save_btn').click();
   });
 });
 $('.price,.start,.end,nouhin_end').change(function() {
     $('.save_btn').click();
 });
 // $(".addrecommend").on("click",function(){
 //   var order_no = $(this).parent().parent().find('.order_no').val();
 //   console.log(order_no);
 //   $(".ordernosave").val(order_no);
 // });
 // $(".addendrecommend").on("click",function(){
 //   var order_no = 1000;
 //   console.log(order_no);
 //   $(".ordernosave").val(order_no);
 // });

 $(window).scroll(function() {
   sessionStorage.scrollTop = $(this).scrollTop();
 });

 $(document).ready(function() {
   if (sessionStorage.scrollTop != "undefined") {
     $(window).scrollTop(sessionStorage.scrollTop);
   }
 });
 </script>

@endsection
