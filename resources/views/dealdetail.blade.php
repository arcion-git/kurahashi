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
                @if ( Auth::guard('admin')->check() )
                <h3>{{$deal->user->name}} <span class="small">様</span></h3>
                @endif
              </div>
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
                  @elseif($deal->status == 'リピートオーダー')
                    @if ( Auth::guard('admin')->check() )
                      <a href="{{url('/admin/user/repeatorder/')}}/{{$user_id}}">
                        <div class="badge badge-info">リピートオーダー</div>
                      </a>
                    @else
                      <a href="{{url('/repeatorder/')}}">
                        <div class="badge badge-info">リピートオーダー</div>
                      </a>
                    @endif
                  @endif
                  注文番号 #{{$deal->id}}
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
                @if ( Auth::guard('admin')->check() )
                <div class="col-md-6">
                  <address>
                    <strong>ご連絡先:</strong><br>
                    {{ $deal->user->tel }}<br>
                    {{ $deal->user->email }}
                  </address>
                </div>
                @endif
                <div class="col-md-6">
                  <address>
                    <!-- <strong>お問い合わせ日時:</strong><br>
                    {{ $deal->created_at }}<br> -->
                    @if($deal->status == '発注済')
                    <strong>注文日時:</strong><br>
                    {{ $deal->success_time }}<br>
                    @endif
                    @if($deal->status == 'キャンセル')
                    <strong>キャンセル日時:</strong><br>
                    {{ $deal->cancel_time }}<br>
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

            @if($deal->first_order_nouhin_yoteibi())
              <div class="row mt-4 order">
                <div class="col-md-12">
                  <div class="section-title">商品受け渡し予定日：{{$deal->first_order_nouhin_yoteibi()}}</div>
                </div>
              </div>
            @endif

            <div class="row mt-4 order">
              <div class="col-md-12">
                <div id="dealorder"></div>
                <input name="deal_id" type="hidden" value="{{$deal->id}}" id="deal_id"/>

                <div class="float-right">
                @if ( Auth::guard('user')->check() )
                  @if($deal->status == 'キャンセル')
                  @else
                    @if($deal_cancel_button)
                      @if($deal->status == 'リピートオーダー')
                      @else
                        {{ csrf_field() }}
                          <div id="deal_cancel_button" class="btn btn-danger">キャンセル</div>
                      @endif
                    @endif
                  @endif
                  @if($deal->status == '発注済' or $deal->status == 'キャンセル' or $deal->status == 'リピートオーダー')
                  @else
                    <button type="submit" name="deal_id" value="{{ $deal->id }}" class="btn btn-warning">この内容で発注する</button>
                  @endif
                @endif
                <!-- <div class="float-right">
                    <button type="submit" name="deal_id" value="{{ $deal->id }}" class="btn btn-warning">キャンセルする</button>
                </div> -->
                @if($deal->status == 'キャンセル')
                @else
                @if ( Auth::guard('admin')->check() )
                  @if ( $setonagi )
                    <button id="kingaku_henkou" type="submit" name="deal_id" value="{{ $deal->id }}" class="btn btn-warning">金額を変更する</button>
                  @endif
                @endif
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

  <script>
  $(function(){

    //ＵＲＬのパラメータを取得するための関数
    function getUrlParam(param){
        var pageUrl = window.location.search.substring(1);
        var urlVar = pageUrl.split('&');
        for (var i = 0; i < urlVar.length; i++)
        {
            var paramName = urlVar[i].split('=');
            if (paramName[0] == param)
            {
                return decodeURI(paramName[1]);
            }
        }
    }
    $(function() {
        var order_id = getUrlParam('order_id');
        var nouhin_yoteibi = getUrlParam('nouhin_yoteibi');
        if (order_id) {
          console.log(order_id);
          Swal.fire({
            html: '納品予定日' + nouhin_yoteibi + 'は指定できません。<br />納品予定日を修正してください。',
            // position: 'top-end',
            // toast: true,
            icon: 'warning',
            showConfirmButton: false,
            // timer: 1500
          });
        }
    });

    $("#kingaku_henkou").click(function() {
      var submitButton = $(this);
      // 二重送信を防止するためにボタンを無効化
      submitButton.addClass("disabled_btn");
    });

    // $(function() {
    //     var cancel_error = getUrlParam('cancel_error');
    //     if (cancel_error) {
    //       Swal.fire({
    //         html: '締め時間を過ぎているためキャンセルできません。',
    //         // position: 'top-end',
    //         // toast: true,
    //         icon: 'warning',
    //         showConfirmButton: false,
    //         // timer: 1500
    //       });
    //     }
    // });
  });
  </script>
@endsection
