@extends('layouts.app')

@section('content')


<section class="section">
  <div class="section-header">
    <h1>セトナギユーザー管理</h1>
    <div class="section-header-breadcrumb">
    </div>
  </div>
  <div class="section-body">
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-body">

            <!-- <div class="float-right">
              <form>
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="検索">
                  <div class="input-group-append">
                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </form>
            </div> -->

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
                  <th class="text-center">かけ払い審査状況</th>
                  <th class="text-center">利用限度額</th>
                  <th class="text-center">利用金額</th>
                  <th class="text-center">操作</th>
                  <th class="text-center">利用許可</th>
                  <!-- <th class="text-center">メール通知</th> -->
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
                    {{$user->setonagi()->kakebarai_sinsa}}
                    @endif
                  </td>
                  <td class="text-center">
                    @if(isset($user->setonagi))
                    {{$user->setonagi()->kakebarai_limit}}
                    @endif
                  </td>
                  <td class="text-center">
                    @if(isset($user->setonagi))
                    {{$user->setonagi()->kakebarai_usepay}}
                    @endif
                  </td>
                  <td class="text-center">
                      @if($user->setonagi()->kakebarai_sinsa == '利用可' & $user->setonagi()->kakebarai_riyou == '')
                      <form action="{{ url('/admin/riyoukyoka') }}" method="POST" class="form-horizontal">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{$user->id}}" />
                        <button type="submit" class="riyoukyoka_btn btn btn-warning">利用許可</button>
                      </form>
                      @endif
                      @if($user->setonagi()->kakebarai_sinsa == 'ご利用不可' & $user->setonagi()->setonagi_ok == '')
                      <form action="{{ url('/admin/card_riyoukyoka') }}" method="POST" class="form-horizontal">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{$user->id}}" />
                        <button type="submit" class="card_riyoukyoka_btn btn btn-primary">カード払いのみで利用許可</button>
                      </form>
                      @endif
                      @if($user->setonagi()->kakebarai_riyou == '1' || $user->setonagi()->setonagi_ok == '1')
                      <form action="{{ url('/admin/riyouteisi') }}" method="POST" class="form-horizontal">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{$user->id}}" />
                        <button type="submit" class="riyouteisi_btn btn btn-success">利用停止</button>
                      </form>
                      @endif
                  </td>
                  <td class="text-center">
                      @if($user->setonagi()->kakebarai_riyou == 1)
                      利用許可済
                      @elseif($user->setonagi()->setonagi_ok == 1)
                      カード払い利用許可済
                      @elseif($user->setonagi()->kakebarai_sinsa == 'ご利用可' & $user->setonagi()->kakebarai_riyou == 0)
                      利用許可待ち
                      @elseif($user->setonagi()->kakebarai_sinsa == '審査受付中')
                      審査中
                      @else
                      利用不可
                      @endif
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
