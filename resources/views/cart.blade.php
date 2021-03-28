<div class="table-responsive">
	<table class="table table-striped">
		<tbody>
			<tr>
				<th>商品名</th>
				<th>価格</th>
				<th>個数</th>
				<th>操作</th>
			</tr>
      @foreach($carts as $cart)
			<tr>
				<td>{{$cart->item->item_name}}</td>
				<td>{{$cart->item->teika}}</td>
				<td>{{$cart->quantity}}</td>
				<td><button id="{{$cart->item->id}}" class="removecart removeid_{{$cart->item->id}} btn btn-info">削除</button></td>
			</tr>
      @endforeach
		</tbody>
	</table>
</div>
