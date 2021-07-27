@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>お気に入りカテゴリ編集</h1>
    <div class="section-header-breadcrumb">
      @if ( Auth::guard('user')->check() )
      <div class="breadcrumb-item"><a href="{{ url('/') }}">HOME</a></div>
      <div class="breadcrumb-item active">お気に入りカテゴリ編集</div>
      @endif
    </div>
  </div>
  <div class="section-body">
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/user/post/favoritecategory') }}">
            @csrf
              <div class="form-divider" style="margin-bottom:30px;">
                興味のあるカテゴリーにチェックをつけてください。
              </div>
              <div class="row">
                <div class="form-group col-12">
                @foreach($categories as $key=>$vals)
                  <div class="form-group">
                    <h6>{{$key}}</h6>
                    <ul class="list-unstyled row">
                        @foreach($vals as $val)
                            <li class="col-4 ">
                              <input class="checkbox-input" type="checkbox" id="{{ $val->category_id }}" name="favorite_category[]" value="{{ $val->category_id }}">
                              <label for="{{ $val->category_id }}" class="checkbox-label">{{ $val->category_name }}（{{ $val->items->count() }}点）</label>
                            </li>
                        @endforeach
                    </ul>
                  </div>
                @endforeach
                </div>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                  登録
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>






@endsection
