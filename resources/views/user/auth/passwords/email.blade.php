@extends('layouts.auth')

<!-- Main Content -->
@section('content')
<div class="card card-primary">
  <div class="card-header"><h4>{{ __('Reset Password') }}</h4></div>
  <div class="card-body">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/user/password/email') }}">
        {{ csrf_field() }}

          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} row ">
              <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

              <div class="col-md-6">
                  <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                  @if ($errors->has('email'))
                      <span class="help-block">
                          <strong>{{ $errors->first('email') }}</strong>
                      </span>
                  @endif
              </div>
          </div>

          <div class="form-group row mb-0">
              <div class="col-md-8 offset-md-4">
                  <button type="submit" class="btn btn-primary">
                      {{ __('Send Password Reset Link') }}
                  </button>
              </div>
          </div>
      </form>
  </div>
</div>




@endsection
