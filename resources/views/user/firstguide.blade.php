@extends('layouts.app')

@section('content')


        <section class="section">
          <div class="section-header">
            <h1>
              はじめてご利用のお客様へ
            </h1>
          </div>
          <div class="section-body guide firstguide">

            <div class="row">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-body">
                    <div class="entry-content">
                      <div class="firstguide_logo">
                        <img src="{{ asset('img/logo.png') }}" width="300px;">
                      </div>
                    <h2 class="firstguide_title">探す手間を市場が取り除く<i class="fa fa-lightbulb"></i></h2>
                    <p>この度は、SETOnagiデジタルオーダーブックのご利用をいただき、有難うございます。手軽に頼め安心できる食品を、<span>「市場からお届けする」</span>ことをコンセプトとしています。 <span>デジタル化が進むほど</span>、寧ろ気付かされる <span>”人と人との繋がり” の大切さ。</span>当社営業が、皆様の購買活動を<span>「人的パワー」</span>でバックアップいたします。 健康寿命を全うするにも、なにより「人間同志の繋がり」が大切。 SETOnagiは、<span>「美味しいで元気。」</span>を皆様にお届けします。</p>
                    <h2 class="firstguide_title">SETOnagiオーダーブックの特徴</h2>
                    <p>創業80年の中四国の市場(荷受)である<span>「株式会社クラハシ」</span>が運営しております。</p>

                    <h2 class="firstguide_title"><i class="fa fa-check"></i>限定お買い得商品</h2>
                    <p>営業が特にお勧めする「今こそお買い得」商品を、写真とともに掲載しています。この機会にぜひお買い求めください。</p>

                    @if(!isset($shipping_code))
                    <h2 class="firstguide_title"><i class="fa fa-user"></i>担当おすすめ商品</h2>
                    <p>営業がお客様毎のご要望に応じた、おすすめ商品を登録し掲載いたします。</p>
                    <h2 class="firstguide_title"><i class="fa fa-fire"></i>市況商品</h2>
                    <p>12時30分から17時までの公開となります。また、月曜、木曜、金曜のみの掲載となります。日々更新される魚介商品などの本日市況に加え、冷凍食品も発信して参ります。付加価値の高い料理作りに是非ご活用下さい。</p>
                    <h2 class="firstguide_title"><i class="fa fa-heart"></i>お気に入り商品</h2>
                    <p>気になる商品はお気に入りリストへ入れることで、検討中の商品へすぐアクセスできるようになります。</p>
                    <h2 class="firstguide_title"><i class="fa fa-redo-alt"></i>リピートオーダー機能</h2>
                    <p>「毎週」「指定する曜日」等、リピート発注したい場合に設定頂くと、自動的に発注する事が出来ます。</p>
                    @endif

                    <div class="firstguide_btn">
                      <a href="setonagi" class="btn-lg btn-warning">限定お買い得商品一覧はこちら</a>
                    </div>

                  </div>
                </div>
              </div>
          </div>
        </section>


@endsection
