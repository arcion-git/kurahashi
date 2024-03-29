@extends('layouts.auth')

@section('content')
<ul class="nav nav-tabs">
  <li class="nav-item col-6 text-center">
    <a class="nav-link font-weight-bold" href="{{ route('login', ['type' => request()->input('type')]) }}">{{ __('Login') }}</a>
  </li>
  @if (Route::has('register'))
      <li class="nav-item col-6 text-center">
        <?php if(!isset($_GET['type'])) { ?>
          <a class="nav-link font-weight-bold active" href="{{ route('register') }}">{{ __('Register') }}</a>
        <?php } else { ?>
          <a class="nav-link font-weight-bold active" href="{{ route('register_c', ['type' => request()->input('type')]) }}">{{ __('Register') }}</a>
        <?php } ?>
      </li>
  @endif
</ul>
<div class="card card-primary">
  <div class="card-header"><h4>お取引先情報</h4></div>
  <div class="card-body">
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/user/register') }}">
    @csrf
      <!-- <div class="form-divider">
        お名前
      </div> -->

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="company">法人・個人</label>
          <select id="hjkjKbn" name="hjkjKbn" class="hjkjKbn @if($errors->has('hjkjKbn')) is-invalid @endif form-control">
            <option value="">選択してください</option>
            <option value="1" @if(old('hjkjKbn') == 1) selected @endif selected>法人</option>
            <!-- <option value="2" @if(old('hjkjKbn') == 2) selected @endif>個人事業主</option> -->
          </select>
          @if($errors->has('hjkjKbn'))
          <span class="invalid-feedback" role="alert">
              <strong>法人・個人は必ず入力してください。</strong>
          </span>
          @endif
        </div>
      </div>
      <div id="maekabu">
        <div class="row">
          <div class="col-sm-12 col-md-6">
            <div class="form-group">
            <label for="company">法人格</label>
              <select name="houjinKaku" class="form-control" value="{{ old('houjinKaku') }}">
                  <!-- <option value="">選択してください</option> -->
                  <option value="1" @if(old('houjinKaku') == 1) selected @endif>株式会社</option>
                  <option value="2" @if(old('houjinKaku') == 2) selected @endif>有限会社</option>
                  <option value="3" @if(old('houjinKaku') == 3) selected @endif>合同会社</option>
                  <option value="4" @if(old('houjinKaku') == 4) selected @endif>合資会社</option>
                  <option value="5" @if(old('houjinKaku') == 5) selected @endif>合名会社</option>
                  <option value="6" @if(old('houjinKaku') == 6) selected @endif>医療法人</option>
                  <option value="7" @if(old('houjinKaku') == 7) selected @endif>医療法人社団</option>
                  <option value="8" @if(old('houjinKaku') == 8) selected @endif>医療法人財団</option>
                  <option value="9" @if(old('houjinKaku') == 9) selected @endif>社会医療法人</option>
                  <option value="10" @if(old('houjinKaku') == 10) selected @endif>一般社団法人</option>
                  <option value="11" @if(old('houjinKaku') == 11) selected @endif>公益社団法人</option>
                  <option value="12" @if(old('houjinKaku') == 12) selected @endif>一般財団法人</option>
                  <option value="13" @if(old('houjinKaku') == 13) selected @endif>公益財団法人</option>
                  <option value="14" @if(old('houjinKaku') == 14) selected @endif>協同組合</option>
                  <option value="15" @if(old('houjinKaku') == 15) selected @endif>社会福祉法人</option>
                  <option value="16" @if(old('houjinKaku') == 16) selected @endif>特定非営利活動法人</option>
                  <option value="17" @if(old('houjinKaku') == 17) selected @endif>学校法人</option>
                  <option value="18" @if(old('houjinKaku') == 18) selected @endif>国立大学法人</option>
                  <option value="19" @if(old('houjinKaku') == 19) selected @endif>公立大学法人</option>
                  <option value="20" @if(old('houjinKaku') == 20) selected @endif>宗教法人</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 col-md-6">
            <div class="form-group">
              <label for="zengo">法人格前後</label><br />
                <input class="radio-input" id="前" type="radio" value="0" name="houjinZengo" @if(old('houjinZengo') == 0) checked @endif> <label for="前">前</label>
                <input class="radio-input" id="後" type="radio" value="1" name="houjinZengo" @if(old('houjinZengo') == 1) checked @endif> <label for="後">後</label>
            </div>
          </div>
        </div>
      </div>


      <div class="form-divider">
        お名前
      </div>
      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="company">事業者名（法人格を除く）</label>
          <input id="company" type="text" class="form-control" name="company" placeholder="クラハシ" value="{{ old('company') }}" autofocus required>
          <span class="small">※<span class="red">全角</span>で入力してください。</span>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="company_kana">事業者名（フリガナ）</label>
          <input id="company_kana" type="text" class="form-control" name="company_kana" placeholder="クラハシ" value="{{ old('company_kana') }}" autofocus required>
          <span class="small">※<span class="red">全角</span>で入力してください。</span>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="last_name">代表者名（姓）</label>
          <input id="last_name" type="text" class="form-control" name="last_name" placeholder="山田" autofocus value="{{ old('last_name') }}" required>
        </div>
        <div class="form-group col-sm-12 col-md-6">
          <label for="last_name">代表者名（名）</label>
          <input id="last_name" type="text" class="form-control" name="first_name" placeholder="太郎" value="{{ old('first_name') }}" required>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="last_name">フリガナ（姓）</label>
          <input id="last_name" type="text" class="form-control" name="last_name_kana" placeholder="ヤマダ" value="{{ old('last_name_kana') }}" required>
          <span class="small">※<span class="red">全角カナ</span>で入力してください。</span>
        </div>
        <div class="form-group col-sm-12 col-md-6">
          <label for="last_name">フリガナ（名）</label>
          <input id="last_name" type="text" class="form-control" name="first_name_kana" placeholder="タロウ" value="{{ old('first_name_kana') }}" required>
          <span class="small">※<span class="red">全角カナ</span>で入力してください。</span>
        </div>
      </div>


      <div class="form-divider">
        ご住所<span class="small">
      </div>
      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="address01">郵便番号</label>
          <input type="text" name="address01" maxlength="7" onKeyUp="AjaxZip3.zip2addr(this,'','address02','address03');" class="form-control" id="address01" placeholder="1001000" value="{{ old('address01') }}" required>
          <span class="small">※<span class="red">ハイフン無し半角7桁</span>で入力してください。</span>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="address02">都道府県</label>
          <input type="text" name="address02" id="address02" class="form-control" placeholder="広島県" value="{{ old('address02') }}" required>
        </div>
        <div class="form-group col-sm-12 col-md-6">
          <label for="address03">市区町村</label>
          <input type="text" name="address03" id="address03" class="form-control" placeholder="福山市〇〇町" value="{{ old('address03') }}" required>
        </div>
        <div class="form-group col-sm-12 col-md-6">
          <label for="address04">番地（ビル・マンション名）</label>
          <input type="text" name="address04" id="address04" class="form-control" placeholder="３ー２ー２" value="{{ old('address04') }}" required>
          <span class="small">※<span class="red">全角</span>で入力してください。</span>
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
          <label for="tel">電話番号(代表)</label>
          <input id="tel" type="tel" class="form-control" name="tel" value="{{ old('tel') }}" required>
          <span class="small">※<span class="red">半角ハイフンあり</span>で入力してください。</span>
          <div class="invalid-feedback">
          </div>
        </div>
        <!-- <div class="form-group col-sm-12 col-md-6">
          <label for="tel">FAX番号</label>
          <input id="fax" type="fax" class="form-control" name="fax" value="084-952-5527">
          <div class="invalid-feedback">
          </div>
        </div> -->
      </div>


      <div class="form-divider">
        掛け払い代金の支払い方法
      </div>
      <div class="row">
        <div class="form-group col-12 @if($errors->has('pay'))is-invalid @endif">
          <input class="@if($errors->has('pay'))is-invalid @endif radio-input" type="radio" id="銀行振込" value="2" name="pay" @if(old('pay') == 2) checked @elseif(old('pay') == null) checked @endif><label for="銀行振込"> 銀行振込</label>
          <input class="@if($errors->has('pay'))is-invalid @endif radio-input" type="radio" id="口座振替" value="8" name="pay" @if(old('pay') == 8) checked @endif><label for="口座振替"> 口座振替</label>
          <input class="@if($errors->has('pay'))is-invalid @endif radio-input" type="radio" id="コンビニ払い" value="9" name="pay" @if(old('pay') == 9) checked @endif><label for="コンビニ払い"> コンビニ払い</label>
          @if($errors->has('pay'))
          <span class="invalid-feedback" role="alert">
              <strong>支払い方法は必ず入力してください。</strong>
          </span>
          @endif
          <label>※掛け払い代金のお支払い方法を上記よりお選びください。<br />（掛け払いの代金は、月末締め翌々月5日払いでのご請求となります。）<br />※クレジットカード払いのみ（掛け払いを使わない）場合は、「銀行振込」を選択してください。</label>
        </div>
      </div>

      <div class="form-divider">
        アカウント情報
      </div>
      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="email">メールアドレス</label>
          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" required autocomplete="email" value="{{ old('email') }}">
          <span class="small">※メールアドレス間違いの場合、再登録が必要となります。</span>
          @error('email')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="password" class="d-block">パスワード</label>
          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" value="{{ old('password') }}">
          @error('password')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
      </div>
      <div class="row">
        <div class="form-group col-sm-12 col-md-6">
          <label for="password-confirm" class="d-block">パスワード（再入力）</label>
          <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" value="{{ old('password') }}">
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
