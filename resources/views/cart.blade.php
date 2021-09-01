<div class="table-responsive">
	<table class="table table-striped">
		<tbody>
			<tr>
				<th>商品名</th>
				<th>在庫数</th>
				<th>単位</th>
				<th>操作</th>
			</tr>
      @foreach($carts as $cart)
			<tr>
				<td>{{$cart->item->item_name}}</td>
				<td>{{$cart->item->zaikosuu}}</td>
				<td>
					@if ($cart->item->tani == 1)
					ｹｰｽ
					@elseif ($cart->item->tani == 2)
					ﾎﾞｰﾙ
					@elseif ($cart->item->tani == 3)
					ﾊﾞﾗ
					@elseif ($cart->item->tani == 4)
					Kg
					@endif
				</td>
				<td><button id="{{$cart->id}}" class="removecart removeid_{{$cart->item->id}} btn btn-info">削除</button></td>
			</tr>
      @endforeach
		</tbody>
	</table>
</div>
