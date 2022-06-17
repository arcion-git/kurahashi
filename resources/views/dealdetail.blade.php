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
                <h3>{{$deal->user->name}} <span class="small">様</span></h3>
                <div class="invoice-number">
                  @if($deal->status == '発注済')
                  @if ( Auth::guard('user')->check() )
                  <div class="badge badge-success">発注済</div>
                  @endif
                  @if ( Auth::guard('admin')->check() )
                  <div class="badge badge-success">受注済</div>
                  @endif
                  @elseif($deal->status == '交渉中')
                  @if ( Auth::guard('user')->check() )
                  <div class="badge badge-warning">問合中</div>
                  @endif
                  @if ( Auth::guard('admin')->check() )
                  <div class="badge badge-warning">交渉中</div>
                  @endif
                  @elseif($deal->status == '確認待')
                  @if ( Auth::guard('user')->check() )
                  <div class="badge badge-info">確認待</div>
                  @endif
                  @if ( Auth::guard('admin')->check() )
                  <div class="badge badge-info">確認待</div>
                  @endif
                  @elseif($deal->status == 'キャンセル')
                  <div class="badge badge-danger">キャンセル</div>
                  @endif
                  オーダー番号 #{{$deal->id}}
                </div>
              </div>
              <hr>
              <div class="row">
                <!-- <div class="col-md-6">
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
                </div> -->
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

          @if ( Auth::guard('admin')->check() )
          <form action="{{ url('/admin/discount') }}" method="POST" class="form-horizontal">
          @endif
          @if ( Auth::guard('user')->check() )
          <form action="{{ url('/addsuscess') }}" method="POST" class="form-horizontal">
          @endif
            {{ csrf_field() }}
            <div class="row mt-4 order">
              <div class="col-md-12">
                <div class="section-title">オーダー内容</div>
                  <div id="dealorder"></div>
                <input name="deal_id" type="hidden" value="{{$deal->id}}" id="deal_id"/>

                <div class="float-right">


                @if ( Auth::guard('user')->check() )

                @if($deal->status == 'キャンセル')
                @else
                  {{ csrf_field() }}
                    <div id="deal_cancel_button" class="btn btn-danger">キャンセル</div>
                @endif

                @if($deal->status == '発注済')
                @else
                    <button type="submit" name="deal_id" value="{{ $deal->id }}" class="btn btn-warning">この内容で発注する</button>
                @endif
                @endif
                <!-- <div class="float-right">
                    <button type="submit" name="deal_id" value="{{ $deal->id }}" class="btn btn-warning">キャンセルする</button>
                </div> -->
                @if ( Auth::guard('admin')->check() )
                    <button type="submit" name="deal_id" value="{{ $deal->id }}" class="btn btn-warning">更新を通知する</button>
                @endif
                </div>
                <!-- <button type="button" class="updateorder btn">更新確認</button> -->

              </div>
            </div>
          </form>

          @if($deal->status == 'キャンセル')
          @else
          <form action="{{ url('/dealcancel') }}" id="deal_cancel" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            <div class="float-right mt-3">
                <input type="hidden" type="submit" name="deal_id" value="{{ $deal->id }}" />
            </div>
          </form>
          @endif



        </div>
        <hr>
      </div>
    </div>
  </section>
@endsection
