<div class="table-responsive">
	<table class="table table-striped table-hover table-md">
		<tr>
			<th class="head text-center">商品番号</th>
			<th class="head">商品名</th>
			<th class="head text-center">産地</th>
			<th class="head text-center">在庫数</th>
			<th class="head text-center">特記事項</th>
			<th width="120px" class="head text-center">金額</th>
			<th width="180px" class="head text-center">納品先店舗</th>
			<th width="80px" class="head text-center">規格</th>
			<th width="100px" class="head text-center">数量</th>
			<th width="80px" class="head text-center">単位</th>
			<th width="120px" class="head text-center">納品予定日</th>
			<th width="80px" class="head text-center">小計</th>
			<th width="140px" class="head text-center">操作</th>
		</tr>
		@foreach($carts as $cart)
		<tr id="{{$cart->id}}">
			<input name="item_id[]" type="hidden" value="{{$cart->item->id}}" />
			<td class="cartid_{$cart->id}} text-center">{{$cart->item->item_id}}</td>
			<td class="cartid_{$cart->id}}">{{$cart->item->item_name}}</td>
			<td class="cartid_{$cart->id}} text-center">{{$cart->item->sanchi_name}}</td>
			<td class="cartid_{$cart->id}} text-center">{{$cart->item->zaikosuu}}</td>
			<td class="cartid_{$cart->id}} text-center">{{$cart->item->tokkijikou}}</td>
			<td width="120px" colspan="8" class="order-table">
				<table class="table table-striped table-hover table-md">
				@foreach($cart->orders as $val)
					<tr id="{{$val->id}}">
						<td width="120px" class="text-center">
							<input name="price[]" class="price text-center form-control" value="{{$val->price}}"
							@if ( Auth::guard('user')->check() ) readonly @endif>
						</td>
						<td width="180px" class="text-center">
							<select name="store[]" class="store text-center form-control" value="{{$val->tokuisaki_name}} {{$val->store_name}}">
								<option id="{{$val->tokuisaki_name}}" value="{{$val->store_name}}">{{$val->tokuisaki_name}} {{$val->store_name}}</option>
								@foreach($stores as $store)
								<option id="{{$store->tokuisaki_name}}" value="{{$store->store_name}}">{{$store->tokuisaki_name}} {{$store->store_name}}</option>
								@endforeach
								<option value="">全店舗に追加</option>
							</select>
						</td>
						<td width="80px" class="text-center">{{$cart->item->kikaku}}</td>
						<td width="100px" class="text-center">
							<select name="quantity[]" class="quantity text-center form-control" value="{{$val->quantity}}">
								@if($val->quantity == 1)
								@elseif($val->quantity)
								<option value="{{$val->quantity}}">{{$val->quantity}}</option>
								@endif
								@for ($i = 1; $i <= $cart->item->zaikosuu; $i++)
								<option value="{{$i}}">{{$i}}</option>
								@endfor
							</select>
						</td>
						<td width="80px" class="text-center">
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
						<td width="120px" class="text-center">
								<input type="text" name="nouhin_yoteibi[]" class="nouhin_yoteibi text-center form-control daterange-cus datepicker" value="{{$val->nouhin_yoteibi}}">
						</td>
						<td width="80px" class="total text-center"></td>
						<td width="140px" class="text-center">
							<button type="button" id="{{$val->id}}" class="removeid_{{$val->id}} removeorder btn btn-info">削除</button>
							<button style="margin-top:10px;" type="button" id="{{$cart->item->id}}" class="cloneid_{{$cart->item->id}} clonecart btn btn-success">配送先を追加</button>
						<input name="order_id[]" class="order_id" type="hidden" value="{{$val->id}}" />
						</td>
					</tr>
				@endforeach
				</table>
			</td>
		</tr>
		@endforeach
	</table>
</div>




@if(isset( $holidays ))
<script>
$('.datepicker').datepicker({
	format: 'yyyy-mm-dd',
	autoclose: true,
	assumeNearbyYear: true,
	language: 'ja',
	startDate: '+2d',
	endDate: '+31d',
	defaultViewDate: Date(),
	datesDisabled: [
	@foreach($holidays as $holiday)
	'{{$holiday}}',
	@endforeach
	]
});
</script>
@endif
