@extends('layouts.lp')

@section('content')

  <section class="section welcome">
    <div class="section-header">
      <h1>
        ご利用方法
      </h1>
    </div>
    <div class="section-body guide">

      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">


              <div class="entry-content">
              <h2>１．SETOnagiオーダーブックについて</h2>
              <p>量販店・小売店向けの商品から、SETOnagiオリジナル商品まで、目利きの選んだ確かな全国の水産商品をご提供してまります。</p>
              <ul>
              <li>ハーフネットショッピング形式　～ ネットで簡単注文・お店でお受け取り～</li>
              <li>ご注文の翌日受取り(水・日・祝は休業日となります。)</li>
              <li>最低注文ロット・お取引金額の制限無し　1回1個のご注文から対応</li>
              <li>架け払いでのお取引もご利用可能</li>
              </ul>
              <p>当サイトは法人会員様限定のサイトになります。<br>
              万が一法人ではないお客様がご登録された場合は、ご利用を停止させていただきます。</p>
              <h2>２．会員登録の流れ</h2>
              <ol>
              <li>5分ほどの簡単登録手続き<br>社名・屋号、お名前、ご住所、電話番号等の簡単手続き<br>
              　　　　↓<br>
              ヤマト架け払い審査 審査は半日～(最大で1営業日)<br>
              　　　　↓</li>
              <li>審査結果をメールにてご連絡<br>
              　　　　↓</li>
              <li>SETOnagiのユーザー登録完了 ＝ ご利用開始<br>
              ※ヤマト架け払いサービスご利用可能なお客様が会員条件となります。<br>
              　審査はヤマトクレジットファイナンス社によりますので、ご要望に添えない場合がございます。<br>
              ※スマートフォンからお手続きの方は、「@setonagi.net」からのメールを受信できるように設定をお願いいたします。</li>
              </ol>
              <h2>３．ご注文の手順</h2>
              <ol>
              <li>登録時に発行されたＩＤ、パスワードでログインをしてください。</li>
              <li>商品のご購入は、トップ画面の「商品一覧」「商品検索」からご注文ください。</li>
              <li>ご希望の商品をクリックし、必要数量を入力していただき、「カートに入れる」をクリックしてください。</li>
              <li>購入を続けられる場合は、「買い物を続ける」、購入を終わられる場合は、「次へ」をクリックしてください。</li>
              <li>「次へ」の場合は、「配送時のご住所」「支払い方法」を選択、「受取希望時間」を入力していただき、確認の上「注文する」をクリックしてください。</li>
              <li>ご登録メールアドレスに、ご注文内容を記載したメールをお送りしますのでご確認ください。<br>
              ※スマートフォンからお手続きの方は、「@setonagi.net」からのメールを受信できるように設定をお願いいたします。</li>
              </ol>
              <p class="red">日祝日並びに水曜日（全国市場休日）お休みです。ご注文は休みの間も受け付けておりますが、出荷は休み明けとなりますのでご注意ください。<a title="福山地方卸売市場_2021市場休日カレンダー" href="https://setonagi.net/wp-content/uploads/2021/12/２０２２年休市カレンダー.pdf" target="_blank" rel="noopener">カレンダーはこちら</a></p>
              <h2>４．商品のお引渡し日について</h2>
              <ul>
              <li>商品のお引渡しは、原則ご注文日の翌営業日となります。</li>
              <li>土・日・祝・市場休日の前日17：00以降～土・日・祝・市場休日にいただいたご注文は、翌々営業日(月曜又は 祝日休日明け)のお引渡しとなります。</li>
              <li><span class="red">本日の仕入れ商品は、火・金・土曜日限定</span>の市場受け渡しとなります。</li>
              </ul>
              <table class="otodoke_table" border="2">
              <tbody>
              <tr style="background-color: #dceaf6;">
              <td style="text-align: center;" width="20%"><strong>ご注文時間</strong></td>
              <td style="text-align: center;" width="40%"><strong>お引渡し日</strong></td>
              </tr>
              <tr>
              <td><strong>17:00まで</strong></td>
              <td style="background-color: #f2f2f2;">翌営業日 市場引き渡し</td>
              </tr>
              <tr>
              <td><strong>17:00以降</strong></td>
              <td style="background-color: #f2f2f2;">翌々営業日 市場引き渡し</td>
              </tr>
              </tbody>
              </table>
              <p>&nbsp;<br>
              <span class="red">日曜祝祭日並びに水曜日<a href="https://setonagi.net/wp-content/uploads/2021/12/２０２２年休市カレンダー.pdf" target="_blank" rel="noopener">（市場休日カレンダーはこちら）</a>等の市場休日については、注文は受け付けておりますが、出荷並びに市場渡しは休み明けとなりますのでご注意ください。</span></p>
              <h2 id="haisou">５．商品のお引渡しについて</h2>
              <p>ご注文商品のお引渡し場所は、ご注文時に選択肢からご指定ください。<br>
              ※ お引渡し受付時間が異なりますので、ご注意ください。</p>
              <p>① お荷物のお受け取りにお越しいただきましたら、各所の受取カウンターにてお申し付け下さい。<br>
              ② 受付担当者に、SETOnagiの商品お引き取りの旨と、お名前、メールにてご案内いたします「ご注文番号」をお伝えください。<br>
              ※ご指定の日時にお越しいただけない場合は、下記お問合せまでご連絡をお願いいたします。</p>
              <p>◇ ご注文時にご指定のお時間にお引き取りにいらっしゃらない場合は、当日にヤマト運輸の宅急便にて、ご登録住所へ商品を郵送させていただきます。<br>
              ◇ 郵送でのご納品の場合は、1個口につき、2,380円の配送手数料がかかります。</p>
              <h2>６．お引渡し場所について</h2>

              <h3>マリンネクスト (三原市糸崎) <span class="red">受け取り可能時間：9時～15時</span></h3>
              <p>〒729-0324 広島県三原市糸崎９丁目４<br>
              お引渡受付時間　9:00～15:00（水・日・祝除く）<br>
              TEL：084-863-6660</p>
              <p><iframe loading="lazy" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3292.7084462717876!2d133.11255571495488!3d34.38333490765722!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x355055c3a8c6db03%3A0xe8d6bd85d5e50ef9!2z44Oe44Oq44Oz44ON44Kv44K544OI!5e0!3m2!1sja!2sjp!4v1648661000311!5m2!1sja!2sjp" width="100%" height="300" frameborder="0" allowfullscreen="allowfullscreen" aria-hidden="false"></iframe></p>

              <h3>株式会社ケンスイ（尾道市東尾道）<span class="red">受け取り可能時間：8時～17時</span></h3>
              <p>〒722-0051 広島県尾道市東尾道7番地2<br>
              お引渡受付時間　8:00～17:00（水・日・祝除く）<br>
              TEL：0848-46-3515</p>
              <p><iframe style="border: 0;" tabindex="0" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d13165.576922219221!2d133.2363559!3d34.41674!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x761df09f92408f2f!2z77yI5qCq77yJ44Kx44Oz44K544Kk!5e0!3m2!1sja!2sjp!4v1611846239024!5m2!1sja!2sjp" width="100%" height="300" frameborder="0" allowfullscreen="allowfullscreen" aria-hidden="false"></iframe></p>

              <!-- <h3>福山魚市場（福山市引野町　福山引き取り窓口 ㈱クラハシ）<span class="red">受け取り可能時間：9時～16時</span></h3>
              <p>〒721-0942 広島県福山市引野町1-1-1 福山市地方卸売市場内<br>
              お引渡受付時間　9:00～16:00（水・日・祝除く）<br>
              TEL：084-941-3510</p>
              <p><iframe loading="lazy" style="border: 0;" tabindex="0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3288.29273894009!2d133.401007915404!3d34.49546210168986!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x35511154f17b0dbd%3A0x498780d753ce4861!2z44CSNzIxLTA5NDIg5bqD5bO255yM56aP5bGx5biC5byV6YeO55S677yR5LiB55uu77yR4oiS77yR!5e0!3m2!1sja!2sjp!4v1629160088292!5m2!1sja!2sjp" width="100%" height="300" frameborder="0" allowfullscreen="allowfullscreen" aria-hidden="false"></iframe></p> -->

              <p>※お引き渡し場所は随時追加をいたします。</p>
              <h2><strong>７．交換・返品について</strong></h2>
              <ul style="list-style-type: square;">
              <li>ご注文のキャンセルや商品の交換・返品は、食品を取り扱っている為<strong><span style="color: #ff0000;">当社が認めた不良品以外は原則お引き受けできません。</span></strong></li>
              <li>やむを得ない場合ご注文のキャンセル、修正等に関しましては、<span style="color: #ff0000;"><strong>当日17：00までとさせていただきます。「受注確認メール」が届く前に必ずご連絡くださいますようお願いいたします。</strong></span>発送後のご対応は出来ませんのでご注意ください。</li>
              <li>お客様側の事情による受取辞退・保管期限切れによる返品などについても受け付けておりません。</li>
              <li>SETOnagiの商品は運送会社の規定により保管期限が5日間となっております。</li>
              <li>保管期限が過ぎますと、運送会社より商品が強制返品され、商品は廃棄処理となります。その場合はご返金も出来ませんので、予めご了承ください。</li>
              <li>返品につきましては、商品に異常が認められる場合や、配送途中での変形や破損等が発生した場合、間違った商品が届いた場合に限り、弊社の費用負担で返品をお受け致します。<strong><span style="color: #ff0000;">（商品の箱及び中身は破棄せずそのままの状態で保管してください。現物がない場合は、交換は出来かねます。）</span></strong><strong><span style="color: #ff0000;"> 商品到着後3日以内にお申し出ください。</span></strong></li>
              </ul>
              <p>&nbsp;</p>
              <div id="kessai"></div>
              <h2><strong>８．お支払方法について</strong></h2>
              <ul style="list-style-type: square;">
              <li>商品のお支払いは、掛け払いとクレジット会計からお選びになれます。</li>
              <li>クレジット会計をご指定の場合、ご利用限度額、引落日等お支払の諸条件につきましては、ご利用のクレジットカードのご契約条件に準じます。</li>
              </ul>
              <h3 id="kakebarai">ヤマト掛け払い決済</h3>
              <p>法人または個人事業主様の後払いのご要望にお応えするため、クロネコヤマトを利用した決済サービスのことです。ご購入代金をヤマトグループのヤマトクレジットファイナンス株式会社が集金代行いたします。<br>
              <img loading="lazy" class="alignnone wp-image-4305" src="https://setonagi.net/wp-content/uploads/2021/08/掛け払い１-1.jpg" alt="" width="747" height="344" srcset="https://setonagi.net/wp-content/uploads/2021/08/掛け払い１-1.jpg 1723w, https://setonagi.net/wp-content/uploads/2021/08/掛け払い１-1-300x138.jpg 300w, https://setonagi.net/wp-content/uploads/2021/08/掛け払い１-1-1024x471.jpg 1024w, https://setonagi.net/wp-content/uploads/2021/08/掛け払い１-1-768x353.jpg 768w, https://setonagi.net/wp-content/uploads/2021/08/掛け払い１-1-1536x706.jpg 1536w" sizes="(max-width: 747px) 100vw, 747px"></p>
              <h4>掛け払い決済のメリット</h4>
              <p><img loading="lazy" class="alignnone wp-image-4303" src="https://setonagi.net/wp-content/uploads/2021/08/掛け払い２-1.jpg" alt="" width="734" height="186" srcset="https://setonagi.net/wp-content/uploads/2021/08/掛け払い２-1.jpg 1449w, https://setonagi.net/wp-content/uploads/2021/08/掛け払い２-1-300x76.jpg 300w, https://setonagi.net/wp-content/uploads/2021/08/掛け払い２-1-1024x258.jpg 1024w" sizes="(max-width: 734px) 100vw, 734px"></p>
              <h4>掛け払い商品発送とお支払いの流れ</h4>
              <p><img loading="lazy" class="alignnone wp-image-4306" src="https://setonagi.net/wp-content/uploads/2021/08/掛け払い３.jpg" alt="" width="753" height="374" srcset="https://setonagi.net/wp-content/uploads/2021/08/掛け払い３.jpg 1394w, https://setonagi.net/wp-content/uploads/2021/08/掛け払い３-300x149.jpg 300w, https://setonagi.net/wp-content/uploads/2021/08/掛け払い３-1024x508.jpg 1024w, https://setonagi.net/wp-content/uploads/2021/08/掛け払い３-768x381.jpg 768w" sizes="(max-width: 753px) 100vw, 753px"></p>
              <h4>掛け払いご利用にあたって</h4>
              <ul style="list-style-type: square;">
              <li><strong><span style="color: #ff0000;">法人・個人事業主のお客様が対象となります</span></strong>。<strong><span style="color: #ff0000;">一般個人のお客様は対象外</span></strong>となります。</li>
              <li>所定の審査によりご利用条件等ご希望に添えない場合がございます。あらかじめご了承ください。</li>
              <li>即日審査で初回からご利用可能です。（審査の回答は、最短5分〜最長２営業日程度）審査が通った場合は、ご登録のご住所宛に「会員登録完了書」が郵送されます。</li>
              <li>審査が通らなかった場合は、他の決済方法に変更するなどのご対応が必要となりますので、あらかじめご了承ください。</li>
              <li><span style="color: #ff0000;"><strong>当サイトの会員登録が必要になります。（登録料・年会費無料）</strong></span>会員登録後、マイページにログインしていただき『掛け払いサービス』よりお申し込みの手続きをお願いいたします。</li>
              </ul>
              <h3>クレジットカード決済</h3>
              <p>SETOnagiサイトでは取り扱いカードは以下のとおりです。[一括払い][分割払い][リボ払い]から選べます。</p>
              <p><img loading="lazy" class="alignnone wp-image-3242" src="https://setonagi.net/wp-content/uploads/2021/08/cardbrand.png" alt="" width="818" height="327" srcset="https://setonagi.net/wp-content/uploads/2021/08/cardbrand.png 750w, https://setonagi.net/wp-content/uploads/2021/08/cardbrand-300x120.png 300w" sizes="(max-width: 818px) 100vw, 818px"></p>
              <h4>クレジットカードご利用にあたって</h4>
              <ul style="list-style-type: square;">
              <li>入力されたクレジットカードのご利用状況によってはご購入いただけない場合があります。</li>
              <li>クレジットカード払いの決済日は、発送した日より一週間以内となります。</li>
              <li>注文日ではありませんのでご注意ください。代金のご精算はお客様とクレジット会社の契約によります。</li>
              <li>お引落し日などのご不明な点は、各クレジット会社に直接お問い合せください。なお、クレジットカード会社の締日等により精算が決済日の翌月になる場合がございますのでご了承ください。</li>
              </ul>
              <h2><strong>９．領収書について</strong></h2>
              <ul style="list-style-type: square;">
              <li>クレジット決済の場合のみ対応可能です。領収書が必要な際は、注文時の【備考欄】に「領収書希望」「ご名義」「但し書き」をご入力ください。</li>
              <li>ご登録メールアドレス宛にPDFにて送付させて頂きます。</li>
              <li>&nbsp;紙面での領収書発行は対応しておりませんので、予めご了承下さい。</li>
              </ul>
              <!-- <h2><strong>１０．賞味期限について</strong></h2>
              <p>いずれの商品も、裏面に【賞味期限】を記載しておりますのでご確認ください。</p> -->
              <h2><strong>１０．お問い合わせ</strong></h2>
              <p>SETOnagiについて、会員登録方法について、ご不明な点等ございましたら、下記までお問い合わせください。<br>
              お問い合わせフォームからは、24時間受け付けております。(ご回答までに1営業日頂く場合がございます。)<br>
              <!-- 【お問い合わせ】<br>
              TEL: 084-941-3510<br>
              受付時間: 9:00-12:30、13:30-17:00（水・日・祝休み）</p>
              <p>運営：株式会社クラハシ<br>
              〒721-0942 広島県福山市引野町１丁目１−１<br> -->
              EMAIL：<a href="mailto:info@setonagi.net">info@setonagi.net</a><br />
              <!-- URL：<a href="https://www.kurahashi.co.jp/">https://www.kurahashi.co.jp/</a></p> -->
              </div>


            </div>
          </div>
        </div>
    </div>
  </section>

@endsection
