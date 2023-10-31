@extends('layouts.auth')

@section('content')
<ul class="nav nav-tabs">
  <li class="nav-item col-6 text-center">
    <!-- <a class="nav-link font-weight-bold active" href="{{ route('login', ['type' => request()->input('type')]) }}">{{ __('Login') }}</a> -->
  </li>
  @if (Route::has('register'))
      <li class="nav-item col-6 text-center">
        <?php if(!isset($_GET['type'])) { ?>
          <!-- <a class="nav-link font-weight-bold" href="{{ route('register') }}">{{ __('Register') }}</a> -->
        <?php } else { ?>
          <!-- <a class="nav-link font-weight-bold" href="{{ route('register_c', ['type' => request()->input('type')]) }}">{{ __('Register') }}</a> -->
        <?php } ?>
      </li>
  @endif
</ul>
<div class="card card-primary">
  <div class="card-header"><h4>ログイン</h4></div>
  <div class="card-body">
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/user/login') }}">
          @csrf

          <div class="form-group row">
              <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

              <div class="col-md-6">
                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </div>
          </div>

          <div class="form-group row">
              <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

              <div class="col-md-6">
                  <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                  @error('password')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </div>
          </div>

          <div class="form-group row">
              <div class="col-md-6 offset-md-4">
                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                      <label class="form-check-label" for="remember">
                          {{ __('Remember Me') }}
                      </label>
                  </div>
              </div>
          </div>

          <div class="form-group row mb-0">
              <div class="col-md-8 offset-md-4">
                  <button type="submit" class="btn btn-primary">
                      {{ __('Login') }}
                  </button>

                  @if (Route::has('password.request'))
                      <a class="btn btn-link" href="{{ url('/user/password/reset') }}">
                          {{ __('Forgot Your Password?') }}
                      </a>
                  @endif
              </div>
          </div>
      </form>
  </div>
</div>
@endsection
