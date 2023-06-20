@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>商品一覧</h1>
    <div class="section-header-breadcrumb">
      @if ( Auth::guard('user')->check() )
      <div class="breadcrumb-item"><a href="{{ url('/') }}">HOME</a></div>
      @endif
    </div>
  </div>
  <div id="bulk" class="section-body">
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="addsetonagi_btn">
              <!-- <form action="{{ url('/addall') }}" method="POST" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" name="addtype" value="addsetonagi" />
                <button id="" type="submit" class="btn btn-warning">限定お買い得商品</button>
              </form> -->

                <!-- <input type="hidden" name="addtype" value="addsetonagi" />
                <button id="" type="submit" class="btn btn-warning">限定お買い得商品</button> -->
                <a href="{{ url('/setonagi') }}" class="btn btn-warning addsetonagi_link"><span>限定お買い得商品</span></a>

            </div>
            <div class="">
              <form action="{{ url('/addall') }}" method="POST" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" name="addtype" value="addbuyerrecommend" />
                <button id="" type="submit" class="btn btn-warning">担当おすすめの商品</button>
              </form>
            </div>
            <div class="">
              <form action="{{ url('/addall') }}" method="POST" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" name="addtype" value="addspecialprice" />
                <button id="" type="submit" class="btn btn-warning">市況商品（時価）</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>






@endsection
