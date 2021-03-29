@extends('layouts.app')

@section('content')
<section class="section">
          <div class="section-header">
            <h1>商品一覧</h1>
          </div>
          <div class="section-body">
            <div class="row mt-4">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="float-left">
                      @if(isset($category_name))
                      <h2 class="section-title">{{$category_name}}</h2>
                      @else
                      <h2 class="section-title">全ての商品</h2>
                      @endif
                    </div>
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
                          <th class="text-center">商品番号</th>
                          <th class="text-center">商品名</th>
                          <!-- <th class="text-center">カテゴリー</th> -->
                          <th class="text-center">参考価格</th>
                          <th class="text-center">個数</th>
                          <th class="text-center">操作</th>
                        </tr>





                        @foreach($items as $item)
                        <tr>
                          <td class="text-center">{{$item->item_code}}</td>
                          <td class="text-center">{{$item->item_name}}</td>
                          <td class="text-center">
                            ¥ {{$item->teika}}
                          </td>
                          <td class="text-center"><input name="quantity" class="quantity form-control" value="1"></td>
                          <td class="text-center"><button id="{{$item->id}}" class="addcart btn btn-warning">カートに入れる</button></td>
                        </tr>
                        @endforeach
                      </table>
                    </div>


                    <!-- <form action="{{ url('/addcart')}}" method="POST" class="form-horizontal">
                      {{ csrf_field() }}
                      <input name="item_id" placeholder="1">
                      <input name="quantity" placeholder="1">
                      <button type="submit">
                       追加
                      </button>
                    </form> -->


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
