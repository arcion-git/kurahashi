@extends('layouts.app')

@section('content')


        <section class="section">
          <div class="section-header">
            <h1>
              @if(isset($category_name))
              {{$category_name}}
              @else
              商品一覧
              @endif
            </h1>
          </div>


          <div class="section-body">

@if(isset($category_name))
@else
          <div class="row mt-4">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="float-left">
                    @if(isset($category_name))
                    <h2 class="section-title">{{$category_name}}</h2>
                    @else
                    <h2 class="section-title">担当のおすすめ商品</h2>
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





                      <tr>
                        <td class="text-center">01</td>
                        <td class="text-center">おすすめ商品サンプル</td>
                        <td class="text-center">
                          ¥ 5,000
                        </td>
                        <td class="text-center"><input name="quantity" class="quantity form-control" value="1"></td>
                        <td class="text-center"><button id="1" class="addcart btn btn-warning">カートに入れる</button></td>
                      </tr>

                      <tr>
                        <td class="text-center">02</td>
                        <td class="text-center">おすすめ商品サンプル</td>
                        <td class="text-center">
                          ¥ 4,000
                        </td>
                        <td class="text-center"><input name="quantity" class="quantity form-control" value="1"></td>
                        <td class="text-center"><button id="1" class="addcart btn btn-warning">カートに入れる</button></td>
                      </tr>

                      <tr>
                        <td class="text-center">03</td>
                        <td class="text-center">おすすめ商品サンプル</td>
                        <td class="text-center">
                          ¥ 6,000
                        </td>
                        <td class="text-center"><input name="quantity" class="quantity form-control" value="1"></td>
                        <td class="text-center"><button id="1" class="addcart btn btn-warning">カートに入れる</button></td>
                      </tr>

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
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="float-left">
                    @if(isset($category_name))
                    <h2 class="section-title">{{$category_name}}</h2>
                    @else
                    <h2 class="section-title">時価商品</h2>
                    @endif
                  </div>
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
                        <th class="text-center">商品番号</th>
                        <th class="text-center">商品名</th>
                        <!-- <th class="text-center">カテゴリー</th> -->
                        <th class="text-center">参考価格</th>
                        <th class="text-center">個数</th>
                        <th class="text-center">操作</th>
                      </tr>





                      <tr>
                        <td class="text-center">01</td>
                        <td class="text-center">時価商品サンプル</td>
                        <td class="text-center">
                          ¥ 5,000
                        </td>
                        <td class="text-center"><input name="quantity" class="quantity form-control" value="1"></td>
                        <td class="text-center"><button id="1" class="addcart btn btn-warning">カートに入れる</button></td>
                      </tr>

                      <tr>
                        <td class="text-center">02</td>
                        <td class="text-center">時価商品サンプル</td>
                        <td class="text-center">
                          ¥ 4,000
                        </td>
                        <td class="text-center"><input name="quantity" class="quantity form-control" value="1"></td>
                        <td class="text-center"><button id="1" class="addcart btn btn-warning">カートに入れる</button></td>
                      </tr>

                      <tr>
                        <td class="text-center">03</td>
                        <td class="text-center">時価商品サンプル</td>
                        <td class="text-center">
                          ¥ 6,000
                        </td>
                        <td class="text-center"><input name="quantity" class="quantity form-control" value="1"></td>
                        <td class="text-center"><button id="1" class="addcart btn btn-warning">カートに入れる</button></td>
                      </tr>

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
          <div class="row">
                      <div class="col-12">
                        <div class="card">
                          <div class="card-body">
                            <div class="float-left">
                              @if(isset($category_name))
                              <h2 class="section-title">{{$category_name}}</h2>
                              @else
                              <h2 class="section-title">前回注文した内容</h2>
                              @endif
                            </div>
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
                                  <th class="text-center">商品番号</th>
                                  <th class="text-center">商品名</th>
                                  <!-- <th class="text-center">カテゴリー</th> -->
                                  <th class="text-center">参考価格</th>
                                  <th class="text-center">個数</th>
                                  <th class="text-center">操作</th>
                                </tr>





                                <tr>
                                  <td class="text-center">01</td>
                                  <td class="text-center">前回注文商品サンプル</td>
                                  <td class="text-center">
                                    ¥ 5,000
                                  </td>
                                  <td class="text-center"><input name="quantity" class="quantity form-control" value="1"></td>
                                  <td class="text-center"><button id="1" class="addcart btn btn-warning">カートに入れる</button></td>
                                </tr>

                                <tr>
                                  <td class="text-center">02</td>
                                  <td class="text-center">前回注文商品サンプル</td>
                                  <td class="text-center">
                                    ¥ 4,000
                                  </td>
                                  <td class="text-center"><input name="quantity" class="quantity form-control" value="1"></td>
                                  <td class="text-center"><button id="1" class="addcart btn btn-warning">カートに入れる</button></td>
                                </tr>

                                <tr>
                                  <td class="text-center">03</td>
                                  <td class="text-center">前回注文商品サンプル</td>
                                  <td class="text-center">
                                    ¥ 6,000
                                  </td>
                                  <td class="text-center"><input name="quantity" class="quantity form-control" value="1"></td>
                                  <td class="text-center"><button id="1" class="addcart btn btn-warning">カートに入れる</button></td>
                                </tr>

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
@endif


            <div class="row">
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
                          <th class="text-center">商品番号</th>
                          <th class="">商品名</th>
                          <th class="text-center">カテゴリー</th>
                          <th class="text-center">タグ</th>
                          <th class="text-center">単位</th>
                          <!-- <th class="text-center">納品予定日</th>
                          <th class="text-center">参考価格</th>
                          <th class="text-center">個数</th> -->
                          <th class="text-center">操作</th>
                        </tr>





                        @foreach($items as $item)
                        <tr>
                          <td class="text-center">{{$item->item_code}}</td>
                          <td class="">{{$item->item_name}}</td>
                          <td class="">


                          </td>
                          <td class="">
                          </td>
                          <td class="text-center">
                            @if ($item->tani == 1)
                            ｹｰｽ
                            @elseif ($item->tani == 2)
                            ﾎﾞｰﾙ
                            @elseif ($item->tani == 3)
                            ﾊﾞﾗ
                            @elseif ($item->tani == 4)
                            Kg
                            @endif
                          </td>
                          <!-- <td class="text-center">{{$item->nouhin_yoteibi_start}}</td>
                          <td class="text-center">
                            ¥ {{$item->teika}}
                          </td>
                          <td class="text-center"><input name="quantity" class="quantity form-control" value="1"></td> -->
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
