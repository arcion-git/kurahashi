@extends('layouts.app')

@section('content')


        <section class="section">
          <div class="section-header">
            <h1>
              @if(isset($category_name))
              {{$category_name}}
              @else
              特集商品
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
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <!-- <div class="float-left">
                      <h2 class="section-title">担当のおすすめ商品</h2>
                    </div> -->

                    <div class="clearfix mb-3"></div>
                    @foreach($setonagi_items as $setonagi_item)
                    <div class="col-4">
                        <img src="/storage/item/{{$setonagi_item->item()->item_id}}_{{$setonagi_item->item()->sku_code}}.jpg" width="100%"/>
                        <p class="text-center">{{$setonagi_item->item()->id}}</p>
                        <p class="">{{$setonagi_item->item()->item_name}}</p>
                        <p class="text-center">{{$setonagi_item->item()->sanchi_name}}</p>
                        <p class="text-center">{{$setonagi_item->item()->kikaku}}</p>
                        <p class="text-center">
                          @if ($setonagi_item->item()->tani == 1)
                          ｹｰｽ
                          @elseif ($setonagi_item->item()->tani == 2)
                          ﾎﾞｰﾙ
                          @elseif ($setonagi_item->item()->tani == 3)
                          ﾊﾞﾗ
                          @elseif ($setonagi_item->item()->tani == 4)
                          Kg
                          @endif
                        </p>
                        <p class="text-center">{{$setonagi_item->item()->zaikosuu}}</p>
                        <p class="text-center">{{$setonagi_item->item()->tokkijikou}}</p>
                        <p class="text-center"><button name="item_id" value="{{$setonagi_item->id}}" id="{{$setonagi_item->id}}" class="addcart btn btn-warning">カートに入れる</button></p>
                      <!-- </form> -->
                    </div>
                    @endforeach

                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif
        </section>


@endsection
