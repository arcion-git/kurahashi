@extends('layouts.app')


@section('content')


        <section class="section">
          <div class="section-header">
            <h1>
              @if(isset($category_name))
              {{$category_name}}
              @else
              @if(Auth::guard('user')->user()->c_user())
               商品一覧
              @else
               限定お買い得商品
              @endif
              @endif
            </h1>
          </div>


          <!-- <a href="{{ url('/user/test') }}"><button class="btn btn-warning">TEST</button></a> -->

          @if(!isset($setonagi_items[0]))
          <p>
            限定お買い得商品が見つかりませんでした。
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
                          @if($now >= $setonagi_item->start_date && $now < $setonagi_item->end_date)
                          <div class="col-12 col-md-4 col-lg-3">
                            <article class="article article-style-c">
                              <div class="article-header">
                                <!-- 画像表示 -->
                                <div id="carouselExampleIndicators{{$setonagi_item->item()->item_id}}" class="carousel slide" data-ride="">
                                  <ol class="carousel-indicators">
                                    <li data-target="#carouselExampleIndicators{{$setonagi_item->item()->item_id}}" data-slide-to="0" class="active"></li>
                                    @for($i = 1; $i < 5; $i++)
                                      <?php $filename = public_path().'/storage/item/'.$setonagi_item->item()->item_id.'_'.$i.'.jpg'; ?>
                                      @if(file_exists($filename))
                                      <li data-target="#carouselExampleIndicators{{$setonagi_item->item()->item_id}}" data-slide-to="{{$i}}" class=""></li>
                                      @else
                                      @endif
                                    @endfor
                                  </ol>
                                  <div class="carousel-inner">
                                    <div class="carousel-item active">
                                      <?php $filename = public_path().'/storage/item/'.$setonagi_item->item()->item_id.'.jpg'; ?>
                                      @if(file_exists($filename))
                                      <a href="/storage/item/{{$setonagi_item->item()->item_id}}.jpg" data-fancybox="images-{{$setonagi_item->item()->item_id}}">
                                        <img class="d-block w-100" src="/storage/item/{{$setonagi_item->item()->item_id}}.jpg" alt="First slide" onerror="this.src='{{ asset('img/no_image.jpg') }}'; this.classList.add('disable_link');">
                                      </a>
                                      @else
                                        <img class="d-block w-100" src="{{ asset('img/no_image.jpg') }}">
                                      @endif
                                    </div>
                                    @for($i = 1; $i < 5; $i++)
                                      <?php $filename = public_path().'/storage/item/'.$setonagi_item->item()->item_id.'_'.$i.'.jpg'; ?>
                                      @if(file_exists($filename))
                                      <div class="carousel-item">
                                        <a href="/storage/item/{{$setonagi_item->item()->item_id}}_{{$i}}.jpg" data-fancybox="images-{{$setonagi_item->item()->item_id}}">
                                          <img class="d-block w-100" src="/storage/item/{{$setonagi_item->item()->item_id}}_{{$i}}.jpg" alt="slide" class="">
                                        </a>
                                      </div>
                                      @else
                                      @endif
                                    @endfor
                                  </div>
                                  <a class="carousel-control-prev" href="#carouselExampleIndicators{{$setonagi_item->item()->item_id}}" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                  </a>
                                  <a class="carousel-control-next" href="#carouselExampleIndicators{{$setonagi_item->item()->item_id}}" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                  </a>
                                </div>
                              </div>
                              <div class="article-details">
                                <div class="article-category">産地<div class="bullet"></div>{{$setonagi_item->item()->sanchi_name}}</div>
                                <div class="article-title">{{$setonagi_item->item()->item_name}}</div>
                                <p class="setonagi_price">¥
                                @if ( Auth::guard('user')->user()->setonagi == 1 )
                                  @if ( Auth::guard('user')->user()->setonagi()->kakebarai_riyou == 1 )
                                  {{number_format($setonagi_item->price)}}
                                  @elseif ( Auth::guard('user')->user()->setonagi()->setonagi_ok == 1 )
                                  {{number_format($setonagi_item->price)}}
                                  @else
                                  ---
                                  @endif
                                @else
                                  {{number_format($setonagi_item->price)}}
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
                                  <span class="setonagi_stock">（残り{{$setonagi_item->item()->zaikosuu}} ）</span>
                                </p>
                                <p><button class="tokkijikou_btn" type="button" data-toggle="collapse" data-target="#collapseExample{{$setonagi_item->item()->item_id}}" aria-expanded="true" aria-controls="collapseExample">詳細を見る<i class="fa fa-chevron-down"></i></button></p>
                                <div class="collapse" id="collapseExample{{$setonagi_item->item()->item_id}}" style="">
                                  <p>
                                    規格：{{$setonagi_item->item()->kikaku}}<br />
                                    @if($setonagi_item->item()->tokkijikou)
                                    特記事項：<br>{{$setonagi_item->item()->tokkijikou}}
                                    @endif
                                  </p>
                                </div>

                                <input type="hidden" value="{{$setonagi_item->id}}" name="setonagi_item_id" class="setonagi_item_id" />

                                @if ( Auth::guard('user')->user()->setonagi == 1 )
                                  @if ( Auth::guard('user')->user()->setonagi()->kakebarai_riyou == 1 || Auth::guard('user')->user()->setonagi()->setonagi_ok == 1)
                                  <button name="item_id" value="{{$setonagi_item->item()->id}}" id="{{$setonagi_item->item()->id}}" class="addcart btn btn-warning">カートに入れる</button>
                                  @else
                                  <div class="btn btn-primary">現在審査中です</div>
                                  @endif
                                @else
                                <button name="item_id" value="{{$setonagi_item->item()->id}}" id="{{$setonagi_item->item()->id}}" class="addcart btn btn-warning">カートに入れる</button>
                                @endif

                                @if($setonagi_item->favoriteitem())
                                <!-- <button name="item_id" value="{{$setonagi_item->item()->id}}" id="{{$setonagi_item->item()->id}}" class="removefavoriteitem"><i class="fa fa-heart"></i></button> -->
                                @else
                                <!-- <button name="item_id" value="{{$setonagi_item->item()->id}}" id="{{$setonagi_item->item()->id}}" class="addfavoriteitem"><i class="far fa-heart"></i></button> -->
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
                          @endif
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



        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
        <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>


        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/luminous-lightbox@2.3.2/dist/luminous-basic.min.css">
        <script src="https://cdn.jsdelivr.net/npm/luminous-lightbox@2.3.2/dist/luminous.min.js"></script>
        <script>
        new LuminousGallery(document.querySelectorAll('.luminous'));
        </script> -->
<script>
function handleError(img) {
  img.src = "{{ asset('img/no_image.jpg') }}";
  img.classList.add('disable_link');
  img.parentNode.classList.add('disable_link');
}
</script>
@endsection
