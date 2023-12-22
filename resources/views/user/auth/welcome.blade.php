<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SETOnagi | セトナギ（新鮮な市場のお魚をかんたん仕入れ）</title>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

    <!-- Styles -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <!-- CSS Libraries -->
    <link href="{{ asset('css/selectric.css') }}" rel="stylesheet" >

    <!-- Template CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" >
    <link href="{{ asset('css/lp.css') }}" rel="stylesheet" >
    <link href="{{ asset('css/components.css') }}" rel="stylesheet" >


</head>
<body>
  <div id="app">
    <div class="header_bg">
      <header>
        <div class="logo">
          <img src="{{ asset('img/logo.png') }}" />
        </div>
        <ul class="menu pc">
          <li><a href="#NEWS">新着情報</a></li>
          <li><a href="#ABOUT">サービスについて</a></li>
          <li><a href="#FUNCTION">機能紹介</a></li>
          <li><a href="#PAY">決済方法</a></li>
          <li><a href="#START">ご利用スタートの流れ</a></li>
          <!-- <li class="register_btn"><a href="{{ route('register') }}">会員登録</a></li> -->
          <?php if(!isset($_GET['type'])) { ?>
            <li class="register_btn"><a href="{{ route('register') }}">会員登録</a></li>
          <?php } else { ?>
            <li class="register_btn">
              <form action="{{ url('/user/register_c') }}" method="GET" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" id="type" name="type" value="<?php echo isset($_GET['type']) ? htmlspecialchars($_GET['type']) : ''; ?>">
                <button id="" type="submit" class="">会員登録</button>
              </form>
            </li>
          <?php } ?>
          <li class="login_btn"><a href="{{ route('login', ['type' => request()->input('type')]) }}">ログイン</a></li>
        </ul>
        <div id="nav-wrapper" class="nav-wrapper sp">
          <div class="hamburger" id="js-hamburger">
            <span class="hamburger__line hamburger__line--1"></span>
            <span class="hamburger__line hamburger__line--2"></span>
            <span class="hamburger__line hamburger__line--3"></span>
          </div>
          <nav class="sp-nav">
            <ul>
              <li><a href="#NEWS">新着情報</a></li>
              <li><a href="#ABOUT">サービスについて</a></li>
              <li><a href="#FUNCTION">機能紹介</a></li>
              <li><a href="#PAY">決済方法</a></li>
              <li><a href="#START">ご利用スタートの流れ</a></li>
              <li><a href="{{ url('/welcomecontact') }}">お問い合わせ</a></li>
              <?php if(!isset($_GET['type'])) { ?>
                <li class="register_btn"><a href="{{ route('register') }}">会員登録</a></li>
              <?php } else { ?>
                <li style="margin-bottom:20px;" class="register_btn">
                  <form action="{{ url('/user/register_c') }}" method="GET" class="form-horizontal">
                    {{ csrf_field() }}
                    <input type="hidden" id="type" name="type" value="<?php echo isset($_GET['type']) ? htmlspecialchars($_GET['type']) : ''; ?>">
                    <button id="" type="submit" class="">会員登録</button>
                  </form>
                </li>
              <?php } ?>
              <li class="login_btn"><a href="{{ route('login') }}">ログイン</a></li>
            </ul>
          </nav>
          <div class="black-bg" id="js-black-bg"></div>
         </div>
      </header>
      <div class="top_message">
        <div class="device pc">
            <img src="{{ asset('img/lp/device.png') }}" />
        </div>
        <div class="catch">
          <img src="{{ asset('img/lp/catch.png') }}" />
          <div class="device sp">
              <img src="{{ asset('img/lp/device.png') }}" />
          </div>


          <?php if(!isset($_GET['type'])) { ?>
            <p>※弊社と取引のないお客様はヤマト掛け払いの審査があり、法人会員様限定となります。<br />※市場での引き取り限定となります。（配送に関しましては、今後ご用意予定です。）</p>
          <?php } else { ?>
            <p>※商品は配送ルートごとにお引き取りいただけます。</p>
          <?php } ?>

          <div class="nagi01">
              <img src="{{ asset('img/lp/nagi01.png') }}" />
          </div>
        </div>
      </div>
    </div>
    <section id="NEWS">
      <h2>新着情報</h2>
      <ul class="news">
        <li><span>2022年10月21日</span>WEBサイトを公開しました。</li>
      </ul>
    </section>
    <section id="ABOUT">
      <h2>SETOnagiでできること</h2>
      <div class="about">
        <div class="about_div">
          <div class="about_logo">
              <img src="{{ asset('img/logo.png') }}" />
          </div>
          <div class="about_text nagi02">
              <p>SETOnagiは、<span>新鮮な市場のお魚をネットショプ感覚</span>でかんたんに<span>仕入れ・購入できるサービス</span>です。<br />また当サービスは、<span>老舗の「魚市場」が運営しています。</span><br />当社営業が、「人的パワー」でお客様の仕入れをバックアップ、<br /><span>今が旬の商品、おすすめの仕入れ商品をご提案</span>をさせていただきます。<br />安心できる食品・食材を市場から、<br /><span class="blue">「美味しいで元気。」</span><br />お届けいたします。<br /><div class="sp sp_nagi02"><img class="" src="{{ asset('img/lp/nagi02.png') }}" /></div>
              <?php if(!isset($_GET['type'])) { ?>
                <p class="small">※弊社と取引のないお客様はヤマト<br />掛け払いの審査があり、法人会員様限定となります。<br />※市場での引き取り限定となります。<br />（配送に関しましては、今後ご用意予定です。）</p>
              <?php } else { ?>
                <p class="small">※お支払いはクレジットカード決済となります。<br />※商品は配送ルートごとにお引き取りいただけます。</p>
              <?php } ?>
          </div>
        </div>
      </div>
      <div class="about02">
        <div class="about_add">
          <img src="{{ asset('img/lp/add.jpg') }}" />
        </div>
      </div>
    </section>
    <section id="BENEFIT">
      <div class="nagi03">
        <h2><span class="pc">SETOnagi</span>の<br class="sp"/>3つのメリット</h2>
      </div>
      <div class="benefit">
        <div class="benefit_item">
          <div class="benefit_img">
            <img src="{{ asset('img/lp/merit01.jpg') }}" />
          </div>
          <div class="benefit_title">
            <h3>市場でしか取引されない<br />新鮮な選りすぐり商品が掲載</h3>
          </div>
          <div class="benefit_text">
              <p>市場の強みを生かし、<span>新鮮な食材を安く</span>、ご希望の量でご購入いただけます。手軽に頼めて安心できる食材をご提供していきます。<span>業務筋の方の調達のお手伝い</span>をします。</p>
          </div>
        </div>
        <div class="benefit_item">
          <div class="benefit_img">
            <img src="{{ asset('img/lp/merit02.jpg') }}" />
          </div>
          <div class="benefit_title">
            <h3>お支払いは月に１度<br /><?php if(!isset($_GET['type'])) { ?>掛け払い<?php } else { ?><?php } ?>クレジットカードで<br />まとめて決済</h3>
          </div>
          <div class="benefit_text">
            <?php if(!isset($_GET['type'])) { ?>
              <p>企業間における請求業務の決済サービスになりますので、都度決済ではなく、ビジネスサイクルとして当サイトのお支払い方法をご活用いただけます。<span>毎回現金でお支払いする手間を省く</span>ことができます。<br /><a href="#PAY">決済方法の詳細はこちら</a></p>
            <?php } else { ?>
              <p>都度決済ではなく、月単位でのまとめてのお支払いが可能です。<span>毎回現金でお支払いする手間を省く</span>ことができます。<br /><a href="#PAY">決済方法の詳細はこちら</a></p>
            <?php } ?>
          </div>
        </div>
        <div class="benefit_item">
          <div class="benefit_img">
            <img src="{{ asset('img/lp/merit03.jpg') }}" />
          </div>
          <div class="benefit_title">
            <h3>市場で直接<br /><?php if(!isset($_GET['type'])) { ?><?php } else { ?>ご指定の場所で<br /><?php } ?>最短翌日受け取り</h3>
          </div>
          <div class="benefit_text">
              <p>SETOnagiでは所定の場所にて、直接商品をお引き取りいただけます。<br /><a href="#HIKITORI">お引き取り場所・時間帯の詳細はこちら</a></p>
          </div>
        </div>
      </div>
      <p class="annotation small">※既に弊社とお取引のあるお客様は、決済サービスを介さず、弊社との直接決済にてご利用いただけます。
        <?php if(!isset($_GET['type'])) { ?>
        <?php } else { ?>
          <br />※受け取り方法によっては日時がご指定いただけない場合がございます。</p>
        <?php } ?>
      </p>

    </section>
    <div class="bg02">
    </div>
    <section id="FUNCTION">
      <div class="function_bigtitle">
        <h2><span>かんたん</span>に特化した<br />SETOnagiの機能について</h2>
      </div>
      <div class="function">
        <div class="function_item">
          <div class="function_title">
            <h3>限定お買い得商品</h3>
          </div>
          <div class="function_img">
            <img src="{{ asset('img/lp/kinou01.png') }}" />
          </div>
          <div class="function_text">
            <p>営業が特にお勧めする<span>「今こそお買い得」商品</span>を、写真とともに掲載しています。この機会にぜひお買い求めください。</p>
          </div>
        </div>
        <?php if(!isset($_GET['type'])) { ?>
        <div class="function_item">
          <div class="function_title">
            <h3>担当のおすすめ商品</h3>
          </div>
          <div class="function_img">
            <img src="{{ asset('img/lp/kinou02.png') }}" />
          </div>
          <div class="function_text">
            <p>お客様専属の担当営業が<span>ご要望に応じた、おすすめ商品</span>を登録し掲載いたします。</p>
          </div>
        </div>
        <div class="function_item">
          <div class="function_title">
            <h3>市況（時価）商品</h3>
          </div>
          <div class="function_img">
            <img src="{{ asset('img/lp/kinou03.png') }}" />
          </div>
          <div class="function_text">
            <p>日々更新される<span>魚介商品などの本日市況</span>に加え、冷凍食品も発信して参ります。付加価値の高い料理作りに是非ご活用下さい。※１</p>
          </div>
        </div>
        <div class="function_item">
          <div class="function_title">
            <h3>お気に入り商品</h3>
          </div>
          <div class="function_img">
            <img src="{{ asset('img/lp/kinou04.png') }}" />
          </div>
          <div class="function_text">
            <p>気になる商品は<span>お気に入りリストへ入れることで、検討中の商品へすぐアクセス</span>できるようになります。</p>
          </div>
        </div>
        <div class="function_item">
          <div class="function_title">
            <h3>リピートオーダー機能</h3>
          </div>
          <div class="function_img">
            <img src="{{ asset('img/lp/kinou05.png') }}" />
          </div>
          <div class="function_text">
            <p>「毎週」「指定する曜日」等、リピート発注したい場合に設定頂くと、<span>自動的に発注</span>する事が出来ます。※2</p>
          </div>
        </div>
        <div class="function_item">
          <div class="function_title">
            <h3>受け取り店舗・日付指定</h3>
          </div>
          <div class="function_img">
            <img src="{{ asset('img/lp/kinou06.png') }}" />
          </div>
          <div class="function_text">
            <p>お受け取りいただく店舗・日付の指定をしていただけます。<span>予め仕入れの日程を決めておくことで発注の手間を省く</span>ことができます。※2</p>
          </div>
        </div>
        <?php } else { ?>
          <div class="function_item">
            <div class="function_title">
              <h3>受け取り場所を選べる</h3>
            </div>
            <div class="function_img">
              <img src="{{ asset('img/lp/kinou02.png') }}" />
            </div>
            <div class="function_text">
              <p>受け取りは市場、もしくは配送ルートに応じた場所で受け取れるため、<span>新鮮な状態</span>でお召し上がりいただけます。</p>
            </div>
          </div>
          <div class="function_item">
            <div class="function_title">
              <h3>日付指定ができる</h3>
            </div>
            <div class="function_img">
              <img src="{{ asset('img/lp/kinou06.png') }}" />
            </div>
            <div class="function_text">
              <p>お受け取りいただく日付の指定をしていただけます。<span>予め仕入れの日程を決めておくことで発注の手間を省く</span>ことができます。※2</p>
            </div>
          </div>
        <?php } ?>
      </div>
      <?php if(!isset($_GET['type'])) { ?>
        <p class="annotation small">※1.月曜、木曜、金曜の12時30分から17時までの限定掲載となります。<br />※2.特定会員様（当社決済、直接お取引のお客様）のみご利用いただける機能です。</p>
      <?php } else { ?>
        <!-- <p class="annotation small">※2.特定会員様（当社決済、直接お取引のお客様）のみご利用いただける機能です。</p> -->
      <?php } ?>
    </section>
    <section id="GENTEI">
      <div class="nagi04">
        <h2>限定お買い得商品<br class="sp"/><span>（掲載例）</span></h2>
      </div>
      <div class="gentei">
        <img src="{{ asset('img/lp/itemsample.jpg') }}" />
      </div>
      <p class="annotation small">※過去に掲載した商品の例になります。日ごとに掲載商品は変更となりますのでご了承ください。</p>
    </section>
    <div class="bg03">
    </div>
    <section id="PAY">
      <div class="pay_bigtitle">
        <h2>ご利用いただける決済方法<br />
        <?php if(!isset($_GET['type'])) { ?>
          <span class="annotation small">※既に弊社と取引のあるお客様は、下記決済サービスを介さず、弊社との直接決済にてご利用いただけます。</span>
        <?php } else { ?>
          <span class="annotation small">※既に弊社と取引のあるお客様は、下記決済サービスを介さず、弊社との直接決済にてご利用いただけます。</span>
        <?php } ?></h2>
      </div>
      <div class="pay">
        <?php if(!isset($_GET['type'])) { ?>
        <div class="pay_item kakebarai">
          <div class="pay_title">
            <h3>クロネコ掛け払い</h3>
          </div>
          <div class="pay_img">
            <img src="{{ asset('img/lp/kakebarai.jpg') }}" />
          </div>
        </div>
        <div class="pay_item creditcard">
          <div class="pay_title">
            <h3>クレジットカード決済（ヤマトWEBコレクト）</h3>
          </div>
          <div class="pay_img">
            <img src="{{ asset('img/lp/card.png') }}" />
          </div>
        </div>
      </div>
      <?php } else { ?>
        <div class="pay_item creditcard creditcard_c">
          <div class="pay_title">
            <h3>クレジットカード決済（ヤマトWEBコレクト）</h3>
          </div>
          <div class="pay_img">
            <img src="{{ asset('img/lp/card.png') }}" />
          </div>
        </div>
      </div>
      <?php } ?>
      <div class="kakebarai_merit">
        <?php if(!isset($_GET['type'])) { ?>
          <div class="kakebarai_merit_text">
            1.通常の後払い決済同様に月末締め・翌々月5日決済となります。<br />
          	2.毎回現金でお支払い頂く手間が省けます。<br />
          	3.お支払い方法は、「口座振替」「銀行振込」「コンビニ払い」が可能です。
          </div>
          <p class="annotation small text-left">※法人個人事業主のお客様が対象となります。一般個人のお客様は対象外となります。<br />※所定の審査によりご利用条件等ご希望に添えない場合がございます。あらかじめご了承ください。<br />※即日審査で初回からご利用可能です。(審査の回答は、最短5分〜最長2営業日程度)審査が通った場合は、ご登録のご住所宛に「会員登録完了書」が郵送されます。<br />※クロネコ掛け払いの審査に通らなかったら場合、クレジットカード決済にてご利用いただけます。</p>
        </div>
      <?php } else { ?>
        <div class="kakebarai_merit_text_c">
          1.現金が無くてもお買物ができる。<br />
        	2.毎月のお金の管理がしやすい。<br />
        	3.ATMなどの手数料を節約できる。
        </div>
          <p class="annotation small text-left">※入力されたクレジットカードのご利用状況によってはご購入いただけない場合があります。<br />※代金のご精算はお客様とクレジット会社の契約によります。<br />※お引落し日などのご不明な点は、各クレジット会社に直接お問い合せください。なお、クレジットカード会社の締日等により精算が決済日の翌月になる場合がございますのでご了承ください。</p>
      </div>
      <?php } ?>
    </section>
    <section id="HIKITORI">
      <div class="hikitori_bigtitle">
        <h2>商品のお引き取りについて<br />
          <?php if(!isset($_GET['type'])) { ?>
            <span class="small">※受け取り方法によっては日時がご指定いただけない場合がございます。</span>
          <?php } else { ?>
            <span class="small">※商品は下記の市場、または地区ごとの配送ルートごとにお引き取りいただけます。</span><br />
            <span class="small">※受け取り方法によっては日時がご指定いただけない場合がございます。</span>
          <?php } ?>
        </h2>
      </div>
      <div class="hikitori">
        <div class="hikitori_item">
          <div class="hikitori_map"><iframe loading="lazy" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3292.7084462717876!2d133.11255571495488!3d34.38333490765722!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x355055c3a8c6db03%3A0xe8d6bd85d5e50ef9!2z44Oe44Oq44Oz44ON44Kv44K544OI!5e0!3m2!1sja!2sjp!4v1648661000311!5m2!1sja!2sjp" width="100%" height="400" frameborder="0" allowfullscreen="allowfullscreen" aria-hidden="false"></iframe></div>
          <div class="hikitori_title">マリンネクスト (三原市糸崎)</div>
          <div class="hikitori_text">〒729-0324 広島県三原市糸崎９丁目４ <a href="https://www.google.com/maps/place/%E3%83%9E%E3%83%AA%E3%83%B3%E3%83%8D%E3%82%AF%E3%82%B9%E3%83%88/@34.38333,133.114744,16z/data=!4m5!3m4!1s0x0:0xe8d6bd85d5e50ef9!8m2!3d34.3833305!4d133.1147444?hl=ja" target="_blank">GoogleMapで開く</a><br />お引渡受付時間：9:00～15:00（水・日・祝除く）　TEL：084-863-6660</div>
        </div>
        <div class="hikitori_item nagi06">
          <div class="hikitori_map"><iframe style="border: 0;" tabindex="0" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d13165.576922219221!2d133.2363559!3d34.41674!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x761df09f92408f2f!2z77yI5qCq77yJ44Kx44Oz44K544Kk!5e0!3m2!1sja!2sjp!4v1611846239024!5m2!1sja!2sjp" width="100%" height="400" frameborder="0" allowfullscreen="allowfullscreen" aria-hidden="false"></iframe></div>
          <div class="hikitori_title">株式会社ケンスイ（尾道市東尾道）</div>
          <div class="hikitori_text">〒722-0051 広島県尾道市東尾道7番地2 <a href="https://www.google.com/maps?ll=34.41674,133.236356&z=14&t=m&hl=ja&gl=JP&mapclient=embed&cid=8511223438944276271" target="_blank">GoogleMapで開く</a><br />お引渡受付時間：8:00～17:00（水・日・祝除く）　TEL：0848-46-3515</div>
          <p class="annotation small text-left">① お荷物のお受け取りにお越しいただきましたら、各所の受取カウンターにてお申し付け下さい。<br />② 受付担当者に、商品お引き取りの旨と、お名前、メールにてご案内いたします「ご注文番号」をお伝えください。<br />※ご指定の日時にお越しいただけない場合は、上記電話番号までご連絡をお願いいたします。</p>
        </div>
      </div>
    </section>
    <div class="bg04">
    </div>
    <section id="START">
      <div class="start_bigtitle nagi05">
        <h2>ご利用スタートの流れ</span></h2>
      </div>
      <div class="start">
        <?php if(!isset($_GET['type'])) { ?>
          <div class="start_item">
            <div class="start_title">弊社とのお取引が初めてのお客様</div>
            <div class="start_img"><img class="pc" src="{{ asset('img/lp/start01.jpg') }}" /><img class="sp" src="{{ asset('img/lp/start01sp.jpg') }}" /></div>
            <div class="start_btn start_btn01">会員登録はこちら</div>
          </div>
          <div class="start_item">
            <div class="start_title">既に弊社と取引があるお客様</div>
            <div class="start_img"><img class="pc" src="{{ asset('img/lp/start02.jpg') }}" /><img class="sp" src="{{ asset('img/lp/start02sp.jpg') }}" /></div>
            <div class="start_btn start_btn02">既に弊社と取引があるお客様</div>
          </div>
        <?php } else { ?>
          <div class="start_item">
            <div class="start_title"></div>
            <div class="start_img"><img class="pc" src="{{ asset('img/lp/start03.jpg') }}" /><img class="sp" src="{{ asset('img/lp/start03sp.jpg') }}" /></div>
            <form action="{{ url('/user/register_c') }}" method="GET" class="form-horizontal">
              {{ csrf_field() }}
              <input type="hidden" id="type" name="type" value="<?php echo isset($_GET['type']) ? htmlspecialchars($_GET['type']) : ''; ?>">
              <button id="" type="submit" class="start_btn">会員登録はこちら</button>
            </form>
          </div>
        <?php } ?>
        <?php if(!isset($_GET['type'])) { ?>
        <p class="annotation small">※お電話でお申し込みの場合は、担当営業にご連絡ください。</p>
        <?php } ?>
      </div>
    </section>
  </div>
  <footer id="colophon" class="">
    <div class="inner" id="CONTACT">
      <div class="bottom">
        <div class="column_footer">
          <div class="flex-container">
            <div class="footer_logo">
              <img src="{{ asset('img/logo-w.png') }}">
              <!-- <p>【運営元】<br><strong>株式会社クラハシ</strong><br>〒721-0942広島県福山市引野町1-1-1<br>福山市地方卸売市場内</p> -->
              <p>【運営元】<br><strong>株式会社U-midas</strong><br>広島県三原市糸崎７丁目８番２２号</p>
            </div>
            <div class="footer_menu">
              <ul class="">
                <li><a href="{{ url('/welcomeguide') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}">ご利用ガイド</a></li>
                <li><a href="{{ url('/welcomelow') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}">特商法取引に基づく表記</a></li>
                <li><a href="{{ url('/welcomeprivacypolicy') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}">個人情報保護方針</a></li>
              </ul>
            </div>
            <div class="footer_contact">
              <h3>お問い合わせ窓口<span>CONTACT</span></h3>
              <!-- <p><strong>TEL 084-941-3510</strong></p> -->
              <!-- <p><strong>TEL 080-2943-7978</strong></p>
              <p class="small">平日9：00～18：00（定休 土日祝）</p> -->
              <a href="{{ url('/welcomecontact') }}{{ request()->has('type') ? '?type=' . request()->input('type') : '' }}"><div class="btn navy">メールでお問い合わせ</div></a>
            </div>
          </div>
          <div class="flex-container external_link">
            <ul class="">
              <!-- <li><a href="https://www.kurahashi.co.jp/" target="_blank">株式会社クラハシオフィシャルサイト<img src="https://setonagi.net/wp-content/themes/welcart_basic-beldad-expo/assets/images/expo/footer_link.png"></a></li> -->
              <li><a href="http://u-midas.com/" target="_blank">株式会社U-midasオフィシャルサイト<img src="https://setonagi.net/wp-content/themes/welcart_basic-beldad-expo/assets/images/expo/footer_link.png"></a></li>
            </ul>
          </div>
          <div class="flex-container sns_link">
            <ul class="">
              <!-- <li><a href="https://twitter.com/U_midas" target="_blank"><img src="https://setonagi.net/wp-content/themes/welcart_basic-beldad-expo/assets/images/expo/footer_twitter.png"></a></li> -->
              <li><a href="https://www.youtube.com/c/KurahashiCoJp" target="_blank"><img src="https://setonagi.net/wp-content/themes/welcart_basic-beldad-expo/assets/images/expo/footer_youtube.png"></a></li>
            </ul>
            <p class="copyright">© UMIDASU Co., Ltd.  All rights reserved.</p>
          </div>
          </div><!-- .flex-container -->
        </div><!-- .column1070 -->
      </div>
  </footer>







    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
    <script src="{{ asset('js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <!-- <script src="../node_modules/jquery-pwstrength/jquery.pwstrength.min.js"></script>
    <script src="../node_modules/selectric/public/jquery.selectric.min.js"></script> -->

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth-register.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <script>

    $(function() {
      $(".start_btn01").click(function(){
        Swal.fire({
          title: '',
          html : '弊社とのお取引が初めてですか？<br />会員登録画面に移動します。',
          icon : 'warning',
          showCancelButton: true,
      	  cancelButtonText: 'いいえ',
          confirmButtonText: 'はい'
        }).then((result) => {
          if (result.value) {
            window.location.href = "{{ route('register') }}";
          }
        });
      });
      $(".start_btn02").click(function(){
        Swal.fire({
          title: '',
          html : '過去に弊社と取引がありますか？<br />お問い合わせ画面に移動します。',
          icon : 'warning',
          showCancelButton: true,
      	  cancelButtonText: 'いいえ',
          confirmButtonText: 'はい'
        }).then((result) => {
          if (result.value) {
            window.location.href = "{{ url('/welcomecontact') }}";
          }
        });
      });
    });
    //ＵＲＬのパラメータを取得するための関数
    function getUrlParam(param){
        var pageUrl = window.location.search.substring(1);
        var urlVar = pageUrl.split('&');
        for (var i = 0; i < urlVar.length; i++)
        {
            var paramName = urlVar[i].split('=');
            if (paramName[0] == param)
            {
                return decodeURI(paramName[1]);
            }
        }
    }
    $(function() {
        var message = getUrlParam('message');
        console.log(message);
        if (message) {
          Swal.fire({
            html: message,
            // position: 'top-end',
            // toast: true,
            icon: 'warning',
            showConfirmButton: false,
            // timer: 1500
          });
        }
    });
    </script>

    <script>
    $(function() {
      window.onload = function () {
          var nav = document.getElementById('nav-wrapper');
          var hamburger = document.getElementById('js-hamburger');
          var blackBg = document.getElementById('js-black-bg');

          hamburger.addEventListener('click', function () {
              nav.classList.toggle('open');
          });
          blackBg.addEventListener('click', function () {
              nav.classList.remove('open');
          });
          $('a').click(function(){
              nav.classList.remove('open');
          });
      };

      $(document).ready(function(){
        var selected = $('#hjkjKbn').val();
        // alert('test');
        //     console.log(selected);
        if ( selected == '1' ) {
          $('#maekabu').show();
          $('#kojin').hide();
        }else if ( selected == '2' ){
          $('#kojin').show();
          $('#maekabu').hide();
        }else{
          $('#maekabu').hide();
          $('#kojin').hide();
        }
      });
      $(document).on("change", "#hjkjKbn", function() {
        var selected = $(this).val();
        // alert('test');
        //     console.log(selected);
        if ( selected == '1' ) {
          $('#maekabu').show();
          $('#kojin').hide();
        }else if ( selected == '2' ){
          $('#kojin').show();
          $('#maekabu').hide();
        }else{
          $('#maekabu').hide();
          $('#kojin').hide();
        }
      });
      $(document).on("change", "#unei_company_hjkjKbn", function() {
        var selected = $(this).val();
        // alert('test');
        //     console.log(selected);
        if ( selected === '1' ) {
          $('#unei_company_detail_houjinkaku').show();
        } else{
          $('#unei_company_detail_houjinkaku').hide();
        }
      });
      $('#unei_company').click(function(){
          if($('#unei_company').prop('checked')){
              $('#unei_company_detail').show();
              // alert('checked!');
          }else{
              $('#unei_company_detail').hide();
              // alert('not checked!');
          }
      });
      $('[name="sqssfKbn"]:radio').change( function() {
        if($('[id=その他]').prop('checked')){
              $('#soufu_detail').show();
        }else{
              $('#soufu_detail').hide();
        }
      });
    });
    </script>
</body>
</html>
