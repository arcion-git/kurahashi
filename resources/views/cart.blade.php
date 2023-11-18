<div class="table-responsive">
	<table class="table table-striped cart_ajax">
		<tbody>
			<tr class="cart_header pc">
				<th class="cart_item_name">商品名</th>
				<th class="cart_zaikosuu">在庫数</th>
				<th class="cart_tani">単位</th>
				<th class="cart_sousa">操作</th>
			</tr>
      @foreach($carts as $cart)
			<tr>
				<td class="cart_item_name">{{$cart->item->item_name}}</td>
				<td class="cart_zaikosuu pc">{{$cart->item->zaikosuu}}</td>
				<td class="cart_tani pc">
					@if ($cart->item->tani == 1)
					ｹｰｽ
					@elseif ($cart->item->tani == 2)
					ﾎﾞｰﾙ
					@elseif ($cart->item->tani == 3)
					個
					@elseif ($cart->item->tani == 4)
					Kg
					@endif
				</td>
				<td class="cart_sousa"><button id="{{$cart->id}}" class="removecart removeid_{{$cart->item->id}} btn btn-info">削除</button></td>
			</tr>
      @endforeach
		</tbody>
	</table>
</div>
