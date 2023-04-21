@extends('layouts.app')

@section('content')






        <section class="section">
          <div class="section-header">
            <h1>
              @if(isset($category_name))
              {{$category_name}}
              @else
              @if(Request::is('recommend'))
              担当のおすすめ商品
              @elseif(Request::is('special_price'))
              市況商品（時価）
              @else
              商品一覧
              @endif
              @endif
            </h1>
          </div>



          <!-- <a href="{{ url('/user/test') }}"><button class="btn btn-warning">TEST</button></a> -->

          @if(Request::is('recommend'))
          @if(!isset($recommends[0]))
          <p>
            担当のおすすめ商品が見つかりませんでした。
          </p>
          @else
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="float-left">
                      <h2 class="section-title">担当のおすすめ商品</h2>
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
                          <th class="text-center">価格</th>
                          <th class="text-center head-cartbtn">操作</th>
                        </tr>


                        @foreach($recommends as $recommend)
                        @if($recommend->item())
                        @if($recommend->item()->zaikosuu == 0)
                        @else
                        <tr>
                          <!-- <form class="form-horizontal" role="form" method="POST" action="{{ url('/addcart') }}"> -->
                            @csrf
                            <td class="text-center">{{$recommend->item()->item_id}}</td>
                            <td class="">{{$recommend->item()->item_name}}</td>
                            <td class="text-center">{{$recommend->item()->sanchi_name}}</td>
                            <td class="text-center">{{$recommend->item()->kikaku}}</td>
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
                            <td class="text-center">{{$recommend->item()->zaikosuu}}</td>
                            <td class="text-center">{{$recommend->item()->tokkijikou}}</td>
                            <td class="text-center">{{number_format($recommend->price)}}</td>
                            <td class="text-center">
                              @if($recommend->favoriteitem())
                              <!-- <button name="item_id" value="{{$recommend->item()->id}}" id="{{$recommend->item()->id}}" class="removefavoriteitem"><i class="fa fa-heart"></i></button> -->
                              @else
                              <!-- <button name="item_id" value="{{$recommend->item()->id}}" id="{{$recommend->item()->id}}" class="addfavoriteitem"><i class="far fa-heart"></i></button> -->
                              @endif
                              @if ( Auth::guard('user')->user()->setonagi == 1 )
                                @if ( Auth::guard('user')->user()->setonagi()->kakebarai_riyou == 1 || Auth::guard('user')->user()->setonagi()->setonagi_ok == 1)
                                <button name="item_id" value="{{$recommend->item()->id}}" id="{{$recommend->item()->id}}" class="addcart btn btn-warning">カートに入れる</button>
                                @else
                                <div class="btn btn-primary">現在審査中です</div>
                                @endif
                              @else
                              <button name="item_id" value="{{$recommend->item()->id}}" id="{{$recommend->item()->id}}" class="addcart btn btn-warning">カートに入れる</button>
                              @endif
                              </td>
                          <!-- </form> -->
                        </tr>
                        @endif
                        @endif
                        @endforeach
                      </table>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif
          @endif


          @if(Request::is('special_price'))
          @if(!isset($special_prices[0]))
          <p>
            時価商品が見つかりませんでした。
          </p>
          @else
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="float-left">
                      <h2 class="section-title">時価商品<span class="small">（月曜、木曜、金曜の12時30分から17時までの限定掲載となります。）</span></h2>
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
                          <th class="text-center">価格</th>
                          <th class="text-center head-cartbtn">操作</th>
                        </tr>

                        @foreach($special_prices as $special_price)
                        @if($special_price->item())
                        @if($special_price->item()->zaikosuu == 0)
                        @else
                        @if($now >= $special_price->start && $now < $special_price->end)
                        <tr>
                          <!-- <form class="form-horizontal" role="form" method="POST" action="{{ url('/addcart') }}"> -->
                            @csrf
                            <td class="text-center">{{$special_price->item()->item_id}}</td>
                            <td class="">{{$special_price->item()->item_name}}</td>
                            <td class="text-center">{{$special_price->item()->sanchi_name}}</td>
                            <td class="text-center">{{$special_price->item()->kikaku}}</td>
                            <td class="text-center">
                              @if ($special_price->item()->tani == 1)
                              ｹｰｽ
                              @elseif ($special_price->item()->tani == 2)
                              ﾎﾞｰﾙ
                              @elseif ($special_price->item()->tani == 3)
                              ﾊﾞﾗ
                              @elseif ($special_price->item()->tani == 4)
                              Kg
                              @endif
                            </td>
                            <td class="text-center">{{$special_price->item()->zaikosuu}}</td>
                            <td class="text-center">{{$special_price->item()->tokkijikou}}</td>
                            <td class="text-center">{{number_format($special_price->price)}}</td>

                            <td class="text-center">
                              @if($special_price->favoriteitem())
                              <!-- <button name="item_id" value="{{$special_price->item()->id}}" id="{{$special_price->item()->id}}" class="removefavoriteitem"><i class="fa fa-heart"></i></button> -->
                              @else
                              <!-- <button name="item_id" value="{{$special_price->item()->id}}" id="{{$special_price->item()->id}}" class="addfavoriteitem"><i class="far fa-heart"></i></button> -->
                              @endif

                              @if ( Auth::guard('user')->user()->setonagi == 1 )
                                @if ( Auth::guard('user')->user()->setonagi()->kakebarai_riyou == 1 || Auth::guard('user')->user()->setonagi()->setonagi_ok == 1)
                                <button name="item_id" value="{{$special_price->item()->id}}" id="{{$special_price->item()->id}}" class="addcart btn btn-warning">カートに入れる</button>
                                @else
                                <div class="btn btn-primary">現在審査中です</div>
                                @endif
                              @else
                              <button name="item_id" value="{{$special_price->item()->id}}" id="{{$special_price->item()->id}}" class="addcart btn btn-warning">カートに入れる</button>
                              @endif
                            </td>
                          <!-- </form> -->
                        </tr>
                        @endif
                        @endif
                        @endif
                        @endforeach
                      </table>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif
          @endif


          @if(!isset($recommendcategories[0]))
          @else
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="float-left">
                      <h2 class="section-title">{{$category_name}}のおすすめ商品</h2>
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
                          <th class="text-center head-cartbtn">操作</th>
                        </tr>


                        @foreach($recommendcategories as $recommendcategory)
                        @if($recommendcategory->item()->zaikosuu == 0)
                        @else
                        <tr>
                          <td class="text-center">{{$recommendcategory->item()->item_id}}</td>
                          <td class="">{{$recommendcategory->item()->item_name}}</td>
                          <td class="text-center">{{$recommendcategory->item()->sanchi_name}}</td>
                          <td class="text-center">{{$recommendcategory->item()->kikaku}}</td>
                          <td class="text-center">
                            @if ($recommendcategory->item()->tani == 1)
                            ｹｰｽ
                            @elseif ($recommendcategory->item()->tani == 2)
                            ﾎﾞｰﾙ
                            @elseif ($recommendcategory->item()->tani == 3)
                            ﾊﾞﾗ
                            @elseif ($recommendcategory->item()->tani == 4)
                            Kg
                            @endif
                          </td>
                          <td class="text-center">{{$recommendcategory->item()->zaikosuu}}</td>
                          <td class="text-center">{{$recommendcategory->item()->tokkijikou}}</td>

                          <td class="text-center"><button id="{{$recommendcategory->item()->id}}" class="addcart btn btn-warning">カートに入れる</button></td>
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
          @endif



          @if(Request::is('/') or Request::is('*category*') or Request::is('*user/home*'))
          @if(empty($items))
          <p>
            商品が売り切れました。
          </p>
          @else
          <?php
          header('Location: /setonagi/');
          exit();
          ?>
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="float-left">
                      @if(isset($category_name))
                      <h2 class="section-title">{{$category_name}}</h2>
                      @else
                      <h2 class="section-title">全ての商品</h2>
                      @endif
                    </div>
                    <!-- <div class="float-right">
                      <form id="saveform" action="{{ url('/') }}" enctype="multipart/form-data" method="POST" class="form-horizontal">
                        @csrf
                        <div class="input-group">
                          <input type="text" name="search" class="form-control" placeholder="検索">
                          <div class="input-group-append">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                          </div>
                        </div>
                      </form>
                    </div> -->

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
                          <!-- <th class="text-center">納品予定日</th>
                          <th class="text-center">参考価格</th>
                          <th class="text-center">個数</th> -->
                          <th class="text-center head-cartbtn">操作</th>
                        </tr>
                        @foreach($items as $item)
                        <tr>
                          <!-- <form class="form-horizontal" role="form" method="POST" action="{{ url('/addcart') }}"> -->
                            @csrf
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
                            <td class="text-center"><button name="item_id" value="{{$item->id}}" id="{{$item->id}}" class="addcart btn btn-warning">カートに入れる</button></td>
                          <!-- </form> -->
                        </tr>
                        @endforeach
                      </table>
                    </div>


                    <!-- <form action="{{ url('/addcart')}}" method="POST" class="form-horizontal">
                      {{ csrf_field() }}
                      <input name="item_id" placeholder="1">
                      <input name="quantity" placeholder="1">
                      <button type="submit">
                       追加
                      </button>
                    </form> -->


                    <div class="float-right">
                      <nav>
                        <ul class="pagination">
                          {{ $items->links() }}
                        </ul>
                      </nav>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif
          @endif

        </section>






@endsection
