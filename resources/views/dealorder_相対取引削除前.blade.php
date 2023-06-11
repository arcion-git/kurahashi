

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



<div class="row mt-4">
  <div class="col-md-12">
    <div class="section-title">任意の商品</div>
    <div class="table-responsive">
      <table class="table table-striped table-hover table-md">
        <tbody><tr>
          <th width="200px" class="text-center">商品名</th>
          <th width="140px" class="text-center">担当</th>
          <th width="100px" class="text-center">納品先店舗</th>
          <th width="100px" class="text-center">数量（単位）</th>
          <th width="60px" class="text-center">納品予定日</th>
          <th width="80px" class="text-center">操作</th>
        </tr>
				@foreach($cart_ninis as $cart_nini)
				<tr width="" id="{{$cart_nini->id}}">
					<input name="cart_nini_id[]" type="hidden" value="{{$cart_nini->id}}" />
					<td width="200px" class="cart_nini_id_{$cart_nini->id}}">
						<input name="nini_item_name[]" class="nini_item_name form-control" value="{{$cart_nini->item_name}}">
					</td>
					<td width="140px" class="text-center">
						<select name="nini_tantou[]" class="nini_tantou text-center form-control" value="{{$cart_nini->tantou_name}}">
							<option value="{{$cart_nini->tantou_name}}">{{$cart_nini->tantou_name}}</option>
							<option value="鮮魚">鮮魚</option>
							<option value="青物">青物</option>
							<option value="太物">太物</option>
							<option value="近海">近海</option>
							<option value="特殊">特殊</option>
							<option value="養魚">養魚</option>
							<option value="水産">水産</option>
						</select>
					</td>
					<td width="200px" colspan="5" class="order-table">
						<table class="table table-striped table-hover table-md">
						@foreach($cart_nini->order_ninis as $val)
							<tr id="{{$val->id}}">
								<td width="180px" class="text-center">
									<select name="nini_store[]" class="nini_store text-center form-control" value="{{$val->tokuisaki_name}} {{$val->store_name}}">
										<option id="{{$val->tokuisaki_name}}" value="{{$val->store_name}}">{{$val->tokuisaki_name}} {{$val->store_name}}</option>
										@foreach($stores as $store)
										<option id="{{$store->tokuisaki_name}}" value="{{$store->store_name}}">{{$store->tokuisaki_name}} {{$store->store_name}}</option>
										@endforeach
										<option value="">全店舗に追加</option>
									</select>
								</td>
								<td width="100px" class="text-center">
									<input name="nini_quantity[]" class="nini_quantity text-center form-control" value="{{$val->quantity}}">
								</td>
								<td width="120px" class="text-center">
										<input type="text" name="nini_nouhin_yoteibi[]" class="nini_nouhin_yoteibi text-center form-control daterange-cus datepicker" value="{{$val->nouhin_yoteibi}}" autocomplete="off">
								</td>
								<td width="80px" class="total text-center"></td>
								<td width="140px" class="text-center">
									<button type="button" id="{{$val->id}}" class="removeid_{{$val->id}} removeordernini btn btn-info">削除</button>
									<button style="margin-top:10px;" type="button" id="{{$cart_nini->id}}" class="cloneid_{{$cart_nini->id}} addordernini btn btn-success">配送先を追加</button>
								</td>
							</tr>
						@endforeach
						</table>
					</td>
				</tr>
				@endforeach
      </tbody>
		</table>
    <button style="min-width:200px;" type="button" name="" id="" class="addniniorder btn btn-success"><i class="fas fa-plus"></i> 任意の商品を追加</button>
    </div>
  </div>
	<div class="col-md-12">
    <div class="section-title">通信欄</div>
      <textarea style="height:250px; width:500px;" name="memo" rows="10" value="@if(isset($deal)){{$deal->memo}}@endif" class="form-control selectric">@if(isset($deal)){{$deal->memo}}@endif</textarea>
  </div>
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
