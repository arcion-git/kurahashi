{{ $name }} 様<br />
<br />
{!! $text !!}
<br />
@if(isset($order_list)){!! $order_list !!}<br /><br />@endif
@if(isset($user))
  @if($user->setonagi == 1)
    @if(isset($shipping_price_text)){!! $shipping_price_text !!}<br /><br />@endif
    @if(isset($total_price)){!! $total_price !!}<br /><br />@endif
    @if(isset($total_price_zei_number_text)){!! $total_price_zei_number_text !!}<br /><br />@endif
    @if(isset($shipping_price_zei_number_text)){!! $shipping_price_zei_number_text !!}<br /><br />@endif
    @if(isset($pay)){!! $pay !!}<br /><br />@endif
    @if(isset($uketori_place)){!! $uketori_place !!}<br /><br />@endif
    @if(isset($nouhin_store)){!! $nouhin_store !!}<br /><br />@endif
    @if($deal->first_order_nouhin_yoteibi())
      @if(isset($nouhin_yoteibi)){!! $nouhin_yoteibi !!}<br /><br />@endif
    @endif
    @if(isset($uketori_time)){!! $uketori_time !!}<br /><br />@endif
  @endif
@endif
@if(isset($nouhin_store)){!! $nouhin_store !!}<br /><br />@endif
@if(isset($user))
  @if(!$user->setonagi == 1)
    @if(isset($nouhin_yoteibi)){!! $nouhin_yoteibi !!}<br /><br />@endif
  @endif
@endif
@if(isset($memo)){!! $memo !!}<br /><br />@endif
引き続き、SETOnagiのご利用を心よりお待ちしております。<br />
<br />
=============================================<br />
ありすぎる情報と品揃えの時代<br />
【探す手間】を市場が解消します。<br />
■SETOnagi サイト<br />
株式会社クラハシグループ<br />
株式会社 U-midas<br />
〒729-0324<br />
広島県三原市糸崎7丁目8番22号<br />
事業者登録番号:T6240001039693<br />
URL:https://setonagi-orderbook.com/<br />
お問い合わせ:info@setonagi-orderbook.com<br />
<br />
株式会社クラハシ 公式 HP<br />
https://www.kurahashi.co.jp/<br />
=============================================
