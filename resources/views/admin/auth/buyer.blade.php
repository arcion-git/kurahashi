@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>得意先一覧</h1>
    <div class="section-header-breadcrumb">
    </div>
  </div>
  <div class="section-body">
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-body">

            <!-- <div class="float-right">
              <form>
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="検索">
                  <div class="input-group-append">
                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </form>
            </div> -->

            <div class="clearfix mb-3"></div>

            <div class="table-responsive">
              <table class="table table-striped">
                <tr>
                  <th class="text-center">得意先ID</th>
                  <th class="text-center">会社名</th>
                  <!-- <th class="text-center">会社名</th>
                  <th class="text-center">住所</th>
                  <th class="text-center">電話番号</th>
                  <th class="text-center">メールアドレス</th> -->
                  <th class="text-center">操作</th>
                </tr>

                @foreach($stores as $store)
                <tr>
                  <td class="text-center">
                    {{$store->store()->tokuisaki_id}}
                  </td>
                  <td class="text-center">
                    {{$store->store()->tokuisaki_name}}
                  </td>
                  <td class="text-center">
                    <a href="{{ url('/admin/buyer/recommend/'.$store->tokuisaki_id) }}"><button class="btn btn-success">おすすめ商品登録</button></a>
                  </td>
                </tr>
                @endforeach

              </table>
            </div>
            <div class="float-right">
              <nav>
                <ul class="pagination">
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
