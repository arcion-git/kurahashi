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
          <div class="section-body">


                        <div class="row">
                          @foreach($setonagi_items as $setonagi_item)
                          <div class="col-6 col-md-3 col-lg-3">
                            <article class="article article-style-c">
                              <div class="article-header">
                                <div class="article-image" data-background="/storage/item/{{$setonagi_item->item()->item_id}}_{{$setonagi_item->item()->sku_code}}.jpg" style="background-image: url(&quot;/storage/item/{{$setonagi_item->item()->item_id}}_{{$setonagi_item->item()->sku_code}}.jpg&quot;);">
                                </div>
                              </div>
                              <div class="article-details">
                                <div class="article-category"><a href="#">産地</a><div class="bullet"></div><a href="#">{{$setonagi_item->item()->sanchi_name}}</a></div>
                                <div class="article-title">
                                  <h2><a href="#">{{$setonagi_item->item()->item_name}}</a></h2>
                                </div>
                                <p class="setonagi_price">¥{{$setonagi_item->price}} /
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
                                <button name="item_id" value="{{$setonagi_item->item()->id}}" id="{{$setonagi_item->item()->id}}" class="addcart btn btn-warning">カートに入れる</button>

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
                          @endforeach
                        </div>




          </div>
          @endif
        </section>


@endsection
