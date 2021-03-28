@extends('layouts.app')

@section('content')




  <section class="section">


    <div class="section-header">
      <h1>取引詳細</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item">取引一覧</div>
        <div class="breadcrumb-item">取引詳細</div>
      </div>
    </div>

    <div class="section-body">
      <div class="invoice">
        <div class="invoice-print">
          <div class="row">
            <div class="col-lg-12">
              <div class="invoice-title">
                <h3>{{$deal->user->last_name}}{{ $deal->user->first_name }} 様<span class="small"></span>（{{ $deal->user->company }}）</h3>
                <div class="invoice-number">
                  @if($deal->success_flg)
                  <div class="badge badge-success">受注済</div>
                  @else
                  <div class="badge badge-warning">交渉中</div>
                  @endif
                  オーダー番号 #{{$deal->id}}
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-md-6">
                  <address>
                    <strong>ご住所:</strong><br>
                    〒{{ $deal->user->address01 }}<br>{{ $deal->user->address02 }}{{ $deal->user->address03 }}{{ $deal->user->address04 }}
                    </address>
                </div>
                <div class="col-md-6">
                  <address>
                    <strong>商品の送付先:</strong><br>
                    〒{{ $deal->user->address01 }}<br>{{ $deal->user->address02 }}{{ $deal->user->address03 }}{{ $deal->user->address04 }}
                    </address>
                </div>
                <div class="col-md-6">
                  <address>
                    <strong>ご連絡先:</strong><br>
                    {{ $deal->user->tel }}<br>
                    {{ $deal->user->email }}
                  </address>
                </div>
                <div class="col-md-6">
                  <address>
                    <strong>お問い合わせ日時:</strong><br>
                    {{ $deal->created_at }}<br>
                    @if($deal->success_flg)
                    <br><strong>発注日時:</strong><br>
                    {{ $deal->success_time }}<br>
                    @else
                    @endif
                  </address>
                </div>
              </div>
            </div>
          </div>


          <form action="{{ url('/addsuscess') }}" method="POST" class="form-horizontal">
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
                      @if($deal->success_flg)
                      @else
                      <th class="text-center">操作</th>
                      @endif
                    </tr>
                    @foreach($carts as $cart)
                    <tr>
                      <td>{{$cart->item->item_name}}</td>
                      <td class="teika text-center">
                        @if($deal->success_flg)
                        <input name="teika[]" class="teika text-center form-control" value="{{$cart->item->teika}}" readonly>
                        @else
                        <input name="teika[]" class="teika text-center form-control" value="{{$cart->item->teika}}">
                        @endif
                      </td>
                      <td class="text-center">
                        @if($deal->success_flg)
                        <input name="quantity[]" class="quantity text-center form-control" value="{{$cart->quantity}}" readonly>
                        @else
                        <input name="quantity[]" class="quantity text-center form-control" value="{{$cart->quantity}}">
                        @endif
                      </td>
                      <td class="total text-center"></td>
                      @if($deal->success_flg)
                      @else
                      <td class="text-center"><button id="{{$cart->item->id}}" class="removeid_{{$cart->item->id}} removecart btn btn-info">削除</button>
<input name="item_id[]" type="hidden" value="{{$cart->item->id}}" />
                      </td>
                      @endif
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

                @if ( Auth::guard('user')->check() )
                @if($deal->success_flg)
                @else
                <div class="float-right">
                    <button type="submit" name="deal_id" value="{{ $deal->id }}" class="btn btn-warning">この内容で発注する</button>
                </div>
                @endif
                @endif

              </div>
            </div>
          </form>
        </div>
        <hr>
      </div>
    </div>
  </section>
@endsection
