@extends('layouts.app')

@section('content')




  <section class="section">
    <div class="section-header">
      <h1>カート編集</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="/">HOME</a></div>
        <div class="breadcrumb-item">カート編集</div>
      </div>
    </div>


    <!-- <div class="input-group">
      <input type="text" name="nouhinbi[]" class="nouhinbi text-center form-control datepicker" value="2021-06-26">
    </div> -->



    <div class="section-body">
      <div class="invoice">
        <div class="invoice-print">


          <!-- <form action="{{ url('/change_store') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            <input name="order_id" value="1" />
            <input name="store_name" value="引野" />
            <input name="tokuisaki_name" value="ﾊﾛｰｽﾞ 水産" />
            <div class="float-right">
                <button type="submit" class="btn btn-warning">この内容で問い合わせる</button>
            </div>
          </form> -->


          <form action="{{ url('/adddeal') }}" method="POST" name="cart_id" value="" class="form-horizontal">
            {{ csrf_field() }}
            <div class="row mt-4 order">
              <div class="col-md-12">
                <div class="section-title">オーダー内容</div>
                <div id="order"></div>
              </div>
            </div>
            <div class="float-right">
                <!-- <button type="submit" name="adddeal_btn" class="btn btn-warning">この内容で問い合わせる</button> -->
                <button type="submit" name="addsuscess_btn" class="btn btn-success">この内容で注文する</button>
            </div>
          </form>
          <div class="float-right" style="margin-right:5px;">
              <a href="javascript:history.back()"><button class="btn btn-info">戻って編集する</button></a>
          </div>

          <br style="clear:both;" />

        </div>
      </div>
    </div>
  </section>



@endsection
