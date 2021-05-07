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



                  @if(Session::has('message'))
                  メッセージ：{{ session('message') }}
                  @endif

                  @if (is_array($errors))
                  <div class="flushComment">
                      ・CSVインポートエラーが発生しました。以下の内容を確認してください。<br>
                      @if (count($errors['registration_errors']) > 0)
                          [対象のデータ：新規登録]
                          <ul>
                          @foreach ($errors['registration_errors'] as $line => $columns)
                              @foreach ($columns as $error)
                              <li>{{ $line }}行目：{{ $error }}</li>
                              @endforeach
                          @endforeach
                          </ul>
                      @endif
                      @if (count($errors['update_errors']) > 0)
                          [対象のデータ：編集登録]<br>
                          <ul>
                          @foreach ($errors['update_errors'] as $line => $columns)
                              @foreach ($columns as $error)
                              <li>{{ $line }}行目：{{ $error }}</li>
                              @endforeach
                          @endforeach
                          </ul>
                      @endif
                  </div>
                  @endif

                  <form action="/form/import-csv" method="post" enctype="multipart/form-data" id="csvUpload">
                  <input type="file" value="ファイルを選択" name="csv_file">
                  {{ csrf_field() }}
                  <button type="submit">インポート</button>
                  </form>





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
