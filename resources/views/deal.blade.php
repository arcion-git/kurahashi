@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>取引一覧</h1>
    <div class="section-header-breadcrumb">
      @if ( Auth::guard('user')->check() )
      <div class="breadcrumb-item"><a href="{{ url('/') }}">HOME</a></div>
      <div class="breadcrumb-item active">取引一覧</div>
      @endif
    </div>
  </div>
  <div class="section-body">
        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="clearfix mb-3"></div>

                @foreach($deals as $deal)
                <div class="sp">
                  <div class="deals_sp">
                    <div>
                      注文番号：{{$deal->id}}<br />
                      注文日時：{{$deal->success_time}}<br />
                      受渡希望：{{$deal->first_order_nouhin_yoteibi()}} {{$deal->uketori_time}}<br />
                      種別：{{$deal->first_cart_addtype()}}<br />
                      @if(Auth::guard('user')->user()->setonagi())
                      @else
                      納品先店舗：{{$deal->first_order_nouhin_tokuisaki_name_and_store_name()}}
                      @endif
                    </div>
                    <div class="deals_sp_btn">
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
                      <div class="badge badge-info">ﾘﾋﾟｰﾄｵｰﾀﾞｰ</div>
                      @endif
                      <div class="mt-2">
                        <a href="{{ url('/user/deal/'.$deal->id) }}"><button class="btn btn-primary">詳細</button></a>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach

                <div class="pc">
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <tr>
                        <th class="text-center">注文番号</th>
                        <!-- <th class="text-center">お名前</th> -->
                        <!-- <th class="text-center">お問い合わせ日時</th> -->
                        <th class="text-center">種別</th>

                        @if(Auth::guard('user')->user()->setonagi())
                        @else
                        <th class="text-center">納品先店舗</th>
                        @endif

                        <th class="text-center">納品予定日</th>
                        <th class="text-center">受け取り予定時間</th>
                        <th class="text-center">発注日時</th>
                        <th class="text-center">状態</th>
                        <th class="text-center">操作</th>
                      </tr>

                      @foreach($deals as $deal)
                      <tr>
                        <td class="text-center" class="text-center">
                          {{$deal->id}}
                        </td>
                        <!-- <td class="text-center">
                          {{$deal->user->name}}
                        </td> -->
                        <!-- <td class="text-center">
                          {{$deal->created_at}}
                        </td> -->
                        <td class="text-center">
                          {{$deal->first_cart_addtype()}}
                        </td>

                        @if(Auth::guard('user')->user()->setonagi())
                        @else
                        <td class="text-center">
                          {{$deal->first_order_nouhin_tokuisaki_name_and_store_name()}}
                        </td>
                        @endif

                        <td class="text-center">
                          {{$deal->first_order_nouhin_yoteibi()}}
                        </td>
                        <td class="text-center">
                          {{$deal->uketori_time}}
                        </td>
                        <td class="text-center">
                          {{$deal->success_time}}
                        </td>
                        <td class="text-center">
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
                          <div class="badge badge-info">ﾘﾋﾟｰﾄｵｰﾀﾞｰ</div>
                          @endif
                        </td>
                        <td class="text-center">
                          <a href="{{ url('/user/deal/'.$deal->id) }}"><button class="btn btn-primary">詳細</button></a>
                        </td>
                      </tr>
                      @endforeach

                    </table>
                  </div>
                </div>

                <div class="float-right">
                  <nav>
                    <ul class="pagination">
                      {{ $deals->links() }}
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
