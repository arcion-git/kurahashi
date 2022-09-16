@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>お問い合わせ</h1>
    <div class="section-header-breadcrumb">
      @if ( Auth::guard('user')->check() )
      <div class="breadcrumb-item"><a href="{{ url('/') }}">HOME</a></div>
      <div class="breadcrumb-item active">お問い合わせ</div>
      @endif
    </div>
  </div>
  <div class="section-body">
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <form class="form-horizontal" role="form" method="POST" action="{{ url('/postcontact') }}">
          @csrf
            <div class="card-body mt-4">
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">お名前</label>
                <div class="col-sm-12 col-md-7">
                  <input type="text" name="name" value="" class="form-control">
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">ご住所</label>
                <div class="col-sm-12 col-md-7">
                  <input type="text" name="address" value="" class="form-control">
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">メールアドレス</label>
                <div class="col-sm-12 col-md-7">
                  <input type="text" name="email" value="" class="form-control">
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">電話番号</label>
                <div class="col-sm-12 col-md-7">
                  <input type="text" name="tel" value="" class="form-control">
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">お問い合わせ種別</label>
                <div class="col-sm-12 col-md-7">
                  <div class="selectric-wrapper selectric-form-control selectric-selectric selectric-below">
                    <select class="form-control selectric" tabindex="-1" name="shubetu" value="">
                    <option value="商品について">商品について</option>
                    <option value="商品の不備・ご意見">商品の不備・ご意見</option>
                    <option value="ご注文について">ご注文について</option>
                    <option value="その他">その他</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">お問い合わせ内容</label>
                <div class="col-sm-12 col-md-7">
                  <textarea style="height:250px;" name="naiyou" rows="10" value="" class="form-control selectric"></textarea>
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                <div class="col-sm-12 col-md-7">
                  <button type="submit" class="btn btn-primary">内容を送信する</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
    </div>
  </div>
</section>






@endsection
