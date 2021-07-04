@extends('layouts.app')

@section('content')




  <section class="section">
    <div class="section-header">
      <h1>お問い合わせ内容確認</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="/">HOME</a></div>
        <div class="breadcrumb-item">お問い合わせ内容確認</div>
      </div>
    </div>


    <!-- <div class="input-group">
      <input type="text" name="nouhinbi[]" class="nouhinbi text-center form-control datepicker" value="2021-06-26">
    </div> -->



    <div class="section-body">
      <div class="invoice">
        <div class="invoice-print">

          <form action="{{ url('/adddeal') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            <div class="row mt-4 order">
              <div class="col-md-12">
                <div class="section-title">オーダー内容</div>
                <div id="order"></div>
              </div>
            </div>





            <div class="row mt-4 order">

            </div>

            <div class="row mt-4">
              <div class="col-lg-8">
                <div class="section-title">お支払い</div>
              </div>
              <div class="col-lg-4 text-right">
                <div class="invoice-detail-item">
                  <div class="invoice-detail-name">商品合計</div>
                  <div id="item_total" class="invoice-detail-value"></div>
                </div>
                <div class="invoice-detail-item">
                  <div class="invoice-detail-name">消費税</div>
                  <div id="tax" class="invoice-detail-value"></div>
                </div>
                <hr class="mt-2 mb-2">
                <div class="invoice-detail-item">
                  <div class="invoice-detail-name">合計</div>
                  <div id="all_total" class="invoice-detail-value invoice-detail-value-lg"></div>
                </div>
              </div>
            </div>

            <div class="float-right">
                <button type="submit" class="btn btn-warning">この内容で問い合わせる</button>
            </div>

          </form>
          <br style="clear:both;" />

        </div>
      </div>
    </div>
  </section>


@endsection
