{{ $name }} 様<br />
<br />
{!! $text !!}
<br />
@if(isset($order_list)){!! $order_list !!}<br /><br />@endif
@if(isset($shipping_price_text)){!! $shipping_price_text !!}<br /><br />@endif
@if(isset($total_price)){!! $total_price !!}<br />@endif
@if(isset($zei_price_text)){!! $zei_price_text !!}<br />@endif
@if(isset($total_price_zei_number_text)){!! $total_price_zei_number_text !!}<br />@endif
@if(isset($shipping_price_zei_number_text)){!! $shipping_price_zei_number_text !!}<br /><br />@endif
@if(isset($user))
@if($user->setonagi == 1)
@if(isset($pay)){!! $pay !!}<br /><br />@endif
@if(isset($uketori_place)){!! $uketori_place !!}<br /><br />@endif
@if(isset($nouhin_store)){!! $nouhin_store !!}<br /><br />@endif
@if(isset($nouhin_yoteibi)){!! $nouhin_yoteibi !!}<br /><br />@endif
@if(isset($uketori_time)){!! $uketori_time !!}<br /><br />@endif
@endif
@if(!$user->setonagi == 1)
@if(isset($nouhin_store)){!! $nouhin_store !!}<br /><br />@endif
@if(isset($nouhin_yoteibi)){!! $nouhin_yoteibi !!}<br /><br />@endif
@endif
@endif
@if(isset($memo)){!! $memo !!}<br /><br />@endif
引き続き、SETOnagiのご利用を心よりお待ちしております。<br />
<br />
=============================================<br />
ありすぎる情報と品揃えの時代<br />
【探す手間】を市場が解消します。<br />
SETOnagi オーダーブック<br />
株式会社クラハシ<br />
〒721-0942<br />
広島県福山市引野町1丁目1−1<br />
事業者登録番号：T6240001039693<br />
URL:https://setonagi-orderbook.com/<br />
公式HP:https://www.kurahashi.co.jp/<br />
お問い合わせ:info@setonagi.net<br />
=============================================
