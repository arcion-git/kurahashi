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
                      <th>商品名</th>
                      <th class="text-center">金額</th>
                      <th class="text-center">個数</th>
                      <th class="text-center">小計</th>
                      <th class="text-center">操作</th>
                    </tr>
                    @foreach($carts as $cart)
                    <tr>
                      <td>{{$cart->item->item_name}}</td>
                      <td class="teika text-center"><input name="teika[]" class="teika text-center form-control" value="{{$cart->item->teika}}" readonly></td>
                      <td class="text-center"><input name="quantity[]" class="quantity text-center form-control" value="{{$cart->quantity}}"></td>
                      <td class="total text-center"></td>
                      <td class="text-center"><button id="{{$cart->item->id}}" class="removeid_{{$cart->item->id}} removecart btn btn-info">削除</button>
<input name="item_id[]" type="hidden" value="{{$cart->item->id}}" />
                      </td>
                    </tr>
                    @endforeach
                  </table>
                </div>
              </form>
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

              </div>
            </div>
          </form>
        </div>
        <hr>
      </div>
    </div>
  </section>
@endsection
