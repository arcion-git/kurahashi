@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>CSVインポート</h1>
    <div class="section-header-breadcrumb">
    </div>
  </div>
  <div class="section-body">
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-body">

            <!-- <div class="row mt-4">
              <div class="col-12">
                <h4>一括インポート</h4>
              </div>
            </div>
            <div class="form-group">
              <label>Zipファイルを入れてください</label>
              <input type="file" class="form-control" style="padding-bottom:37px;">
            </div> -->

            <div class="row mt-4">
              <div class="col-12">
                <h4>商品情報インポート</h4>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>商品詳細（ProductItemDetail）</label>
                  <form action="itemimport" method="POST" enctype="multipart/form-data">
                  	@csrf
                  	<div class="form-group">
                  		<input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                  		<br>
                  		<button class="btn btn-success">インポート</button>
                  	</div>
                  </form>
                </div>
              </div>



              <div class="col-12">
                <div class="form-group">
                  <label>価格グループ（PriceGroupInfo）</label>
                  @if ($errors->any())
                  <div class="alert alert-danger">
                  	<ul>
                  		@foreach ($errors->all() as $error)
                  			<li>{{ $error }}</li>
                  		@endforeach
                  	</ul>
                  </div>
                  @endif
                  <form action="PriceGroupeImport" method="POST" enctype="multipart/form-data">
                  	@csrf
                  	<div class="form-group">
                  		<input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                  		<br>
                  		<button class="btn btn-success">インポート</button>
                  	</div>
                  </form>
                </div>
              </div>


              <div class="col-6">
                <div class="form-group">
                  <label>限定お買い得商品（genteiPraiceInfo）</label>
                  <!-- <label>限定お買い得商品（SetonagiItem）</label> -->
                  @if ($errors->any())
                  <div class="alert alert-danger">
                  	<ul>
                  		@foreach ($errors->all() as $error)
                  			<li>{{ $error }}</li>
                  		@endforeach
                  	</ul>
                  </div>
                  @endif
                  <form action="SetonagiItemImport" method="POST" enctype="multipart/form-data">
                  	@csrf
                  	<div class="form-group">
                  		<input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                  		<br>
                  		<button class="btn btn-success">インポート</button>
                  	</div>
                  </form>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label>市況商品（SpecialPriceInfo）</label>
                  @if ($errors->any())
                  <div class="alert alert-danger">
                  	<ul>
                  		@foreach ($errors->all() as $error)
                  			<li>{{ $error }}</li>
                  		@endforeach
                  	</ul>
                  </div>
                  @endif
                  <form action="SpecialPriceImport" method="POST" enctype="multipart/form-data">
                  	@csrf
                  	<div class="form-group">
                  		<input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                  		<br>
                  		<button class="btn btn-success">インポート</button>
                  	</div>
                  </form>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label>商品価格（ListPriceInfo）</label>
                  @if ($errors->any())
                  <div class="alert alert-danger">
                  	<ul>
                  		@foreach ($errors->all() as $error)
                  			<li>{{ $error }}</li>
                  		@endforeach
                  	</ul>
                  </div>
                  @endif
                  <form action="PriceImport" method="POST" enctype="multipart/form-data">
                  	@csrf
                  	<div class="form-group">
                  		<input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                  		<br>
                  		<button class="btn btn-success">インポート</button>
                  	</div>
                  </form>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label>得意先ごとのおすすめ商品（BuyerRecommend）</label>
                  @if ($errors->any())
                  <div class="alert alert-danger">
                  	<ul>
                  		@foreach ($errors->all() as $error)
                  			<li>{{ $error }}</li>
                  		@endforeach
                  	</ul>
                  </div>
                  @endif
                  <form action="BuyerRecommendImport" method="POST" enctype="multipart/form-data">
                  	@csrf
                  	<div class="form-group">
                  		<input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                  		<br>
                  		<button class="btn btn-success">インポート</button>
                  	</div>
                  </form>
                </div>
              </div>


              <!-- <div class="col-6">
                <div class="form-group">
                  <label>価格情報 - 定価</label>
                  <input type="file" class="form-control" style="padding-bottom:37px;">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label>商品情報 - 特価</label>
                  <input type="file" class="form-control" style="padding-bottom:37px;">
                </div>
              </div> -->
              <div class="col-6">
                <div class="form-group">
                  <label>商品カテゴリ（ProductCategory）</label>
                  @if ($errors->any())
                  <div class="alert alert-danger">
                  	<ul>
                  		@foreach ($errors->all() as $error)
                  			<li>{{ $error }}</li>
                  		@endforeach
                  	</ul>
                  </div>
                  @endif
                  <form action="CategoryItemImport" method="POST" enctype="multipart/form-data">
                  	@csrf
                  	<div class="form-group">
                  		<input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                  		<br>
                  		<button class="btn btn-success">インポート</button>
                  	</div>
                  </form>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label>商品カテゴリマスター（CategoryMaster）</label>
                  @if ($errors->any())
                  <div class="alert alert-danger">
                  	<ul>
                  		@foreach ($errors->all() as $error)
                  			<li>{{ $error }}</li>
                  		@endforeach
                  	</ul>
                  </div>
                  @endif
                  <form action="CategoryImport" method="POST" enctype="multipart/form-data">
                  	@csrf
                  	<div class="form-group">
                  		<input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                  		<br>
                  		<button class="btn btn-success">インポート</button>
                  	</div>
                  </form>
                </div>
              </div>
              <!-- <div class="col-6">
                <div class="form-group">
                  <label>商品タグ</label>
                  @if ($errors->any())
                  <div class="alert alert-danger">
                  	<ul>
                  		@foreach ($errors->all() as $error)
                  			<li>{{ $error }}</li>
                  		@endforeach
                  	</ul>
                  </div>
                  @endif
                  <form action="TagImport" method="POST" enctype="multipart/form-data">
                  	@csrf
                  	<div class="form-group">
                  		<input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                  		<br>
                  		<button class="btn btn-success">インポート</button>
                  	</div>
                  </form>
                </div>
              </div> -->
            </div>

            <div class="row mt-4">
              <div class="col-12">
                <h4>顧客情報インポート</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>顧客担当者（CustomerRep）</label>
                  @if ($errors->any())
                  <div class="alert alert-danger">
                  	<ul>
                  		@foreach ($errors->all() as $error)
                  			<li>{{ $error }}</li>
                  		@endforeach
                  	</ul>
                  </div>
                  @endif
                  <form action="userimport" method="POST" enctype="multipart/form-data">
                  	@csrf
                  	<div class="form-group">
                  		<input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                  		<br>
                  		<button class="btn btn-success">インポート</button>
                  	</div>
                  </form>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label>顧客担当店舗（CustomerResponsibleStore）</label>
                  @if ($errors->any())
                  <div class="alert alert-danger">
                  	<ul>
                  		@foreach ($errors->all() as $error)
                  			<li>{{ $error }}</li>
                  		@endforeach
                  	</ul>
                  </div>
                  @endif
                  <form action="StoreUserImport" method="POST" enctype="multipart/form-data">
                  	@csrf
                  	<div class="form-group">
                  		<input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                  		<br>
                  		<button class="btn btn-success">インポート</button>
                  	</div>
                  </form>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label>得意先店舗（CustomerStore）</label>
                  @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                  @endif
                  <form action="StoreImport" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                      <input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                      <br>
                      <button class="btn btn-success">インポート</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>


            <div class="row mt-4">
              <div class="col-12">
                <h4>休日カレンダーインポート</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label>休日カレンダー（Calendar）</label>
                  @if ($errors->any())
                  <div class="alert alert-danger">
                  	<ul>
                  		@foreach ($errors->all() as $error)
                  			<li>{{ $error }}</li>
                  		@endforeach
                  	</ul>
                  </div>
                  @endif
                  <form action="HolidayImport" method="POST" enctype="multipart/form-data">
                  	@csrf
                  	<div class="form-group">
                  		<input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                  		<br>
                  		<button class="btn btn-success">インポート</button>
                  	</div>
                  </form>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <div class="form-group">
                    <label>配送カレンダー（ShippingCalender）</label>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                    	<ul>
                    		@foreach ($errors->all() as $error)
                    			<li>{{ $error }}</li>
                    		@endforeach
                    	</ul>
                    </div>
                    @endif
                    <form action="ShippingCalenderImport" method="POST" enctype="multipart/form-data">
                    	@csrf
                    	<div class="form-group">
                    		<input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                    		<br>
                    		<button class="btn btn-success">インポート</button>
                    	</div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <!-- <div class="row mt-4">
              <div class="col-12">
                <h4>セトナギ</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label>セトナギユーザー</label>
                  @if ($errors->any())
                  <div class="alert alert-danger">
                  	<ul>
                  		@foreach ($errors->all() as $error)
                  			<li>{{ $error }}</li>
                  		@endforeach
                  	</ul>
                  </div>
                  @endif
                  <form action="SetonagiImport" method="POST" enctype="multipart/form-data">
                  	@csrf
                  	<div class="form-group">
                  		<input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                  		<br>
                  		<button class="btn btn-success">インポート</button>
                  	</div>
                  </form>
                </div>
              </div>
            </div> -->


            <div class="row mt-4">
              <div class="col-12">
                <h4>社内営業インポート</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <div class="form-group">
                    <label>社内営業（InCompanySales）</label>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                    	<ul>
                    		@foreach ($errors->all() as $error)
                    			<li>{{ $error }}</li>
                    		@endforeach
                    	</ul>
                    </div>
                    @endif
                    <form action="Adminimport" method="POST" enctype="multipart/form-data">
                    	@csrf
                    	<div class="form-group">
                    		<input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                    		<br>
                    		<button class="btn btn-success">インポート</button>
                    	</div>
                    </form>
                  </div>
                </div>
              </div>
            </div>



            <div class="row mt-4">
              <div class="col-12">
                <h4>BtoC向け配送情報</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <div class="form-group">
                    <label>配送コード（ShippingCompanyCode）</label>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                      <ul>
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                    @endif
                    <form action="ShippingCompanyCodeImport" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="form-group">
                        <input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                        <br>
                        <button class="btn btn-success">インポート</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <div class="form-group">
                    <label>配送情報（ShippingInfo）</label>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                    	<ul>
                    		@foreach ($errors->all() as $error)
                    			<li>{{ $error }}</li>
                    		@endforeach
                    	</ul>
                    </div>
                    @endif
                    <form action="ShippingInfoImport" method="POST" enctype="multipart/form-data">
                    	@csrf
                    	<div class="form-group">
                    		<input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                    		<br>
                    		<button class="btn btn-success">インポート</button>
                    	</div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <div class="form-group">
                    <label>配送設定（ShippingSetting）</label>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                      <ul>
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                    @endif
                    <form action="ShippingSettingImport" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="form-group">
                        <input type="file" class="form-control" name="file" style="padding-bottom:37px;">
                        <br>
                        <button class="btn btn-success">インポート</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>



          </div>
        </div>
      </div>
    </div>
  </div>
</section>






@endsection
