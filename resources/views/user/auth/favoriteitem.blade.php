@extends('layouts.app')

@section('content')






        <section class="section">
          <div class="section-header">
            <h1>
              @if(isset($category_name))
              {{$category_name}}
              @else
              お気に入り商品一覧
              @endif
            </h1>
          </div>



          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="float-left">
                      <h2 class="section-title">時価商品</h2>
                    </div>

                    <div class="clearfix mb-3"></div>

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
                          <th class="text-center" style="min-width:180px;">操作</th>
                        </tr>


                        @foreach($favorite_items as $favorite_item)
                        @if($favorite_item->item()->zaikosuu == 0)
                        @else
                        <tr>
                          <!-- <form class="form-horizontal" role="form" method="POST" action="{{ url('/addcart') }}"> -->
                            @csrf
                            <td class="text-center">{{$favorite_item->item()->item_id}}</td>
                            <td class="">{{$favorite_item->item()->item_name}}</td>
                            <td class="text-center">{{$favorite_item->item()->sanchi_name}}</td>
                            <td class="text-center">{{$favorite_item->item()->kikaku}}</td>
                            <td class="text-center">
                              @if ($favorite_item->item()->tani == 1)
                              ｹｰｽ
                              @elseif ($favorite_item->item()->tani == 2)
                              ﾎﾞｰﾙ
                              @elseif ($favorite_item->item()->tani == 3)
                              ﾊﾞﾗ
                              @elseif ($favorite_item->item()->tani == 4)
                              Kg
                              @endif
                            </td>
                            <td class="text-center">{{$favorite_item->item()->zaikosuu}}</td>
                            <td class="text-center">{{$favorite_item->item()->tokkijikou}}</td>

                            <td class="text-center">
                              @if($favorite_item->item())
                              <button name="item_id" value="{{$favorite_item->item()->id}}" id="{{$favorite_item->item()->id}}" class="removefavoriteitem"><i class="fa fa-heart"></i></button>
                              @else
                              <button name="item_id" value="{{$favorite_item->item()->id}}" id="{{$favorite_item->item()->id}}" class="addfavoriteitem"><i class="far fa-heart"></i></button>
                              @endif
                              <button name="item_id" value="{{$favorite_item->item()->id}}" id="{{$favorite_item->item()->id}}" class="addcart btn btn-warning">カートに入れる</button></td>
                          <!-- </form> -->
                        </tr>
                        @endif
                        @endforeach
                      </table>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>

        </section>






@endsection
