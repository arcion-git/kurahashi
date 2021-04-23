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


            <div class="row mt-4">
              <div class="col-12">
                <h4>一括インポート</h4>
              </div>
            </div>
            <div class="form-group">
              <label>Zipファイルを入れてください</label>
              <input type="file" class="form-control" style="padding-bottom:37px;">
            </div>

            <div class="row mt-4">
              <div class="col-12">
                <h4>商品情報インポート</h4>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>商品詳細</label>
                  <input type="file" class="form-control" style="padding-bottom:37px;">
                </div>
              </div>
              <div class="col-6">
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
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label>商品カテゴリ</label>
                  <input type="file" class="form-control" style="padding-bottom:37px;">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label>商品タグ</label>
                  <input type="file" class="form-control" style="padding-bottom:37px;">
                </div>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-12">
                <h4>顧客情報インポート</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>顧客担当者</label>
                  <input type="file" class="form-control" style="padding-bottom:37px;">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label>顧客担当店舗</label>
                  <input type="file" class="form-control" style="padding-bottom:37px;">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label>得意先</label>
                  <input type="file" class="form-control" style="padding-bottom:37px;">
                </div>
              </div>
            </div>



            <div class="row mt-4">
              <div class="col-12">
                <h4>社内営業インポート</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>社内営業</label>
                  <input type="file" class="form-control" style="padding-bottom:37px;">
                </div>
              </div>
            </div>


              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>






@endsection
