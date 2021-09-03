@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>顧客一覧</h1>
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
                  <th class="text-center">お名前</th>
                  <!-- <th class="text-center">会社名</th>
                  <th class="text-center">住所</th> -->
                  <th class="text-center">電話番号</th>
                  <th class="text-center">メールアドレス</th>
                  <th class="text-center">操作</th>
                </tr>

                @foreach($users as $user)
                <tr>
                  <td class="text-center">
                    {{$user->name}}
                  </td>
                  <td class="text-center">
                    {{$user->tel}}
                  </td>
                  <td class="text-center">
                    {{$user->email}}
                  </td>
                  <td class="text-center">
                    <a href="{{ url('/admin/user/deal/'.$user->id) }}"><button class="btn btn-primary">取引一覧</button></a>
                    <a href="{{ url('/admin/user/recommend/'.$user->id) }}"><button class="btn btn-success">おすすめ商品登録</button></a>
                    <a href="{{ url('/admin/user/repeatorder/'.$user->id) }}"><button class="btn btn-warning">リピートオーダー登録</button></a>
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
