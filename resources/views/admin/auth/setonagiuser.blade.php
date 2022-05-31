@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>顧客一覧</h1>
    <div class="section-header-breadcrumb">
    </div>
  </div>
  <div class="section-body">
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-body">

            <div class="float-right">
              <form>
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="検索">
                  <div class="input-group-append">
                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </form>
            </div>

            <div class="clearfix mb-3"></div>

            <div class="table-responsive">
              <table class="table table-striped">
                <tr>
                  <th class="text-center">ユーザーID</th>
                  <th class="text-center">お名前</th>
                  <!-- <th class="text-center">会社名</th>
                  <th class="text-center">住所</th> -->
                  <th class="text-center">電話番号</th>
                  <th class="text-center">メールアドレス</th>
                  <th class="text-center">利用許可</th>
                  <th class="text-center">かけ払い審査状況</th>
                  <th class="text-center">利用限度額</th>
                  <th class="text-center">利用を開始</th>
                  <th class="text-center">メール通知</th>
                </tr>

                @foreach($users as $user)
                <tr>
                  <td class="text-center">
                    {{$user->id}}
                  </td>
                  <td class="text-center">
                    {{$user->name}}
                  </td>
                  <td class="text-center">
                    {{$user->tel}}
                  </td>
                  <td class="text-center">
                    {{$user->email}}
                  </td>
                  <td class="text-center">
                    @if(isset($user->setonagi))
                    {{$user->setonagi()->kakebarai_ok}}
                    @endif
                  </td>
                  <td class="text-center">
                    @if(isset($user->setonagi))
                    {{$user->setonagi()->kakebarai_sinsa}}
                    @endif
                  </td>
                  <td class="text-center">
                    @if(isset($user->setonagi))
                    {{$user->setonagi()->kakebarai_limit}}
                    @endif
                  </td>
                  <td class="text-center">
                    <a href="{{ url('/admin/user/repeatorder/'.$user->id) }}"><button class="btn btn-warning">登録</button></a>
                  </td>
                  <td class="text-center">
                    <a href="{{ url('/admin/user/repeatorder/'.$user->id) }}"><button class="btn btn-warning">通知</button></a>
                  </td>
                </tr>
                @endforeach

              </table>
            </div>
            <div class="float-right">
              <nav>
                <ul class="pagination">
                  {{ $users->links() }}
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>






@endsection
