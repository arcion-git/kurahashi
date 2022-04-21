@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>商品画像アップロード</h1>
    <div class="section-header-breadcrumb">
    </div>
  </div>
  <div class="section-body">
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="row mt-4">
              <div class="col-12">
                <h4>新規画像アップロード</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <form method="POST" enctype="multipart/form-data" action="{{ url('/admin/imgsave') }}">
                  	@csrf
                  	<div class="form-group">
                  		<input type="file" class="form-control" name="file[]" style="padding-bottom:37px;" multiple>
                  		<br>
                  		<button type="submit" class="btn btn-success">アップロード</button>
                  	</div>
                  </form>
                </div>
              </div>
            </div>
            <div class="row mt-4">
              <div class="col-12">
                <h4>登録画像一覧</h4>
              </div>
            </div>
            <div class="row">
                <?php
                $images = glob('storage/item/*jpg');
                $images = array_reverse($images);
                foreach($images as $image) {
                	echo '<div class="col-3"><a class="luminous" href="' , $image , '"><img src="/' , $image , '" alt="" width="100%"></a><p>' , $image , '</p></div>';
                }
                ?>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>






@endsection
