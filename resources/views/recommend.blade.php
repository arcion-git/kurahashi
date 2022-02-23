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
                  <tr>
                    <th class="text-center">商品番号</th>
                    <th class="text-center">商品名</th>
                    <th class="text-center">産地</th>
                    <th class="text-center">規格</th>
                    <th class="text-center">単位</th>
                    <th class="text-center">単価</th>
                    <th class="text-center">掲載期限</th>
                    <th class="text-center">操作</th>
                  </tr>

    	            @foreach($recommends as $recommend)
                  <tr>
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
                    <td class="text-center" width="150">
                      <input name="recommend[{{$recommend->id}}][price]" class="price text-center form-control" value="{{$recommend->price}}">
                    </td>
                    <td class="text-center" width="150">
                      <input type="text" name="recommend[{{$recommend->id}}][end]" class="nouhin_yoteibi text-center form-control daterange-cus datepicker" value="{{$recommend->end}}">
                    </td>
                    <td class="text-center">
                      <div class="btn btn-primary delete_button" data-id="{{$recommend->id}}"/>削除</div>
                    </td>
                  </tr>
                  @endforeach

                </table>
              </div>
              <input name="user_id" type="hidden" value="{{$id}}">
              <button form="saveform" type="submit" class="btn btn-warning float-right">内容を保存</button>
            </form>
            <button class="addrecommend btn btn-success" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> 商品を追加</button>
          </div>
        </div>
      </div>
      <hr>
    </div>
  </div>
</section>



<form id="remove_form" action="{{ url('/admin/user/removercommend') }}" method="POST">
  @csrf
  <input name="user_id" type="hidden" value="{{$id}}">
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

        <form action="{{ url('/admin/user/addrecommend') }}" method="POST" class="form-horizontal">
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
                <th class="text-center">特記事項</th>
                <!-- <th class="text-center">納品予定日</th>
                <th class="text-center">参考価格</th>
                <th class="text-center">個数</th> -->
                <th class="text-center" style="min-width:130px;">操作</th>
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
                <td class="text-center">{{$item->tokkijikou}}</td>

                <!-- <td class="text-center">{{$item->nouhin_yoteibi_start}}</td>
                <td class="text-center">
                  ¥ {{$item->teika}}
                </td>
                <td class="text-center"><input name="quantity" class="quantity form-control" value="1"></td> -->
                <td class="text-center"><button id="{{$item->id}}" name="item_id" type="submit" value="{{$item->id}}" class="addrecommend btn btn-warning">追加</button></td>
              </tr>
              @endif
              @endforeach
            </table>
          </div>
          <input type="hidden" name="user_id" value="{{$id}}" />
        </form>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>
<script>
$('.datepicker').datepicker({
	format: 'yyyy-mm-dd',
	autoclose: true,
	assumeNearbyYear: true,
	language: 'ja',
	startDate: '+1d',
	endDate: '+31d',
	defaultViewDate: Date()
});
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
