{{ $name }} 様<br />
<br />
{!! $text !!}
<br />
@if(isset($order_list)){!! $order_list !!}<br /><br />@endif
@if(isset($total_price)){!! $total_price !!}<br /><br />@endif
@if(isset($user))
@if($user->setonagi == 1)
@if(isset($pay)){!! $pay !!}<br /><br />@endif
@if(isset($uketori_place)){!! $uketori_place !!}<br /><br />@endif
@if(isset($nouhin_yoteibi)){!! $nouhin_yoteibi !!}<br /><br />@endif
@if(isset($uketori_time)){!! $uketori_time !!}<br /><br />@endif
@endif
@endif
@if(isset($memo)){!! $memo !!}<br /><br />@endif
引き続き、SETOnagiのご利用を心よりお待ちしております。<br />
<br />
=============================================<br />
ありすぎる情報と品揃えの時代<br />
【探す手間】を市場が解消します。<br />
SETOnagi オーダーブック<br />
株式会社U-midas（ウミダス）<br />
〒729-0324<br />
広島県三原市糸崎7丁目8番22号<br />
URL:http://setonagi.net<br />
お問い合わせ:info@setonagi.net<br />
=============================================
