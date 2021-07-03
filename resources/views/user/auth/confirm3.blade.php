@extends('layouts.app')

@section('content')




  <section class="section">
    <div class="section-header">
      <h1>お問い合わせ内容確認</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="/">HOME</a></div>
        <div class="breadcrumb-item">お問い合わせ内容確認</div>
      </div>
    </div>

    <div class="section-body">
      <div class="invoice">
        <div class="invoice-print">

          <form action="{{ url('/adddeal') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            <div class="row mt-4 order">
              <div class="col-md-12">
                <div class="section-title">オーダー内容</div>
                <div class="table-responsive">
                  <table class="table table-striped table-hover table-md">
                    <tr>
                      <th class="text-center">商品番号</th>
                      <th class="">商品名</th>
                      <th class="text-center">産地</th>
                      <th class="text-center">在庫数</th>
                      <th class="text-center">特記事項</th>
                      <th class="text-center">金額</th>
                      <th class="text-center">納品先店舗</th>
                      <th class="text-center">規格</th>
                      <th class="text-center">数量</th>
                      <th class="text-center">単位</th>
                      <th class="text-center">納品予定日</th>
                      <th class="text-center">小計</th>
                      <th class="text-center">操作</th>
                    </tr>
                    @foreach($carts as $cart)
                    <tr id="{{$cart->id}}">
                      <td class="cartid_{$cart->id}} text-center">{{$cart->item->item_id}}</td>
                      <td class="cartid_{$cart->id}}">{{$cart->item->item_name}}</td>
                      <td class="cartid_{$cart->id}} text-center">{{$cart->item->sanchi_name}}</td>
                      <td class="cartid_{$cart->id}} text-center">{{$cart->item->zaikosuu}}</td>
                      <td class="cartid_{$cart->id}} text-center">{{$cart->item->tokkijikou}}</td>
                      <td class="cartid_{$cart->id}} teika text-center">
                        <input name="teika[]" class="teika text-center form-control" value="5000" readonly>
                      </td>
                          @foreach($cart->orders as $val)
                            <td class="text-center">
                              <select name="store[]" class="store text-center form-control" value="{{$cart->store_id}}">
                                @foreach($stores as $store)
                                <option value="{{$store->store_id}}">{{$store->tokuisaki_name}} {{$store->store_name}}</option>
                                @endforeach
                                <option value="{{$store->store_id}}">全店舗に追加</option>
                              </select>
                            </td>
                            <td class="text-center">{{$cart->item->kikaku}}</td>
                            <td class="text-center">
                              <select name="quantity[]" class="store text-center form-control" value="{{$cart->store_id}}">
                                @for ($i = 1; $i <= $cart->item->zaikosuu; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                                @endfor
                              </select>
                            </td>
                            <td class="text-center">
                              @if ($cart->item->tani == 1)
                              ｹｰｽ
                              @elseif ($cart->item->tani == 2)
                              ﾎﾞｰﾙ
                              @elseif ($cart->item->tani == 3)
                              ﾊﾞﾗ
                              @elseif ($cart->item->tani == 4)
                              Kg
                              @endif
                            </td>
                            <td class="text-center">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                  </div>
                                </div>
                                <input type="text" name="nouhinbi[]" class="nouhinbi text-center form-control daterange-cus" value="2021-06-26">
                                <inputclass="form-control daterange-cus">
                              </div>
                            </td>
                            <td class="total text-center"></td>
                            <td class="text-center">
                              <button type="button" id="{{$cart->item->id}}" class="removeid_{{$cart->item->id}} removecart btn btn-info">削除</button>
                              <button style="margin-top:10px;" type="button" id="{{$cart->item->id}}" class="cloneid_{{$cart->item->id}} clonecart btn btn-success">配送先を追加</button>
                            <input name="item_id[]" type="hidden" value="{{$cart->item->id}}" />
                            </td>
                          @endforeach
                    @endforeach
                  </table>
                </div>
              </div>
            </div>



            <!-- <div class="row mt-4 order">
              <div class="col-md-4">
                <div class="section-title">通信欄</div>
                <div class="col-md-12">
                    <textarea style="height:200px;" rows="10" class="form-control selectric"></textarea>
                </div>
              </div>
              <div class="col-md-8">
                <div class="section-title">任意の商品</div>
                <div class="table-responsive">
                  <table class="table table-striped table-hover table-md">
                    <tr>
                      <th class="text-center">商品名</th>
                      <th class="text-center">担当</th>
                      <th class="text-center">納品先店舗</th>
                      <th class="text-center">数量</th>
                      <th class="text-center">納品予定日</th>
                      <th class="text-center">操作</th>
                    </tr>
                    <tr>
                        <td class="teika">
                          <input name="nini_item_name[]" class="text-center form-control" value=" ハマグリ 4kg 天然"></td>
                        <td class="text-center">
                          <select name="nini_tantou[]" class="text-center form-control" value="1">
                            <option value="サンプル1">鮮魚</option>
                            <option value="サンプル2">青物</option>
                            <option value="サンプル3">太物</option>
                            <option value="サンプル1">近海</option>
                            <option value="サンプル2">特殊</option>
                            <option value="サンプル3">養魚</option>
                            <option value="サンプル3">水産</option>
                          </select>
                        </td>

                        <td class="text-center">
                          <select name="nini_store[]" class="text-center form-control" value="1">
                            <option value="サンプル1">春日店</option>
                            <option value="サンプル2">緑町店</option>
                            <option value="サンプル3">蔵王店</option>
                          </select>
                        </td>
                        <td class="text-center"><input name="nini_quantity[]" class="quantity text-center form-control" value="1"></td>
                        <td class="text-center">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <i class="fas fa-calendar"></i>
                              </div>
                            </div>
                            <input  type="text" name="nini_nouhinbi[]" class="nouhinbi text-center form-control daterange-cus" value="2021-06-19">
                            <inputclass="form-control daterange-cus">
                          </div>
                        </td>
                        <td class="text-center">
                          <button id="{{$cart->item->id}}" class="removeid_{{$cart->item->id}} removecart btn btn-info">削除</button>
                          <button id="{{$cart->item->id}}" class="btn btn-success">配送先を追加</button>
                        <input name="item_id[]" type="hidden" value="{{$cart->item->id}}" />
                        </td>
                    </tr>
                  </table>

                  <button style="min-width:200px;" id="" class="removecart btn btn-success"><i class="fas fa-plus"></i> 任意の商品を追加</button>
                </div>
              </div>

            </div> -->


            <div class="row mt-4 order">

            </div>

            <div class="row mt-4">
              <div class="col-lg-8">
                <div class="section-title">お支払い</div>
              </div>
              <div class="col-lg-4 text-right">
                <div class="invoice-detail-item">
                  <div class="invoice-detail-name">商品合計</div>
                  <div id="item_total" class="invoice-detail-value"></div>
                </div>
                <div class="invoice-detail-item">
                  <div class="invoice-detail-name">消費税</div>
                  <div id="tax" class="invoice-detail-value"></div>
                </div>
                <hr class="mt-2 mb-2">
                <div class="invoice-detail-item">
                  <div class="invoice-detail-name">合計</div>
                  <div id="all_total" class="invoice-detail-value invoice-detail-value-lg"></div>
                </div>
              </div>
            </div>

            <div class="float-right">
                <button type="submit" class="btn btn-warning">この内容で問い合わせる</button>
            </div>

          </form>
          <br style="clear:both;" />

        </div>
      </div>
    </div>
  </section>



@endsection
