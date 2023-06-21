

@if(!$user->setonagi)
<div class="table-responsive" id="nouhin_store_nouhin_yoteibi">
	<div class="section-title">納品先・納品日</div>
	<table id="{{$user->id}}" class="user_id table table-striped table-hover table-md cart-wrap">
		<tr>
			<th class="">納入先店舗</th>
			<th class="">
				<select id="change_all_store" name="change_all_store" class="change_all_store text-center form-control" value="" readonly>
					<option id="@if(isset($set_order)){{$set_order->tokuisaki_name}}@endif" value="@if(isset($set_order)){{$set_order->store_name}}@endif">@if(isset($set_order)){{$set_order->tokuisaki_name}} {{$set_order->store_name}}@endif</option>
					@foreach($stores as $store)
						<option id="@if(isset($set_order)){{$store->tokuisaki_name}}@endif" value="{{$store->store_name}}">{{$store->tokuisaki_name}} {{$store->store_name}}</option>
					@endforeach
					<input type="hidden" name="set_tokuisaki_name" value="@if(isset($set_order)){{$set_order->tokuisaki_name}}@endif" id="set_tokuisaki_name" />
				</select>
			</th>
		</tr>
		<tr>
			<th class="">納品予定日</th>
			<td class="text-center">
					<input id="change_all_nouhin_yoteibi" type="text" name="change_all_nouhin_yoteibi" class="change_all_nouhin_yoteibi text-center form-control daterange-cus datepicker" value="@if(isset($set_order)){{$set_order->nouhin_yoteibi}}@endif" autocomplete="off" required readonly>
					@if($user->kyuujitu_haisou == 1)
					<script>
					$('.change_all_nouhin_yoteibi').datepicker({
						format: 'yyyy-mm-dd',
						autoclose: true,
						assumeNearbyYear: true,
						language: 'ja',
						startDate: '{{$sano_nissuu}}',
						endDate: '{{$all_nouhin_end}}',
					});
					</script>
					@else
					<script>
					$('.change_all_nouhin_yoteibi').datepicker({
						format: 'yyyy-mm-dd',
						autoclose: true,
						assumeNearbyYear: true,
						language: 'ja',
						startDate: '{{$sano_nissuu}}',
						endDate: '{{$all_nouhin_end}}',
						defaultViewDate: Date(),
						datesDisabled: [
						@foreach($holidays as $holiday)
						'{{$holiday}}',
						@endforeach
					],
					});
					</script>
					@endif
			</td>
			</th>
		</tr>
	</table>
</div>
@endif

<div class="table-responsive mt-4">
	<div class="section-title">オーダー内容</div>
		<div id="cartAccordion">
				    <table id="cartHeader" class="table table-striped table-hover table-md cart-wrap">
				        <tr id="order_header">
				            <th class="head-item-id head text-center">商品番号</th>
				            <th class="head-item-name head">商品名</th>
				            <th class="head-sanchi head text-center">産地</th>
				            <th class="head-zaikosuu head text-center">在庫数</th>
				            <!-- <th class="head text-center">特記事項</th> -->
				            <th class="head-price head text-center">金額</th>
				            @if(!$user->setonagi)
				                <th class="head-store head text-center">納品先店舗</th>
				            @endif
				            <th class="head-kikaku head text-center">規格</th>
				            <th class="head-quantity head text-center">数量</th>
				            <th class="head-tani head text-center">単位</th>
				            @if(!$user->setonagi)
				                <th class="head-yoteibi head text-center">納品予定日</th>
				            @endif
				            <th class="head-shoukei head text-center">小計</th>
				            <th class="head-sousa head text-center">操作</th>
				        </tr>
				    </table>
				    @foreach($groupedItems as $text => $carts)
				    <div class="accordion cartAccordion" id="cartAccordion{{ $loop->index }}">
				        <!-- <div class="card"> -->
				        <div class="">
				            <div class="card-header groupe_button" id="heading{{ $loop->index }}" data-toggle="collapse" data-target="#collapse{{ $loop->index }}" aria-expanded="true" aria-controls="collapse{{ $loop->index }}">
				                <h2 class="mb-0">
				                    <button class="btn btn-link" type="button" onclick="toggleAccordion(this)">
				                        <span id="collapse-icon-{{ $text }}">-</span> {{ $text }}
				                    </button>
				                </h2>
				            </div>
				            <div id="collapse{{ $loop->index }}" class="collapse show" aria-labelledby="heading{{ $loop->index }}" data-parent="#cartAccordion{{ $loop->index }}">
				                <div class="">
				                    <table id="{{$user->kaiin_number}}" class="table table-striped table-hover table-md cart-wrap">
															@foreach($carts as $cart)
															<tr id="{{$cart->id}}" class="cart_item" data-addtype="{{$cart->addtype}}">
																<input name="item_id[]" type="hidden" value="{{$cart->item->id}}" />
																<td class="head-item-id cartid_{{$cart->id}} text-center">{{$cart->item->item_id}}</td>
																<td class="head-item-name cartid_{{$cart->id}}">
																	@if($cart->addtype == 'addbuyerrecommend' && !$user->setonagi)
																		{{$cart->uwagaki_item_name}}
																	@else
																		{{$cart->item->item_name}}
																	@endif


																</td>
																<td class="head-sanchi cartid_{{$cart->id}} text-center">
																	@if(isset($cart->item->sanchi_name))
																		{{$cart->item->sanchi_name}}
																	@else
																	@endif
																</td>
																<td class="head-zaikosuu cartid_{{$cart->id}} text-center">{{$cart->item->zaikosuu}}</td>
																<!-- <td class="cartid_{$cart->id}} text-center">{{$cart->item->tokkijikou}}</td> -->
																<td colspan="8" class="order-table">
																	<table class="table table-striped table-hover table-md">
																	@foreach($cart->orders as $val)
																		<tr id="{{$val->id}}" class="order_item">
																			<td class="head-price text-center">

																				<!-- BtoB金額表示 -->
																				@if(!$user->setonagi)
																					@if($cart->hidden_price() == '1')
																					<!-- 担当のおすすめ商品の価格非表示が選択されている場合 -->
																					<input name="price[]" pattern="^[0-9]+$" class="price text-center form-control" data-price="未定" value="未定" @if ( Auth::guard('user')->check() ) readonly @endif>
																					@else
																					<!-- BtoB通常金額表示 -->
																					<input name="price[]" pattern="^[0-9]+$" class="price text-center form-control" data-price="@if($val->price=='未定'){{(0)}}@else{{ $val->price }}@endif" value="@if($val->price=='未定')未定@else{{ number_format($val->price)}}@endif"
																					@if ( Auth::guard('user')->check() ) readonly @endif>
																					@endif
																				@endif
																				<!-- BtoSB金額表示 -->
																				@if($user->setonagi)
																				<input name="price[]" pattern="^[0-9]+$" class="price text-center form-control" data-price="{{$val->price}}" value="{{number_format($val->price)}}"
																				@if ( Auth::guard('user')->check() ) readonly @endif>
																				@endif
																			</td>

																			@if(!$user->setonagi)
																			<td class="head-store text-center">


																				<select name="store[]" class="store text-center form-control" value="{{$val->tokuisaki_name}} {{$val->store_name}}" required>
																					<option id="{{$val->tokuisaki_name}}" value="{{$val->store_name}}">{{$val->tokuisaki_name}} {{$val->store_name}}</option>

																					<!-- 該当する得意先店舗のみが選べるように -->

																						@if($cart->stores())
																							<?php $store_lists = $cart->stores()?>
																							@foreach($store_lists as $store)
																								<option id="{{$store->tokuisaki_name}}" value="{{$store->store_name}}">{{$store->tokuisaki_name}} {{$store->store_name}}</option>
																							@endforeach
																							<!-- <option value="all_store">全店舗に追加</option> -->
																						@endif


																				</select>
																			</td>
																			@endif

																			<td class="head-kikaku text-center">
																				@if($cart->addtype == 'addbuyerrecommend' && !$user->setonagi)
																					{{$cart->uwagaki_kikaku}}
																				@else
																					{{$cart->item->kikaku}}
																				@endif
																			</td>
																			<td class="head-quantity text-center">
																				<select name="quantity[]" class="quantity text-center form-control" value="{{$val->quantity}}" required>
																					@if(isset($deal))
																						<option value="{{$val->quantity}}">{{$val->quantity}}</option>
																					@else
																						@if($val->quantity == 1)
																						<option value="{{$val->quantity}}">{{$val->quantity}}</option>
																						@elseif($val->quantity)
																						<option value="{{$val->quantity}}">{{$val->quantity}}</option>
																						@endif
																					@endif
																						@for ($i = 0; $i <= $cart->item->zaikosuu; $i++)
																						@endfor
																						@for ($i = 0; $i <= $cart->zaikosuu(); $i++)
																						<option value="{{$i}}">{{$i}}</option>
																						@endfor
																				</select>
																			</td>
																			<td class="head-tani text-center">
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
																			@if(!$user->setonagi)
																			<td class="head-yoteibi text-center">
																					<input type="text" name="nouhin_yoteibi[]" class="nouhin_yoteibi nouhin_yoteibi_{{$cart->id}} text-center form-control daterange-cus datepicker" value="{{$val->nouhin_yoteibi}}" autocomplete="off" required>
																					@if($user->kyuujitu_haisou == 1)
																					<script>
																					$('.nouhin_yoteibi_{{$cart->id}}').datepicker({
																						format: 'yyyy-mm-dd',
																						autoclose: true,
																						assumeNearbyYear: true,
																						language: 'ja',
																						startDate: '{{$sano_nissuu}}',
																						endDate: '{{$cart->nouhin_end()}}',
																						// endDate: '@if($cart->nouhin_end()){{$cart->nouhin_end()}}@else +31d @endif',
																					});
																					</script>
																					@else
																					<script>
																					$('.nouhin_yoteibi_{{$cart->id}}').datepicker({
																						format: 'yyyy-mm-dd',
																						autoclose: true,
																						assumeNearbyYear: true,
																						language: 'ja',
																						startDate: '{{$sano_nissuu}}',
																						endDate: '{{$cart->nouhin_end()}}',
																						// endDate: '@if($cart->nouhin_end()){{$cart->nouhin_end()}}@else +31d @endif',
																						defaultViewDate: Date(),
																						datesDisabled: [
																						@foreach($holidays as $holiday)
																						'{{$holiday}}',
																						@endforeach
																					],
																					});
																					</script>
																					@endif
																			</td>
																			@endif
																			<td class="head-shoukei total text-center"></td>
																			<td class="head-sousa text-center">
																				<button type="button" id="{{$val->id}}" class="removeid_{{$val->id}} removeorder btn btn-info">削除</button>
																				@if(!$user->setonagi)
																				<button style="margin-top:10px;" type="button" id="{{$cart->item->id}}" class="cloneid_{{$cart->item->id}} clonecart btn btn-success">配送先を追加</button>
																				@endif
																			</td>
																			<input name="order_id[]" class="order_id" type="hidden" value="{{$val->id}}" />
																		</tr>
																	@endforeach
																	</table>
																</td>
															</tr>
															@endforeach
				                    </table>
				        				</div>
				    				</div>
								</div>
						</div>
						@endforeach
				</div>
	</div>
</div>




@if(!$user->setonagi)
<div class="row mt-4">
  <div class="col-md-12">
    <div class="section-title">任意の商品</div>
    <div class="table-responsive" id="nini-wrap">
      <table id="{{$user->kaiin_number}}" class="table table-striped table-hover table-md">
        <tbody>
					<tr id="nini_header">
	          <th class="head-nini_item_name text-center">商品名</th>
	          <th class="head-nini_tantou text-center">担当</th>
	          <th class="head-store text-center">納品先店舗</th>
	          <th class="head-quantity text-center">数量（単位）</th>
	          <th class="head-yoteibi text-center">納品予定日</th>
	          <th class="head-sousa text-center">操作</th>
	        </tr>
					@foreach($cart_ninis as $cart_nini)
					<tr width="" id="{{$cart_nini->id}}" class="cart_nini_item">
						<input name="cart_nini_id[]" type="hidden" value="{{$cart_nini->id}}" />
						<td class="cart_nini_id_{$cart_nini->id}} head-nini_item_name">
							<input name="nini_item_name[]" class="nini_item_name form-control" value="{{$cart_nini->item_name}}" required>
						</td>
						<td class="head-nini_tantou text-center">
							<select name="nini_tantou[]" class=" nini_tantou text-center form-control" value="{{$cart_nini->tantou_name}}" required>
								<option value="{{$cart_nini->tantou_name}}">{{$cart_nini->tantou_name}}</option>
								<option value="青物">青物</option>
								<option value="太物">太物</option>
								<option value="近海">近海</option>
								<option value="養魚">養魚</option>
								<option value="特殊">特殊</option>
								<option value="水産">水産</option>
							</select>
						</td>
						<td colspan="5" class="order-table">
							<table class="table table-striped table-hover table-md">
							@foreach($cart_nini->order_ninis as $val)
								<tr id="{{$val->id}}">
									<td class="head-store text-center">
										<select name="nini_store[]" class="nini_store text-center form-control" value="{{$val->tokuisaki_name}} {{$val->store_name}}" required>
											<option id="{{$val->tokuisaki_name}}" value="{{$val->store_name}}">{{$val->tokuisaki_name}} {{$val->store_name}}</option>

											@foreach($stores as $store)
												<option id="{{$store->tokuisaki_name}}" value="{{$store->store_name}}">{{$store->tokuisaki_name}} {{$store->store_name}}</option>
											@endforeach
											<option value="all_store_nini">全店舗に追加</option>
										</select>
									</td>
									<td class="head-quantity text-center">
										<input name="nini_quantity[]" class="nini_quantity text-center form-control" value="{{$val->quantity}}" required>
									</td>
									<td class="head-yoteibi text-center">
											<input type="text" name="nini_nouhin_yoteibi[]" class="nini_nouhin_yoteibi text-center form-control daterange-cus datepicker" value="{{$val->nouhin_yoteibi}}" autocomplete="off" required>
									</td>
									<td class="head-sousa text-center">
										<button type="button" id="{{$val->id}}" class="removeid_{{$val->id}} removeordernini btn btn-info">削除</button><br />
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
    <button style="min-width:200px;" type="button" name="" id="@if(isset($deal)){{$deal->id}}@endif" class="addniniorder btn btn-success"><i class="fas fa-plus"></i> 任意の商品を追加</button>
    </div>
  </div>
</div>
@endif



@if($user->setonagi)
<div class="row mt-4 order">
	<div class="col-md-12">
		<div class="section-title">商品の受け渡し・お支払い方法</div>
	</div>
</div>
<div class="row">
	<div class="form-group col-12">
		<div class="row">
			<div class="col-sm-12 col-md-2">
					<label for="company">ご注文者名</label>
			</div>
			<div class="col-sm-12 col-md-5">
					<label for="">{{$user->name}}</label>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-2">
					<label for="company">ご注文者住所</label>
			</div>
			<div class="col-sm-12 col-md-5">
					<label for="">{{$setonagi->address02}}{{$setonagi->address03}}{{$setonagi->address04}}<br />
					<small>※商品の受け取りに取りに来られない場合は、上記ご住所に配送させていただきます。</small></label>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-2">
					<label for="company">受け渡し場所</label>
			</div>
			<div class="col-sm-12 col-md-5">
				<select id="uketori_place" value="" name="uketori_place" class="uketori_place form-control" required>
				@if(isset($deal->uketori_place))
				<option value="{{$deal->uketori_place}}" selected>{{$deal->uketori_place}}</option>
				@else
				<option value="" selected>選択してください</option>
				@endif
				<!-- <option value="福山魚市引き取り">福山魚市引き取り</option> -->
				<option value="引取り（マリンネクスト）">三原引き取り（マリンネクスト）</option>
				<option value="引取り（尾道ケンスイ）">尾道引取り（ケンスイ）</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-2">
					<label for="company">受け渡し希望時間</label>
			</div>
			<div class="col-sm-12 col-md-5">
				<select id="uketori_time" value="" name="uketori_time" class="uketori_time form-control" required>
				@if(isset($deal->uketori_time))
				<option value="{{$deal->uketori_time}}" selected>{{$deal->uketori_time}}</option>
				@else
				<option value="" selected>選択してください</option>
				@endif
				<option value="午前中">午前中</option>
				<option value="12時〜14時">12時〜14時</option>
				<option value="14時〜16時">14時〜15時</option>
				<!-- <option value="16時〜18時">16時〜18時</option> -->
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-2">
					<label for="company">お支払い方法</label>
			</div>
			<div class="col-sm-12 col-md-10">

				@if ( Auth::guard('user')->check() )
					@if ( Auth::guard('user')->user()->setonagi()->kakebarai_riyou == 1 )
						<input required class="radio-input uketori_siharai_radio" type="radio" id="クロネコかけ払い" value="クロネコかけ払い" name="uketori_siharai" @if($deal->uketori_siharai == 'クロネコかけ払い') checked @endif ><label for="クロネコかけ払い"> クロネコかけ払い</label>
					@endif
				@endif

				@if ( Auth::guard('admin')->check() )
					<input required class="radio-input uketori_siharai_radio" type="radio" id="クロネコかけ払い" value="クロネコかけ払い" name="uketori_siharai" @if($deal->uketori_siharai == 'クロネコかけ払い') checked @endif ><label for="クロネコかけ払い"> クロネコかけ払い</label>
				@endif

				<input required class="radio-input uketori_siharai_radio" type="radio" id="クレジットカード払い" value="クレジットカード払い" name="uketori_siharai" @if($deal->uketori_siharai == 'クレジットカード払い') checked @endif><label for="クレジットカード払い"> クレジットカード払い</label>
				<input type="hidden" name="token_api" id="token_api" value="{{app('request')->input('token_api')}}"/>
				<div class="invalid-feedback">
				</div>

			</div>
		</div>

		<input type="hidden" name="setonagi_id" value="{{$setonagi->id}}"/>
		</div>
	</div>
</div>
@endif



@if($user->kyuujitu_haisou == 1)
<script>
$('.nini_nouhin_yoteibi').datepicker({
	format: 'yyyy-mm-dd',
	autoclose: true,
	assumeNearbyYear: true,
	language: 'ja',
	startDate: '{{$sano_nissuu}}',
	endDate: '+31d',
});
</script>
@else
<script>
$('.nini_nouhin_yoteibi').datepicker({
	format: 'yyyy-mm-dd',
	autoclose: true,
	assumeNearbyYear: true,
	language: 'ja',
	startDate: '{{$sano_nissuu}}',
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

<!-- <div id="item_total" class="total_price"></div>
<div id="tax" class="total_price">税：</div>
<div id="all_total" class="total_price">税込金額：</div> -->
<script>
$(document).ready( function(){
    // update_field();
    var target = $('.total').map(function (index, el) {
		var total = $(this).closest('tr').find('input.price').data('price') * $(this).closest('tr').find('select.quantity').val();
    $(this).closest('tr').find('.total').text(total);
		});
    // console.log(target);
    var sum = 0;
    $('.total').each(function () {
        sum += parseInt(this.innerText);
        var item_total = $('#item_total').map(function (index, el) {
        $(this).text("¥ "+ sum.toLocaleString() );});

        var all_total = $('#all_total').map(function (index, el) {
        var all_total = sum * 108 / 100;
        var all_total = Math.round(all_total);
				$("#all_total_val").val(all_total);
        $(this).text("¥ "+ all_total.toLocaleString());});

        var tax = $('#tax').map(function (index, el) {
        var tax = sum * 108 / 100 - sum;
        var tax = Math.round(tax);
				$("#tax_val").val(tax);
        $(this).text("¥ "+ tax.toLocaleString());});
    });
});
</script>


<div class="row mt-4">
	<div class="col-lg-8">
	  <div class="section-title">通信欄</div>
	    <textarea id="memo" style="height:250px; width:500px;" name="memo" rows="10" value="@if(isset($deal)){{$deal->memo}}@elseif(isset($user->memo)){{$user->memo}}@endif" class="form-control selectric" maxlength="374" onchange="Limit(event)" onkeyup="Limit(event)">@if(isset($deal)){{$deal->memo}}@elseif(isset($user->memo)){{$user->memo}}@endif</textarea>
			<p class="memo_note">※通信欄は「内容確認画面に進む」を押すと保存されます。<br />確認画面に進む直前に通信欄の入力をしてください。</p>
	</div>
	@if($user->setonagi)
  <div class="col-lg-4 text-right">
    <div class="invoice-detail-item">
      <div class="invoice-detail-name">合計</div>
      <div id="item_total" class="invoice-detail-value"></div>
    </div>
    <div class="invoice-detail-item">
      <div class="invoice-detail-name">税額</div>
      <div id="tax" class="invoice-detail-value"></div>
			<input id="tax_val" type="hidden" name="tax_val" value="" />
    </div>
    <hr class="mt-2 mb-2">
    <div class="invoice-detail-item">
      <div class="invoice-detail-name">税込合計額</div>
      <div id="all_total" class="invoice-detail-value invoice-detail-value-lg"></div>
			<input id="all_total_val" type="hidden" name="all_total_val" value="" />
    </div>
  </div>
	@endif
</div>


<!-- 発注済、キャンセルの場合操作ができないようにする -->
@if(Auth::guard('user')->check())
	@if(isset($deal))
		@if($deal->status == '発注済' or $deal->status == 'キャンセル')
	<script>
	  $(function(){
			$('input').attr('readonly',true);
			$('select').attr('readonly',true);
			$('select').addClass('arrow_hidden');
			$("select[readonly] > option:not(:selected)").attr('disabled', 'disabled');
			$('textarea').attr('readonly',true);
			$('input[type="radio"]').prop('disabled', true);
			$('select').attr("disabled", true);
			$('.datepicker').attr("disabled", true);
			$('.head-sousa').remove();
			$('.addniniorder').remove();
	  });
	</script>
		@endif
	@endif
@endif

<!-- 発注済、キャンセルの場合操作ができないようにする -->
@if(Auth::guard('admin')->check())
	@if(isset($deal))
		@if($deal->status == '発注済' or $deal->status == 'キャンセル')
	<script>
	  $(function(){
			$('input[type="radio"]').prop('disabled', true);
	  });
	</script>
		@endif
	@endif
@endif



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
