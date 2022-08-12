@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>CSVダウンロード</h1>
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
                <h4>翌営業日納品予定のデータをダウンロード</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">

                  <form method="POST" enctype="multipart/form-data" action="{{ url('/admin/export') }}">
                  	@csrf
                  	<div class="form-group">
                  		<!-- <input type="file" class="form-control" name="file[]" style="padding-bottom:37px;" multiple>
                  		<br> -->
                  		<button type="submit" class="btn btn-success">次の営業日分のCSVをダウンロードする</button>
                  	</div>
                  </form>

                </div>
              </div>
            </div>
            <div class="row mt-4">
              <div class="col-12">
                <h4>納品予定期間を選択してダウンロード</h4>
              </div>
            </div>

            <form method="POST" enctype="multipart/form-data" action="{{ url('/admin/export') }}">
              @csrf
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label>いつから</label>
                    <input type="date" name="start" value="" class="form-control" required>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>いつまで</label>
                    <input type="date" name="end" value="" class="form-control" required>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <button type="submit" name="kikan" value="1" class="btn btn-primary">CSVをダウンロードする</button>
                  </div>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>






@endsection
