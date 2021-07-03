<div class="table-responsive">
	<table class="table table-striped table-hover table-md">
		<tr>
			<th class="text-center">商品番号</th>
			<th class="">商品名</th>
			<th class="text-center">産地</th>
			<th class="text-center">在庫数</th>
			<th class="text-center">特記事項</th>
			<th class="text-center">金額</th>
			<th width="180px" class="text-center">納品先店舗</th>
			<th width="80px" class="text-center">規格</th>
			<th width="100px" class="text-center">数量</th>
			<th width="80px" class="text-center">単位</th>
			<th width="120px" class="text-center">納品予定日</th>
			<th width="80px" class="text-center">小計</th>
			<th width="140px" class="text-center">操作</th>
		</tr>
		@foreach($carts as $cart)
		<tr id="{{$cart->id}}">
			<td class="cartid_{$cart->id}} text-center">{{$cart->item->item_id}}</td>
			<td class="cartid_{$cart->id}}">{{$cart->item->item_name}}</td>
			<td class="cartid_{$cart->id}} text-center">{{$cart->item->sanchi_name}}</td>
			<td class="cartid_{$cart->id}} text-center">{{$cart->item->zaikosuu}}</td>
			<td class="cartid_{$cart->id}} text-center">{{$cart->item->tokkijikou}}</td>
			<td class="cartid_{$cart->id}} teika text-center">
				<input name="teika[]" class="teika text-center form-control" value="5000" readonly>
			</td>
			<td width="120px" colspan="7" class="order-table">
				<table class="table table-striped table-hover table-md">
				@foreach($cart->orders as $val)
					<tr id="{{$val->id}}">
						<td width="180px" class="text-center">
							<select name="store[]" class="store text-center form-control" value="{{$cart->store_id}}">
								@foreach($stores as $store)
								<option value="{{$store->store_id}}">{{$store->tokuisaki_name}} {{$store->store_name}}</option>
								@endforeach
								<option value="{{$store->store_id}}">全店舗に追加</option>
							</select>
						</td>
						<td width="80px" class="text-center">{{$cart->item->kikaku}}</td>
						<td width="100px" class="text-center">
							<select name="quantity[]" class="store text-center form-control" value="{{$cart->store_id}}">
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
							<div class="input-group">

								<input type="text" name="nouhinbi[]" class="nouhinbi text-center form-control daterange-cus datepicker" value="2021-06-26">
								<!-- <div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fas fa-calendar"></i>
									</div>
								</div> -->
							</div>
						</td>
						<td width="80px" class="total text-center"></td>
						<td width="140px" class="text-center">
							<button type="button" id="{{$val->id}}" class="removeid_{{$val->id}} removeorder btn btn-info">削除</button>
							<button style="margin-top:10px;" type="button" id="{{$cart->item->id}}" class="cloneid_{{$cart->item->id}} clonecart btn btn-success">配送先を追加</button>
						<input name="item_id[]" type="hidden" value="{{$cart->item->id}}" />
						</td>
					</tr>
				@endforeach
				</table>
			</td>
		</tr>
		@endforeach
	</table>
</div>

<script>
// 取引詳細画面でオーダー内容を取得
function dealorder_update() {
					$.ajax({
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							}, //Headersを書き忘れるとエラーになる
							type: 'POST', //リクエストタイプ
							data: {
								'item_id': item_id,
							}, //Laravelに渡すデータ
							url: location.origin + '/dealorder',
							cache: false, // キャッシュしないで読み込み
							// 通信成功時に呼び出されるコールバック
							success: function (data) {
										$('#dealorder').html(data);
							},
							// 通信エラー時に呼び出されるコールバック
							error: function () {
									// alert("Ajax通信エラー");
							}
					});
	}

// 個数入力画面を開いたらオーダー内容を取得
$(document).ready( function(){
setTimeout(dealorder_update);
});
</script>
