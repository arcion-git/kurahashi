@extends('layouts.app')

@section('content')


        <section class="section">
          <div class="section-header">
            <h1>
              「{{$search}}」の検索結果
            </h1>
          </div>


          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="float-left">
                      <h2 class="section-title">{{$search}}の検索結果</h2>
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
                          <!-- <th class="text-center">納品予定日</th>
                          <th class="text-center">参考価格</th>
                          <th class="text-center">個数</th> -->
                          <th class="text-center" style="min-width:180px;">操作</th>
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
                          <td class="text-center">{{$item->tokkijikou}}</td>

                          <!-- <td class="text-center">{{$item->nouhin_yoteibi_start}}</td>
                          <td class="text-center">
                            ¥ {{$item->teika}}
                          </td>
                          <td class="text-center"><input name="quantity" class="quantity form-control" value="1"></td> -->
                          <td class="text-center"><button id="{{$item->id}}" class="addcart btn btn-warning">カートに入れる</button></td>
                        </tr>
                        @endforeach
                      </table>
                    </div>
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
        </section>






@endsection
