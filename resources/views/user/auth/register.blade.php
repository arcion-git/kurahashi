@extends('layouts.auth')

@section('content')
<ul class="nav nav-tabs">
  <li class="nav-item col-6 text-center">
    <a class="nav-link font-weight-bold" href="{{ route('login') }}">{{ __('Login') }}</a>
  </li>
  @if (Route::has('register'))
      <li class="nav-item col-6 text-center">
          <a class="nav-link font-weight-bold active" href="{{ route('register') }}">{{ __('Register') }}</a>
      </li>
  @endif
</ul>
<div class="card card-primary">
  <div class="card-header"><h4>新規会員登録</h4></div>
  <div class="card-body">
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/user/register') }}">
    @csrf
      <div class="form-divider">
        お名前
      </div>
      <div class="row">
        <div class="form-group col-6">
          @include('template/form_col', ['name' => 'last_name' , 'label' => '姓' ])
        </div>
        <div class="form-group col-6">
          @include('template/form_col', ['name' => 'first_name' , 'label' => '名' ])
        </div>
      </div>

      <div class="row">
        <div class="form-group col-6">
          @include('template/form_col', ['name' => 'last_name_kana' , 'label' => '姓（ふりがな）' ])
        </div>
        <div class="form-group col-6">
          @include('template/form_col', ['name' => 'first_name_kana' , 'label' => '名（ふりがな）' ])
        </div>
      </div>

      <div class="row">
        <div class="form-group col-6">
          @include('template/form_col', ['name' => 'company' , 'label' => '会社名' ])
        </div>
        <div class="form-group col-6">
          @include('template/form_col', ['name' => 'company_kana' , 'label' => '会社名（ふりがな）' ])
        </div>
      </div>


      <div class="form-divider">
        ご住所
      </div>
      <div class="row">
        <div class="form-group col-6">
          <label for="address01">郵便番号(7桁)</label>
          <input type="text" name="address01" maxlength="8" onKeyUp="AjaxZip3.zip2addr(this,'','address02','address03');" class="form-control" id="address01" placeholder="">
        </div>
        <div class="form-group col-6">
          <label for="address02">都道府県</label>
          <input type="text" name="address02" id="address02" class="form-control" placeholder="">
        </div>
        <div class="form-group col-6">
          <label for="address03">市区町村</label>
          <input type="text" name="address03" id="address03" class="form-control" placeholder="">
        </div>
        <div class="form-group col-6">
          @include('template/form_col', ['name' => 'address04' , 'label' => '番地' ])
        </div>
        <div class="form-group col-6">
          @include('template/form_col', ['name' => 'address05' , 'label' => 'マンション名（部屋番号）' ])
        </div>
      </div>


      <div class="form-divider">
        ご連絡先
      </div>
      <div class="form-group">
        <label for="tel">電話番号</label>
        <input id="tel" type="tel" class="form-control" name="tel">
        <div class="invalid-feedback">
        </div>
      </div>

      <div class="form-group">
        <label for="email">メールアドレス</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      </div>

      <div class="row">
        <div class="form-group col-6">
          <label for="password" class="d-block">パスワード</label>
          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
          @error('password')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group col-6">
          <label for="password-confirm" class="d-block">パスワード（再入力）</label>
          <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
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
