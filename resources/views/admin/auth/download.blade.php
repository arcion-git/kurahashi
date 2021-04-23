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
                <h4>未取り込みのデータをダウンロード</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <button class="btn btn-primary">CSVをダウンロードする</button>
                </div>
              </div>
            </div>



            <div class="row mt-4">
              <div class="col-12">
                <h4>期間を選択してダウンロード</h4>
              </div>
            </div>


            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label>いつから</label>
                  <input type="date" class="form-control">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label>いつまで</label>
                  <input type="date" class="form-control">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <button class="btn btn-primary">CSVをダウンロードする</button>
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
