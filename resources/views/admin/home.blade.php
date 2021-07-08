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

            <ul class="navbar-nav float-left">


              <div class="form-group">
                <select class="custom-select">
                  <option selected="">全ての取引</option>
                  <option value="1">交渉中</option>
                  <option value="2">受注済み</option>
                  <option value="3">過去受注</option>
                  <option value="3">キャンセル</option>
                </select>
              </div>

            </ul>


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
                  <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Previous">
                      <span aria-hidden="true">&laquo;</span>
                      <span class="sr-only">Previous</span>
                    </a>
                  </li>
                  <li class="page-item active">
                    <a class="page-link" href="#">1</a>
                  </li>
                  <!-- <li class="page-item">
                    <a class="page-link" href="#">2</a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="#">3</a>
                  </li> -->
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
