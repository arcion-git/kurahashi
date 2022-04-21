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
          <label for="company">会社名</label>
          <input id="company" type="text" class="form-control" name="company" placeholder="株式会社クラハシ" value="株式会社クラハシ" autofocus>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-6">
          <label for="last_name">お名前（姓）</label>
          <input id="last_name" type="text" class="form-control" name="last_name" placeholder="山田" autofocus value="山田">
        </div>
        <div class="form-group col-6">
          <label for="last_name">お名前（名）</label>
          <input id="last_name" type="text" class="form-control" name="first_name" placeholder="太郎" value="太郎">
        </div>
      </div>

      <div class="row">
        <div class="form-group col-6">
          <label for="last_name">フリガナ（姓）</label>
          <input id="last_name" type="text" class="form-control" name="last_name_kana" placeholder="ヤマダ" value="ヤマダ">
        </div>
        <div class="form-group col-6">
          <label for="last_name">フリガナ（名）</label>
          <input id="last_name" type="text" class="form-control" name="first_name_kana" placeholder="タロウ" value="タロウ">
        </div>
      </div>


      <div class="form-divider">
        ご住所
      </div>
      <div class="row">
        <div class="form-group col-6">
          <label for="address01">郵便番号(ハイフン無し7桁)</label>
          <input type="text" name="address01" maxlength="8" onKeyUp="AjaxZip3.zip2addr(this,'','address02','address03');" class="form-control" id="address01" placeholder="1001000" value="7200824">
        </div>
      </div>
      <div class="row">
        <div class="form-group col-6">
          <label for="address02">都道府県</label>
          <input type="text" name="address02" id="address02" class="form-control" placeholder="広島県" value="広島県">
        </div>
        <div class="form-group col-6">
          <label for="address03">市区町村</label>
          <input type="text" name="address03" id="address03" class="form-control" placeholder="福山市〇〇町" value="福山市多治米町">
        </div>
        <div class="form-group col-6">
          <label for="address04">番地</label>
          <input type="text" name="address04" id="address04" class="form-control" placeholder="3-24-555" value="2-12-17">
        </div>
        <div class="form-group col-6">
          <label for="address05">ビル・マンション名</label>
          <input type="text" name="address05" id="address05" class="form-control" placeholder="◯◯ビル4F" value="プレセランス・オリビエ 202">
        </div>
      </div>


      <div class="form-divider">
        ご連絡先
      </div>
      <div class="row">
        <div class="form-group col-6">
          <label for="tel">電話番号</label>
          <input id="tel" type="tel" class="form-control" name="tel" value="080-2888-5281">
          <div class="invalid-feedback">
          </div>
        </div>
        <div class="form-group col-6">
          <label for="tel">FAX番号</label>
          <input id="fax" type="fax" class="form-control" name="fax" value="084-952-5527">
          <div class="invalid-feedback">
          </div>
        </div>
      </div>

      <div class="form-divider">
        運営会社有無
      </div>
      <div class="row">
        <div class="form-group col-12">
          <input class="checkbox-input" type="checkbox" id="unei_company" name="unei_company">
          <label for="unei_company">支店(営業所、店舗)の場合チェックを入れてください※本社登録の場合はチェックの必要はありません</label>
          <div class="invalid-feedback">
          </div>
        </div>
      </div>

      <div class="form-divider">
        掛け払い代金の支払い方法
      </div>
      <div class="row">
        <div class="form-group col-12">
          <input class="radio-input" type="radio" id="銀行振込" name="pay"><label for="銀行振込">銀行振込</label>
          <input class="radio-input" type="radio" id="口座振替" name="pay"><label for="口座振替">口座振替</label>
          <input class="radio-input" type="radio" id="コンビニ払い" name="pay"><label for="コンビニ払い">コンビニ払い</label>
          <label>※掛け払い代金のお支払い方法を上記よりお選びください。<br />（掛け払いの代金は、月末締め翌々月5日払いでのご請求となります。）<br />※クレジットカード払いのみ（掛け払いを使わない）場合は、「銀行振込」を選択してください。</label>
          <div class="invalid-feedback">
          </div>
        </div>
      </div>

      <div class="form-divider">
        アカウント情報
      </div>
      <div class="row">
      <div class="form-group col-6">
        <label for="email">メールアドレス</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" value="kitamura.yusei@gmail.com">
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      </div>
      </div>

      <div class="row">
        <div class="form-group col-6">
          <label for="password" class="d-block">パスワード</label>
          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" value="secret1234">
          @error('password')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
      </div>
      <div class="row">
        <div class="form-group col-6">
          <label for="password-confirm" class="d-block">パスワード（再入力）</label>
          <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" value="secret1234">
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
