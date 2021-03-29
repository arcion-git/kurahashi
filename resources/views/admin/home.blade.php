@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>取引一覧</h1>
    <div class="section-header-breadcrumb">
    </div>
  </div>
  <div class="section-body">
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-body">

            <div class="float-right">
              <form>
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="検索">
                  <div class="input-group-append">
                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </form>
            </div>

            <div class="clearfix mb-3"></div>

            <div class="table-responsive">
              <table class="table table-striped">
                <tr>
                  <th>お名前</th>
                  <th>会社名</th>
                  <th>お問い合わせ日時</th>
                  <th>受注日時</th>
                  <th>状態</th>
                  <th>操作</th>
                </tr>

                @foreach($deals as $deal)
                <tr>
                  <td>
                    {{$deal->user->last_name}} {{$deal->user->first_name}}
                  </td>
                  <td>
                    {{$deal->user->company}}
                  </td>
                  <td>
                    {{$deal->created_at}}
                  </td>
                  <td>
                    {{$deal->success_time}}
                  </td>
                  <td>
                    @empty($deal->success_flg)
                    <div class="badge badge-warning">交渉中</div>
                    @else
                    <div class="badge badge-success">受注済</div>
                    @endempty
                  </td>
                  <td>
                    <a href="{{ url('/admin/deal/'.$deal->id) }}"><button class="btn btn-primary">詳細を見る</button></a>
                  </td>
                </tr>
                @endforeach

              </table>
            </div>
            <div class="float-right">
              <nav>
                <ul class="pagination">
                  <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Previous">
                      <span aria-hidden="true">&laquo;</span>
                      <span class="sr-only">Previous</span>
                    </a>
                  </li>
                  <li class="page-item active">
                    <a class="page-link" href="#">1</a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="#">2</a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="#">3</a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                      <span aria-hidden="true">&raquo;</span>
                      <span class="sr-only">Next</span>
                    </a>
                  </li>
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
