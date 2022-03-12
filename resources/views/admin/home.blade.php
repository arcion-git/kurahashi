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
              <h3>{{ $user->name}} <span class="small">様</span></h3>
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
            <!-- <div class="float-right">
              <form>
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="検索">
                  <div class="input-group-append">
                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </form>
            </div>
            <ul class="navbar-nav float-right">
              <div class="form-group">
                <select class="custom-select">
                  <option selected="">全ての取引</option>
                  <option value="1">交渉中</option>
                  <option value="2">受注済み</option>
                  <option value="3">キャンセル</option>
                </select>
              </div>
            </ul> -->
            <div class="clearfix mb-3"></div>
            <div class="table-responsive">
              <table class="table table-striped">
                <tr>
                  <th class="text-center">取引ID</th>
                  <th class="text-center">お名前</th>
                  <th class="text-center">お問い合わせ日時</th>
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
                    {{$deal->user->name}}
                  </td>
                  <td class="text-center">
                    {{$deal->created_at}}
                  </td>
                  <td class="text-center">
                    {{$deal->success_time}}
                  </td>
                  <td class="text-center">
                    @empty($deal->success_flg)
                    <div class="badge badge-warning">交渉中</div>
                    @else
                    <div class="badge badge-success">受注済</div>
                    @endempty
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
                  {{ $deals->links() }}
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
