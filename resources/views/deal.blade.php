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
                <div class="table-responsive">
                  <table class="table table-striped">
                    <tr>
                      <th class="text-center">オーダーID</th>
                      <th class="text-center">お名前</th>
                      <th class="text-center">お問い合わせ日時</th>
                      <th class="text-center">発注日時</th>
                      <th class="text-center">状態</th>
                      <th class="text-center">操作</th>
                    </tr>

                    @foreach($deals as $deal)
                    <tr>
                      <td class="text-center" class="text-center">
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
                        <div class="badge badge-warning">問合中</div>
                        @else
                        <div class="badge badge-success">発注済</div>
                        @endempty
                      </td>
                      <td class="text-center">
                        <a href="{{ url('/user/deal/'.$deal->id) }}"><button class="btn btn-primary">詳細を見る</button></a>
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
</section>






@endsection
