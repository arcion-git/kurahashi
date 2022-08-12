@extends('layouts.app')

@section('content')


        <section class="section">
          <div class="section-header">
            <h1>
              @if(isset($category_name))
              {{$category_name}}
              @else
              限定お買い得商品
              @endif
            </h1>
          </div>


          <!-- <a href="{{ url('/user/test') }}"><button class="btn btn-warning">TEST</button></a> -->

          @if(!isset($setonagi_items[0]))
          <p>
            担当のおすすめ商品が見つかりませんでした。
          </p>
          @else


          <!-- <div class="section-body">

            <div class="row mb-4">
              <div class="col-lg-6">
                  <a href="/saiji"><img src="https://setonagi.net/wp-content/uploads/2022/03/%E2%97%8FSETOnagi%E5%82%AC%E4%BA%8B%E3%83%90%E3%83%8A%E3%83%BC_5%E6%9C%88%E5%82%AC%E4%BA%8B%E3%83%90%E3%83%8A%E3%83%BC.jpg"  width="100%"/></a>
              </div>
              <div class="col-lg-6">
                  <a href="/saiji"><img src="https://setonagi.net/wp-content/uploads/2022/03/%E2%97%8FSETOnagi%E5%82%AC%E4%BA%8B%E3%83%90%E3%83%8A%E3%83%BC_6%E6%9C%88%E5%82%AC%E4%BA%8B.jpg" width="100%"/></a>
              </div>
            </div>
          </div> -->

          <div class="section-body">
                        <div class="row">
                          @foreach($setonagi_items as $setonagi_item)
                          <div class="col-6 col-md-3 col-lg-3">
                            <article class="article article-style-c">
                              <!-- <button class="addrecommendcategory btn btn-success" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> 商品を追加</button>
                              <button class="btn btn-success" data-toggle="modal" data-target="#modal{{$setonagi_item->item()->item_id}}"><i class="fas fa-plus"></i> 商品を追加</button> -->
                              <div class="article-header">
                                <div class="article-image" data-background="/storage/item/{{$setonagi_item->item()->item_id}}.jpg" style="background-image: url(&quot;/storage/item/{{$setonagi_item->item()->item_id}}.jpg&quot;);">
                                </div>
                              </div>
                              <div class="article-details">
                                <div class="article-category"><a href="#">産地</a><div class="bullet"></div><a href="#">{{$setonagi_item->item()->sanchi_name}}</a></div>
                                <div class="article-title">
                                  <h2><a href="#">{{$setonagi_item->item()->item_name}}</a></h2>
                                </div>
                                <p class="setonagi_price">¥
                                @if ( Auth::guard('user')->user()->setonagi == 1 )
                                  @if ( Auth::guard('user')->user()->setonagi()->kakebarai_riyou == 1 )
                                  {{$setonagi_item->price}}
                                  @else
                                  ---
                                  @endif
                                @else
                                  {{$setonagi_item->price}}
                                @endif
                                 /
                                  @if ($setonagi_item->item()->tani == 1)
                                  ｹｰｽ
                                  @elseif ($setonagi_item->item()->tani == 2)
                                  ﾎﾞｰﾙ
                                  @elseif ($setonagi_item->item()->tani == 3)
                                  ﾊﾞﾗ
                                  @elseif ($setonagi_item->item()->tani == 4)
                                  Kg
                                  @endif
                                  <span class="setonagi_stock"></spab>（残り{{$setonagi_item->item()->zaikosuu}} ）</span></p>
                                <p>規格：{{$setonagi_item->item()->kikaku}}<br />
                                  @if($setonagi_item->item()->tokkijikou)
                                  特記事項：{{$setonagi_item->item()->tokkijikou}}
                                  @endif
                                </p>
                                <input type="hidden" value="{{$setonagi_item->id}}" name="setonagi_item_id" class="setonagi_item_id" />

                                @if ( Auth::guard('user')->user()->setonagi == 1 )
                                  @if ( Auth::guard('user')->user()->setonagi()->kakebarai_riyou == 1 )
                                  <button name="item_id" value="{{$setonagi_item->item()->id}}" id="{{$setonagi_item->item()->id}}" class="addcart btn btn-warning">カートに入れる</button>
                                  @else
                                  <a name="item_id" href="{{ url('/user/register') }}" class="btn btn-primary">新規会員登録はこちら</a>
                                  @endif
                                @else
                                <button name="item_id" value="{{$setonagi_item->item()->id}}" id="{{$setonagi_item->item()->id}}" class="addcart btn btn-warning">カートに入れる</button>
                                @endif

                                <!-- <div class="article-user">
                                  <img alt="image" src="assets/img/avatar/avatar-1.png">
                                  <div class="article-user-details">
                                    <div class="user-detail-name">
                                      <a href="#">Hasan Basri</a>
                                    </div>
                                    <div class="text-job">Web Developer</div>
                                  </div>
                                </div> -->

                              </div>
                            </article>
                          </div>
                          <div class="modal fade" id="#modal{{$setonagi_item->item()->item_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">商品一覧</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
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
                                      </table>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          @endforeach
                        </div>




          </div>
          @endif
        </section>

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
                <form action="{{ url('/admin/addrecommendcategory') }}" method="POST" class="form-horizontal">
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
                    </table>
                  </div>
                  <input type="hidden" name="category_id" value="" />
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
