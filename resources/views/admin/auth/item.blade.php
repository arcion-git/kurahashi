@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>商品一覧</h1>
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
                  <th class="text-center">商品コード</th>
                  <th class="text-center">商品名</th>
                  <th class="text-center">発注先担当者名</th>
                  <!-- <th class="text-center">タグ</th>
                  <th class="text-center">定価</th>
                  <th class="text-center">単価</th> -->
                  <th class="text-center">残り在庫数</th>
                  <!-- <th class="text-center">掲載期限</th> -->
                  <!-- <th class="text-center">操作</th> -->
                </tr>

                <!-- <tr>
                  <td class="text-center">
                    0
                  </td>
                  <td class="text-center">
                    サンプル商品名1
                  </td>
                  <td class="text-center">

                  </td>
                  <td class="text-center">

                  </td>
                  <td class="text-center">

                  </td>
                  <td class="text-center">

                  </td>
                  <td class="text-center">
                  </td>
                  <td class="text-center">
                  </td>
                  <td class="text-center">
                    <a href=""><button class="btn btn-primary">価格を見る</button></a>
                  </td>
                </tr>
                <tr>
                  <td class="text-center">

                  </td>
                  <td class="text-center">

                  </td>
                  <td class="text-center">

                  </td>
                  <td class="text-center">
                    価格帯S
                  </td>
                  <td class="text-center">
                    3000
                  </td>
                  <td class="text-center">
                    3000
                  </td>
                  <td class="text-center">
                  </td>
                  <td class="text-center">
                  </td>
                  <td class="text-center">
                  </td>
                </tr>
                <tr>
                  <td class="text-center">

                  </td>
                  <td class="text-center">

                  </td>
                  <td class="text-center">

                  </td>
                  <td class="text-center">
                    価格帯A
                  </td>
                  <td class="text-center">
                    5000
                  </td>
                  <td class="text-center">
                    5000
                  </td>
                  <td class="text-center">
                  </td>
                  <td class="text-center">
                  </td>
                  <td class="text-center">
                  </td>
                </tr>
                <tr>
                  <td class="text-center">

                  </td>
                  <td class="text-center">

                  </td>
                  <td class="text-center">

                  </td>
                  <td class="text-center">
                    価格帯B
                  </td>
                  <td class="text-center">
                    7000
                  </td>
                  <td class="text-center">
                    7000
                  </td>
                  <td class="text-center">
                  </td>
                  <td class="text-center">
                  </td>
                  <td class="text-center">
                  </td>
                </tr> -->

                @foreach($items as $item)
                <tr>
                  <td class="text-center">
                    {{$item->item_id}}
                  </td>
                  <td class="text-center">
                    {{$item->item_name}}
                  </td>
                  <td class="text-center">
                    {{$item->tantou_name}}
                  </td>
                  <!-- <td class="text-center">

                  </td> -->
                  <!-- <td class="text-center">

                  </td> -->
                  <!-- <td class="text-center">

                  </td> -->
                  <td class="text-center">
                    {{$item->zaikosuu}}
                  </td>
                  <!-- <td class="text-center">
                  </td> -->
                  <!-- <td class="text-center">
                    <a href=""><button class="btn btn-primary">価格を見る</button></a>
                  </td> -->
                </tr>
                @endforeach

              </table>
            </div>
            <div class="float-right">
              <nav>
                <ul class="pagination">
                  {{ $items->links() }}
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
