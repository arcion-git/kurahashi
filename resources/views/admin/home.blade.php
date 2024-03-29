@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>取引一覧</h1>
    <div class="section-header-breadcrumb">
      @if(isset($user))
      <div class="breadcrumb-item active"><a href="{{ url('/admin/home') }}">HOME</a></div>
      <div class="breadcrumb-item"><a href="{{ url('/admin/user') }}">顧客一覧</a></div>
      <div class="breadcrumb-item">取引一覧（{{$user->name}} 様）</a></div>
      @endif
    </div>
  </div>



  @if(!empty($deals))
  <div class="section-body">
    @if(isset($user))
    <div class="invoice">
      <div class="invoice-print">
        <div class="row">
          <div class="col-lg-12">
            <div class="invoice-title">
              <h3>{{$user->name}} <span class="small">様</span></h3>
            </div>
            <hr>
            <div class="row">
              <div class="col-md-6">
                <address>
                  <strong>ご連絡先:</strong><br>
                  {{ $user->tel }}<br>
                  {{ $user->email }}
                </address>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <div class="section-title">取引一覧</div>
            <div class="clearfix mb-3"></div>
    @else
    <div class="card">
      <div class="card-body">
    @endif
        <div class="row mt-4">
          <div class="col-12">
            <div class="float-right">
              @if ( Auth::guard('admin')->check() )
              @if(isset($tokuisakis))
              <form id="admin_deal_search" action="{{ url('/admin/search') }}" enctype="multipart/form-data" method="GET" class="form-inline mr-auto">
                @csrf
                <div class="input-group">
                  <div class="search-element">

                    <select name="tokuisaki_name" id="tokuisaki_name" class="postform">
                      @if(isset($tokuisaki_name))
                        <option class="level-0" value="{{$tokuisaki_name}}">{{$tokuisaki_name}}</option>
                      @endif
                      <option value="すべてバイヤー">すべてバイヤー</option>
                      @foreach($tokuisakis as $tokuisaki)
                        <option class="level-0" value="{{$tokuisaki->tokuisaki_name}}">{{$tokuisaki->tokuisaki_name}}</option>
                      @endforeach
                    </select>

                    @if(isset($tokuisaki_name))
                      @if($tokuisaki_name != 'すべてバイヤー')
                      <select name="store_name" id="store_name" class="postform">
                        @if(isset($store_name))
                          <option class="level-0" value="{{$store_name}}">{{$store_name}}</option>
                        @endif
                        <option value="すべての店舗">すべての店舗</option>
                        @foreach($stores as $store)
                          <option class="level-0" value="{{$store->store_name}}">{{$store->store_name}}</option>
                        @endforeach
                      </select>
                      @endif
                    @endif

                    <select name="cat" id="cat" class="postform">
                      @if(isset($cat))
                        <option class="level-0" value="{{$cat}}">{{$cat}}</option>
                      @endif
                      <option value="すべての取引">すべての取引</option>
                      <!-- <option class="level-0" value="交渉中">交渉中</option> -->
                      <option class="level-0" value="受注済">受注済</option>
                      <option class="level-0" value="キャンセル">キャンセル</option>
                      <option class="level-0" value="リピートオーダー">リピートオーダー</option>
                    </select>

                    <input class="form-control" type="text" name="search" placeholder="検索" aria-label="Search" data-width="250" style="width: 250px;" value="@if(isset($search)){{$search}}@endif">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </form>
              @endif
              @endif
            </div>


            <div class="clearfix mb-3"></div>
            <div class="table-responsive">
              <table class="table table-striped">
                <tr>
                  <th class="text-center">注文番号</th>
                  <th class="text-center">バイヤー</th>
                  <th class="text-center">お名前</th>
                  <th class="text-center">種別</th>
                  <th class="text-center">納品予定日</th>
                  <th class="text-center">受け取り予定時間</th>
                  <!-- <th class="text-center">お問い合わせ日時</th> -->
                  <th class="text-center">受注日時</th>
                  <th class="text-center">状態</th>
                  <th class="text-center">操作</th>
                </tr>
                @foreach($deals as $deal)
                <tr>
                  <td class="text-center">
                    {{$deal->id}}
                  </td>
                  <td class="text-center">
                    @if($deal->user->setonagi == 1)
                    @else
                    {{$deal->user->tokuisaki_name()}}
                    @endif
                  </td>
                  <td class="text-center">
                    {{$deal->user->name}}
                  </td>
                  <td class="text-center">
                    {{$deal->first_cart_addtype()}}
                  </td>
                  <td class="text-center">
                    {{$deal->first_order_nouhin_yoteibi()}}
                  </td>
                  <td class="text-center">
                    {{$deal->uketori_time}}
                  </td>
                  <!-- <td class="text-center">
                    {{$deal->created_at}}
                  </td> -->
                  <td class="text-center">
                    {{$deal->success_time}}
                  </td>
                  <td class="text-center">
                    @if($deal->status == '発注済')
                    <div class="badge badge-success">受注済</div>
                    @elseif($deal->status == '交渉中')
                    <div class="badge badge-warning">交渉中</div>
                    @elseif($deal->status == '確認待')
                    <div class="badge badge-info">確認待</div>
                    @elseif($deal->status == 'リピートオーダー')
                    <div class="badge badge-info">リピートオーダー</div>
                    @elseif($deal->status == 'キャンセル')
                    <div class="badge badge-danger">キャンセル</div>
                    @endif
                  </td>
                  <td class="text-center">
                    <a href="{{ url('/admin/deal/'.$deal->id) }}"><button class="btn btn-primary">詳細を見る</button></a>
                  </td>
                </tr>
                @endforeach
              </table>
            </div>

            <div class="float-right">
              <nav>
                <ul class="pagination">
                  {{ $deals->appends(Request::query())->links() }}
                </ul>
              </nav>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  @else
  <p>取引情報が見つかりませんでした</p>
  @endif

</section>






@endsection
