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
  <div class="card-header"><h4>お客様情報</h4></div>
  <div class="card-body">
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/user/register') }}">
    @csrf

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="form-divider">
        お名前
      </div>

      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="first_name">氏名（姓）</label>
          <!-- <input id="last_name" type="text" class="form-control" name="last_name" placeholder="山田" autofocus value="{{ old('last_name') }}" required> -->
          <input id="first_name" type="text" class="form-control" name="last_name" placeholder="山田" autofocus value="濱本" required>
          @error('first_name')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group col-sm-12 col-md-6">
          <label for="last_name">氏名（名）</label>
          <!-- <input id="last_name" type="text" class="form-control" name="first_name" placeholder="太郎" value="{{ old('first_name') }}" required> -->
          <input id="first_name" type="text" class="form-control" name="first_name" placeholder="太郎" value="悠世" required>
          @error('first_name')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="last_name">フリガナ（姓）</label>
          <!-- <input id="last_name" type="text" class="form-control" name="last_name_kana" placeholder="ヤマダ" value="{{ old('last_name_kana') }}" required> -->
          <input id="last_name_kana" type="text" class="form-control" name="last_name_kana" placeholder="ヤマダ" value="ハマモト" required>
          <span class="small">※<span class="red">全角カナ</span>で入力してください。</span>
          @error('last_name_kana')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group col-sm-12 col-md-6">
          <label for="last_name">フリガナ（名）</label>
          <!-- <input id="last_name" type="text" class="form-control" name="first_name_kana" placeholder="タロウ" value="{{ old('first_name_kana') }}" required> -->
          <input id="first_name_kana" type="text" class="form-control" name="first_name_kana" placeholder="タロウ" value="ユウセイ" required>
          <span class="small">※<span class="red">全角カナ</span>で入力してください。</span>
          @error('first_name_kana')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
      </div>


      <div class="form-divider">
        ご住所<span class="small">
      </div>
      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="address01">郵便番号</label>
          <!-- <input type="text" name="address01" maxlength="7" onKeyUp="AjaxZip3.zip2addr(this,'','address02','address03');" class="form-control" id="address01" placeholder="1001000" value="{{ old('address01') }}" required> -->
          <input type="text" name="address01" maxlength="7" onKeyUp="AjaxZip3.zip2addr(this,'','address02','address03');" class="form-control" id="address01" placeholder="1001000" value="7200824" required>
          <span class="small">※<span class="red">ハイフン無し半角7桁</span>で入力してください。</span>
          @error('address01')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
      </div>
      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="address02">都道府県</label>
          <!-- <input type="text" name="address02" id="address02" class="form-control" placeholder="広島県" value="{{ old('address02') }}" required> -->
          <input type="text" name="address02" id="address02" class="form-control" placeholder="広島県" value="広島県" required>
          @error('address02')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group col-sm-12 col-md-6">
          <label for="address03">市区町村</label>
          <!-- <input type="text" name="address03" id="address03" class="form-control" placeholder="福山市〇〇町" value="{{ old('address03') }}" required> -->
          <input type="text" name="address03" id="address03" class="form-control" placeholder="福山市〇〇町" value="福山市多治米町" required>
          @error('address03')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group col-sm-12 col-md-6">
          <label for="address04">番地（ビル・マンション名）</label>
          <!-- <input type="text" name="address04" id="address04" class="form-control" placeholder="３ー２ー２" value="{{ old('address04') }}" required> -->
          <input type="text" name="address04" id="address04" class="form-control" placeholder="３ー２ー２" value="２ー１２ー１７" required>
          <span class="small">※<span class="red">全角</span>で入力してください。</span>
          @error('address04')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <!-- <div class="form-group col-sm-12 col-md-6">
          <label for="address05">ビル・マンション名</label>
          <input type="text" name="address05" id="address05" class="form-control" placeholder="◯◯ビル４階" value="{{ old('address05') }}">
          <span class="small">※<span class="red">全角</span>で入力してください。</span>
        </div> -->
      </div>
      <div class="form-divider">
        ご連絡先
      </div>
      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="tel">電話番号</label>
          <!-- <input id="tel" type="tel" class="form-control" name="tel" value="{{ old('tel') }}" required> -->
          <input id="tel" type="tel" class="form-control" name="tel" value="080-2888-5281" required>
          <span class="small">※<span class="red">半角ハイフンあり</span>で入力してください。</span>
          @error('tel')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
      </div>


      <?php if(($shipping_info->keiyaku_company_hyouji == 1)){ ?>
        <div class="form-divider">
          ご契約会社名
        </div>
        <div class="row">
          <div class="form-group col-sm-12 col-md-6">
            <!-- <label for="shipping_type">ご契約会社コード</label> -->
            <input id="type" type="hidden" class="form-control" name="type" value="<?php echo isset($_GET['type']) ? htmlspecialchars($_GET['type']) : ''; ?>" required>
            <label for="company_name">ご契約会社名</label>
            <select name="company_name" class="form-control" value="{{ old('company_name') }}">
                <option value="">選択してください</option>
                @foreach($company_names as $company_name)
                <option value="{{$company_name->company_name}}">{{$company_name->company_name}}</option>
                @endforeach
            </select>
            <span class="small">※配送契約をされている企業のお客様は、会社名を選択してください。</span>
            @error('company_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
        </div>
      <?php } ?>

      <div class="form-divider">
        アカウント情報
      </div>
      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="email">メールアドレス</label>
          <!-- <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" required autocomplete="email" value="{{ old('email') }}"> -->
          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" required autocomplete="email" value="sk8.panda.27@gmail.com">
          <span class="small">※メールアドレス間違いの場合、再登録が必要となります。</span>
          <!-- @error('email')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror -->
        </div>
      </div>

      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="password" class="d-block">パスワード</label>
          <!-- <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" value="{{ old('password') }}"> -->
          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" value="pass1234">
          <!-- @error('password')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror -->
        </div>
      </div>
      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="password-confirm" class="d-block">パスワード（再入力）</label>
          <!-- <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" value="{{ old('password') }}"> -->
          <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" value="pass1234">
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
