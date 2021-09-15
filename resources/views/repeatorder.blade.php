@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>リピートオーダー登録</h1>
    <div class="section-header-breadcrumb">
      @if ( Auth::guard('user')->check() )
      <div class="breadcrumb-item"><a href="{{ url('/') }}">HOME</a></div>
      <div class="breadcrumb-item">担当のおすすめ商品</div>
      @endif
      @if ( Auth::guard('admin')->check() )
      <div class="breadcrumb-item active"><a href="{{ url('/admin/home') }}">HOME</a></div>
      <div class="breadcrumb-item"><a href="{{ url('/admin/user') }}">顧客一覧</a></div>
      <div class="breadcrumb-item">リピートオーダー登録（{{$user->name}} 様）</a></div>
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
            <div class="section-title">リピートオーダー</div>
            <div class="clearfix mb-3"></div>
            <form id="saveform" action="{{ url('/admin/user/saverepeatorder') }}" enctype="multipart/form-data" method="POST" class="form-horizontal">
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
                    <th class="text-center">次回納品予定日</th>
                    <th class="text-center">納品曜日</th>
                    <th class="text-center">有効/無効</th>
                    <th class="text-center">操作</th>
                  </tr>

  		            @foreach($repeatorders as $repeatorder)
                  <tr>
                    <td class="text-center">
                      {{$repeatorder->item()->item_id}}
                    </td>
                    <td class="text-center">
                      {{$repeatorder->item()->item_name}}
                    </td>
                    <td class="text-center">
                      {{$repeatorder->item()->sanchi_name}}
                    </td>
                    <td class="text-center">
                      {{$repeatorder->item()->kikaku}}
                    </td>
                    <td class="text-center">
                      @if ($repeatorder->item()->tani == 1)
        							ｹｰｽ
        							@elseif ($repeatorder->item()->tani == 2)
        							ﾎﾞｰﾙ
        							@elseif ($repeatorder->item()->tani == 3)
        							ﾊﾞﾗ
        							@elseif ($repeatorder->item()->tani == 4)
        							Kg
        							@endif
                    </td>
                    <td class="text-center" width="150">
                      <input name="repeatorder[{{$repeatorder->id}}][price]" class="price text-center form-control" value="{{$repeatorder->price}}">
                    </td>
                    <td class="text-center" width="160">
      								<input type="text" name="repeatorder[{{$repeatorder->id}}][nouhin_youbi]" class="nouhin_youbi text-center form-control daterange-cus datepicker" value="{{$repeatorder->nouhin_youbi}}" autocomplete="off">
                    </td>
                    <td class="text-center" width="150">
                      <input type="text" name="" class="text-center form-control" value="">
                    </td>
                    <td class="text-center" width="150">
                      <div class="form-group">
                        <label class="mt-4">
                          <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                              <input type="radio" name="repeatorder[{{$repeatorder->id}}][status]" value="有効" class="selectgroup-input"
                              @if($repeatorder->status == "有効")
                              checked
                              @elseif($repeatorder->status == "")
                              checked
                              @else
                              @endif
                              >
                              <span class="selectgroup-button">on</span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="radio" name="repeatorder[{{$repeatorder->id}}][status]" value="無効" class="selectgroup-input"
                              @if($repeatorder->status == "無効")
                              checked
                              @else
                              @endif
                              >
                              <span class="selectgroup-button">off</span>
                            </label>
                          </div>
                          <!-- <input type="checkbox" name="repeatorder[{{$repeatorder->id}}][status]" value="{{$repeatorder->status}}" class="custom-switch-input">
                          <span class="custom-switch-indicator"></span> -->
                        </label>
                      </div>
                    </td>
                    <td class="text-center">
                      <div class="btn btn-primary delete_button" data-id="{{$repeatorder->id}}"/>削除</div>
                    </td>
                  </tr>
                  @endforeach

                </table>
              </div>
              <input name="kaiin_number" type="hidden" value="{{$id}}">
              <button form="saveform" type="submit" class="btn btn-warning float-right">内容を保存</button>
            </form>
            <button class="addrepeatorder btn btn-success" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> 商品を追加</button>
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
</form>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">商品一覧</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ url('/admin/user/addrepeatorder') }}" method="POST" class="form-horizontal">
          {{ csrf_field() }}
          <div class="table-responsive">
            <table class="table table-striped">
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
                <td class="text-center">{{$item->tokkijikou}}</td>

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

<script>
$('.datepicker').datepicker({
	format: 'yyyy-mm-dd',
	autoclose: true,
	assumeNearbyYear: true,
	language: 'ja',
	startDate: '+2d',
	endDate: '+31d',
	defaultViewDate: Date()
});
</script>
@endsection