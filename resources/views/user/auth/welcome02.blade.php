@extends('layouts.auth')

@section('content')
<ul class="nav nav-tabs">
  <li class="nav-item col-6 text-center">
    <a class="nav-link font-weight-bold active" href="{{ route('login') }}">{{ __('Login') }}</a>
  </li>
  @if (Route::has('register'))
      <li class="nav-item col-6 text-center">
          <a class="nav-link font-weight-bold" href="{{ route('register') }}">{{ __('Register') }}</a>
      </li>
  @endif
</ul>
<div class="card card-primary">

  <div class="row a4_img">
    <div class="col-md-6">
      <a href="{{ asset('img/a401.jpg') }}"><img alt="image" src="{{ asset('img/a401.jpg') }}" class=""></a>
    </div>
    <div class="col-md-6">
      <a href="{{ asset('img/a402.jpg') }}"><img alt="image" src="{{ asset('img/a402.jpg') }}" class=""></a>
    </div>
  </div>

  <div class="row mx-auto justify-content-center welcome_btn">
    <div class="col-6 mx-auto justify-content-center">
      <a href="{{ route('login') }}">
        <button type="submit" class="btn btn-primary">
            ログインはこちら
        </button>
      </a>
    </div>
    <div class="col-6 mx-auto justify-content-center">
      <a href="{{ route('register') }}">
        <button type="submit" class="btn btn-primary">
          会員登録はこちら
        </button>
      </a>
    </div>
  </div>
  <div class="row justify-content-center mx-auto">
    <div class="col-md-12">
    <p>※既に株式会社クラハシとお取引のあるお客様は、担当営業にご連絡ください。<br />お客様情報を確認し、ユーザー登録をさせていただきます。</p>
    </div>

  </div>

</div>
@endsection
