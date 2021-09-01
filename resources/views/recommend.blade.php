@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>担当のおすすめ商品登録</h1>
    <div class="section-header-breadcrumb">
    </div>
  </div>
  <div class="section-body">
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-body">

            <!-- <ul class="navbar-nav float-left">


              <div class="form-group">
                <select class="custom-select">
                  <option selected="">全ての取引</option>
                  <option value="1">交渉中</option>
                  <option value="2">受注済み</option>
                  <option value="3">過去受注</option>
                  <option value="3">キャンセル</option>
                </select>
              </div>

            </ul> -->


            <!-- <div class="float-right">
              <form>
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="検索">
                  <div class="input-group-append">
                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </form>
            </div> -->



            <div class="clearfix mb-3"></div>

            <form action="{{ url('/admin/user/saverecommend') }}" method="POST" class="form-horizontal">
              {{ csrf_field() }}
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
                    <input name="recommend_id[]" type="hidden" value="{{$recommend->id}}">
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
                      <input name="price[]" class="price text-center form-control" value="">
                    </td>
                    <td class="text-center" width="150">
                      <input type="text" name="end[]" class="nouhin_yoteibi text-center form-control daterange-cus datepicker" value="">
                    </td>
                    <td class="text-center">
                      <a href=""><button class="btn btn-primary">削除</button></a>
                    </td>
                  </tr>
                  @endforeach

                </table>
              </div>
              <input name="user_id" type="hidden" value="{{$id}}">
              <button type="submit" class="btn btn-warning float-right">内容を保存</button>
            </form>
              <button class="addrecommend btn btn-success" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> 商品を追加</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ url('/admin/user/addrecommend') }}" method="POST" class="form-horizontal">
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
@endsection
