@extends('layouts.app')

@section('content')




  <section class="section">


    <div class="section-header">
      <h1>取引詳細</h1>
      <div class="section-header-breadcrumb">
        @if ( Auth::guard('user')->check() )
        <div class="breadcrumb-item"><a href="{{ url('/') }}">HOME</a></div>
        <div class="breadcrumb-item"><a href="{{ url('/deal') }}">取引一覧</a></div>
        @endif
        @if ( Auth::guard('admin')->check() )
        <div class="breadcrumb-item"><a href="{{ url('/admin/home') }}">取引一覧</a></div>
        @endif
        <div class="breadcrumb-item active">取引詳細</div>
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
                  @if ( Auth::guard('user')->check() )
                  <div class="badge badge-success">発注済</div>
                  @endif
                  @if ( Auth::guard('admin')->check() )
                  <div class="badge badge-success">受注済</div>
                  @endif
                  @else
                  @if ( Auth::guard('user')->check() )
                  <div class="badge badge-warning">問合中</div>
                  @endif
                  @if ( Auth::guard('admin')->check() )
                  <div class="badge badge-warning">交渉中</div>
                  @endif
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




          <!-- <form action="{{ url('/updatecart') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            <tr>
              <td>
              <input name="discount" type="text" value="5000" />
              <input name="cart_id" type="text" value="1"/>
              <input name="quantity" type="text" value="5"/>
              </td>
            </tr>
            <div class="float-right">
                <button type="submit" name="deal_id" value="{{ $deal->id }}" class="btn btn-warning">この内容で発注する</button>
            </div>
          </form> -->



          <form action="{{ url('/addsuscess') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            <div class="row mt-4 order">
              <div class="col-md-12">
                <div class="section-title">オーダー内容</div>
                <div class="table-responsive">

                  <table class="table table-striped table-hover table-md">
                  <tr>
                  	<th>商品名</th>
                    @if ( Auth::guard('admin')->check() )
                  	<th class="text-center">定価</th>
                    @endif
                  	<th class="text-center">単価</th>
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
                    @if ( Auth::guard('admin')->check() )
                  	<td class="text-center">{{$cart->item->tanka}}</td>
                    @endif
                  	<td class="teika text-center">

                  		@if ( Auth::guard('admin')->check() )
                  			@if($deal->success_flg)
                  				@if(isset($cart->discount))
                  				<input name="discount[]" class="teika text-center form-control" value="{{$cart->discount}}" readonly>
                  				@else
                  				<input name="teika[]" class="teika text-center form-control" value="{{$cart->item->teika}}" readonly>
                  				@endif
                  			@else
                  				@if(isset($cart->discount))
                  				<input name="discount[]" class="teika text-center form-control" value="{{$cart->discount}}">
                  				@else
                  				<input name="teika[]" class="teika text-center form-control" value="{{$cart->item->teika}}">
                  				@endif
                  			@endif
                  		@endif

                  		@if ( Auth::guard('user')->check() )
                  			@if(isset($cart->discount))
                  			<input name="discount[]" class="teika text-center form-control" value="{{$cart->discount}}" readonly>
                  			@else
                  			<input name="teika[]" class="teika text-center form-control" value="{{$cart->item->teika}}" readonly>
                  			@endif
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
                  	<input name="cart_id[]" type="hidden" value="{{$cart->id}}" class="cart_id"/>
                  	</td>
                  	@endif
                  </tr>
                  @endforeach
                  </table>


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
                <input name="deal_id" type="hidden" value="{{$deal->id}}" id="deal_id"/>

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
