@extends('layouts.auth')

@section('content')




<div class="card card-primary">
  <div class="card-header">
    <h4>初回アンケート</h4>
  </div>
  <div class="card-body">
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/user/register') }}">
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
                    <li class="col-4">
                      <input type="checkbox" id="{{ $val->category_id }}" name="{{ $val->category_id }}">
                      <label for="{{ $val->category_id }}" class="checkbox02">{{ $val->category_name }}（236点）</label>
                    </li>
                @endforeach
            </ul>
          </div>
        @endforeach
        </div>
      </div>


      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-lg btn-block">
            {{ __('Register') }}
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
