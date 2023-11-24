


@if(request()->input('addtype') == 'addbuyerrecommend' && $user->setonagi)
@else
<div class="table-responsive" id="nouhin_store_nouhin_yoteibi">
	@if(!$user->setonagi)
	<div class="section-title">納品先・納品日</div>
	@elseif(request()->input('addtype') === 'addbuyerrecommend')
		@if($url == 'confirm')
			<div class="section-title">お気に入り表示</div>
		@endif
	@endif
	<table id="{{$user->id}}" class="user_id table table-striped table-hover table-md cart-wrap">
		@if(!$user->setonagi)
		<tr>
			<th class="">納入先店舗</th>
			<th class="">
				<select id="change_all_store" name="change_all_store" class="change_all_store text-center form-control" value="" required @if(isset($deal)) readonly disabled @endif>
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

					<input id="change_all_nouhin_yoteibi" type="text" name="change_all_nouhin_yoteibi" class="change_all_nouhin_yoteibi text-center form-control daterange-cus datepicker" value="@if(isset($set_order)){{$set_order->nouhin_yoteibi}}@endif" autocomplete="off" required @if(isset($deal)) readonly disabled @endif>
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
		@endif
		@if(request()->input('addtype') === 'addbuyerrecommend' && $url == 'confirm')
		<tr>
			<th class="">お気に入り商品のみを表示</th>
			<td class="text-center">
					@if(request()->input('show_favorite') == true)
		      <input type="checkbox" id="show_favorite" name="show_favorite" value="1" checked>
					@else
		      <input type="checkbox" id="show_favorite" name="show_favorite" value="1">
					@endif
		      <label class="show_favorite_label" for="show_favorite">お気に入り商品のみを表示</label>
			</td>
		</tr>
		@endif
	</table>
</div>
@endif




<div id="@if(isset($deal)){{$deal->id}}@endif" class="deal_id table-responsive mt-4">
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



						@if(isset($groupedItems))
				    @foreach($groupedItems as $text => $carts)
				    <div class="accordion cartAccordion" id="cartAccordion{{ $loop->index }}">
				        <!-- <div class="card"> -->
								@foreach($carts as $cart)
									@if($cart->addtype == 'addsetonagi' || $cart->addtype == 'addrepeatorder')
									<style>
									.card-header{
										display: none;
									}
									#cartAccordion {
									    border-bottom: 0px solid #bfc1c3;
									}
									.cartAccordion{
									    border-bottom: 1px solid #bfc1c3;
									}
									</style>
									@break
									@endif
								@endforeach
				        <div class="">
				            <div class="card-header groupe_button" id="heading{{ $loop->index }}" data-toggle="collapse" data-target="#collapse{{ $loop->index }}" aria-expanded="true" aria-controls="collapse{{ $loop->index }}">
				                <h2 class="mb-0">
				                    <button class="btn btn-link" type="button" onclick="toggleAccordion(this)">
				                        <span id="collapse-icon-{{ $text }}">-</span> {{ $text }}
				                    </button>
				                </h2>
				            </div>
				            <div id="collapse{{ $loop->index }}" class="collapse @if($url == 'approval' || isset($deal) || request()->input('addtype') === 'addsetonagi') show @endif" aria-labelledby="heading{{ $loop->index }}" data-parent="#cartAccordion{{ $loop->index }}">
				                <div class="">
				                    <table id="{{$user->kaiin_number}}" class="table table-striped table-hover table-md cart-wrap">


															@foreach($carts as $cart)

															<!-- お気に入り商品表示非表示 -->
															@if(!isset($show_favorite) && ($cart->addtype == 'addbuyerrecommend' || $cart->addtype == 'addsetonagi' || $cart->addtype == 'addrepeatorder' || $cart->addtype == 'addspecialprice') || (isset($show_favorite) && ($cart->favoriteitem())))

															<!-- 最終確認画面で在庫がある商品のみ -->

															@if(!isset($deal) && $cart->addtype == 'addbuyerrecommend' && $cart->zaikosuu == 0)
															@else

															@if($url == 'approval' && $cart->order_this())
															@else

															<!-- カートに1つでも商品があるか、BtoBユーザーか確認 -->
															@if(!isset($set_order) && !$user->setonagi)
															@else

															<!-- BtoB担当のおすすめ商品 -->
															<!-- 在庫管理のある商品を表示 -->


															<!-- 納品先の得意先IDと、担当のおすすめ商品の得意先IDが一致するか確認 -->
															<tr id="{{$cart->id}}" class="cart_item" data-addtype="{{$cart->addtype}}">
																<input name="cart_id[]" type="hidden" value="{{$cart->id}}" />
																<td class="head-item-id cartid_{{$cart->id}} text-center">{{$cart->item->item_id}}</td>
																<td class="head-item-name cartid_{{$cart->id}}">
																	@if($cart->uwagaki_item_name)
																		{{$cart->uwagaki_item_name}}
																		@else
																		{{$cart->item->item_name}}
																	@endif
																	@if(!isset($deal))
																		@if($cart->addtype == 'addbuyerrecommend' && !$user->setonagi)
																			@if($cart->favoriteitem())
				                              <span name="item_id" value="{{$cart->item->id}}" id="{{$cart->item->id}}" class="favoritebutton removefavoriteitem"><i class="fa fa-heart"></i></span>
				                              @else
				                              <span name="item_id" value="{{$cart->item->id}}" id="{{$cart->item->id}}" class="favoritebutton addfavoriteitem"><i class="far fa-heart"></i></span>
				                              @endif
																		@endif
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
																	 @if($val)
																		<tr id="{{$val->id}}" class="order_item">
																			<td class="head-price text-center">
																				<span class="sp">¥</span>
																				<!-- BtoB金額表示 -->
																				<?php
																				if ($val->price == '未定') {
																						$price_val = '未定';
																				} elseif ($val->price == '-') {
																						$price_val = '-';
																				} else {
																						if (Auth::guard('admin')->check()) {
																								$price_val = $val->price;
																						} else {
																								$price_val = number_format($val->price);
																						}
																				}
																				?>
																					@if(!$user->setonagi)
																						<!-- BtoB通常金額表示 -->
																						<input name="price[]" pattern="^[0-9]+$" class="price text-center form-control" data-price="@if($val->price=='未定'){{(0)}}@else{{ $val->price }}@endif" value="<?php echo $price_val ?>" @if(isset($deal) && Auth::guard('admin')->check()) @else readonly @endif>
																					@else
																						<!-- BtoC通常金額表示 -->
																						<input name="price[]" pattern="^[0-9]+$" class="price text-center form-control" data-price="@if($val->price=='未定'){{(0)}}@else{{ $val->price }}@endif" value="<?php echo $price_val ?>"  @if(isset($deal) && Auth::guard('admin')->check()) @else readonly @endif>
																					@endif
																			</td>

																			@if(!$user->setonagi)
																			<td class="head-store text-center">


																				<select name="store[]" class="store text-center form-control" value="{{$val->tokuisaki_name}} {{$val->store_name}}" required>
																					<option id="{{$val->tokuisaki_name}}" value="{{$val->store_name}}">{{$val->tokuisaki_name}} {{$val->store_name}}</option>

																					<!-- 該当する得意先店舗のみが選べるように -->

																						@if($stores)

																							@foreach($stores as $store)
																								<option id="{{$store->tokuisaki_name}}" value="{{$store->store_name}}">{{$store->tokuisaki_name}} {{$store->store_name}}</option>
																							@endforeach
																							<!-- <option value="all_store">全店舗に追加</option> -->
																						@endif


																				</select>
																			</td>
																			@endif

																			<td class="head-kikaku text-center">
																				@if($cart->uwagaki_kikaku)
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

																					@if($cart->addtype == 'addbuyerrecommend')
																						@for ($i = 0; $i <= $cart->zaikosuu; $i++)
																						<option value="{{$i}}">{{$i}}</option>
																						@endfor
																					@else
																						@for ($i = 0; $i <= $cart->item->zaikosuu; $i++)
																						<option value="{{$i}}">{{$i}}</option>
																						@endfor
																					@endif

																				</select>
																			</td>
																			<td class="head-tani text-center">
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
																		{{--	@if(!$user->setonagi)
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
																			 --}}
																			@if($user->setonagi)
																			<td class="head-shoukei total text-center"></td>
																			<input name="order_id[]" class="order_id" type="hidden" value="{{$val->id}}" />
																			@endif
																			{{-- <td class="head-sousa text-center">
																				<button type="button" id="{{$val->id}}" class="removeid_{{$val->id}} removeorder btn btn-info">削除</button>
																				@if(!$user->setonagi)
																				<button style="margin-top:10px;" type="button" id="{{$cart->item->id}}" class="cloneid_{{$cart->item->id}} clonecart btn btn-success">配送先を追加</button>
																				@endif
																			</td>
																		</tr> --}}
																		@break
																		@endif
																	@endforeach
																	</table>
																</td>
															</tr>

															@endif
															@endif
															@endif
															@endif
															@endforeach
				                    </table>
				        				</div>
				    				</div>
								</div>
						</div>
						@endforeach
						@endif
				</div>
	</div>
</div>





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
					<label for="company"><strong>ご注文者名</strong></label>
			</div>
			<div class="col-sm-12 col-md-5">
					<label for="">{{$user->name}}</label>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-2">
					<label for="company"><strong>ご注文者住所</strong></label>
			</div>
			<div class="col-sm-12 col-md-5">
					<label for="">{{$setonagi->address02}}{{$setonagi->address03}}{{$setonagi->address04}}<br />
					<small>※商品の受け取りに取りに来られない場合は、上記ご住所に配送させていただきます。</small></label>
			</div>
		</div>

		@if(isset($setonagi->shipping_code))
		<div class="row">
			<div class="col-sm-12 col-md-2">
					<label for="company"><strong>配送方法</strong></label>
			</div>
			<div class="col-sm-12 col-md-5">
				<select id="uketori_place" value="@if(isset($deal) && isset($deal->uketori_place)){{$deal->uketori_place}}@elseif(isset($setonagi) && isset($setonagi->uketori_place)){{$setonagi->uketori_place}}@endif" name="uketori_place" class="c_uketori_place form-control">
				@if(isset($deal))
					<option id="set_uletori_place" value="{{$deal->uketori_place}}" selected>{{$deal->c_uketori_houhou()}}</option>
				@else
					@if(isset($setonagi->uketori_place))
						<option id="set_uletori_place" value="{{$setonagi->uketori_place}}" selected>{{$setonagi->shipping_name()}}</option>
					@else
						<!-- <option value="" selected>選択してください</option> -->
					@endif
						@if(isset($shipping_settings))
							@foreach($shipping_settings as $shipping_setting)
								<option value="{{$shipping_setting->shipping_method}}">{{$shipping_setting->shipping_name}}</option>
							@endforeach
						@else
					@endif
				@endif
				</select>
			</div>
		</div>

		<div id="c_shipping_date" class="row">
			<div class="col-sm-12 col-md-2">
					<label for="company"><strong>受け渡し希望日</strong></label>
			</div>
			<div class="col-sm-12 col-md-5">
				<input type="text" id="change_all_nouhin_yoteibi" name="change_all_nouhin_yoteibi" class="nouhin_yoteibi_c form-control daterange-cus datepicker" value="@if(isset($deal)){{$set_order->nouhin_yoteibi}}@else{{$nouhin_yoteibi}}@endif" autocomplete="off" onkeydown="return event.key != 'Enter';" readonly >
				<script>
				$('.nouhin_yoteibi_c').datepicker({
					format: 'yyyy-mm-dd',
					autoclose: true,
					assumeNearbyYear: true,
					language: 'ja',
					startDate: '{{$sano_nissuu}}',
					endDate: '+7d',
          setDate: null,
					// endDate: '@if($cart->nouhin_end()){{$cart->nouhin_end()}}@else +31d @endif',
					// defaultViewDate: Date(),
					datesDisabled: [
					@foreach($holidays as $holiday)
					'{{$holiday}}',
					@endforeach
				],
				});
				</script>
			</div>
		</div>
		<div id="c_shipping_time" class="row">
			<div class="col-sm-12 col-md-2">
					<label for="company"><strong>受け渡し希望時間</strong></label>
			</div>
			<div class="col-sm-12 col-md-5">
				<select id="uketori_time" value="" name="uketori_time" class="uketori_time form-control">
					@if(isset($deal))
						<option value="{{$deal->uketori_time}}" selected>{{$deal->uketori_time}}</option>
					@else
						@if(isset($setonagi->uketori_time))
						<option value="{{$setonagi->uketori_time}}" selected>{{$setonagi->uketori_time}}</option>
						@else
						@endif
					@endif
						<option value="午前中">午前中</option>
						<option value="12時〜14時">12時〜14時</option>
						<option value="14時〜16時">14時〜16時</option>
						<option value="16時〜17時">16時〜17時</option>
				</select>
			</div>
		</div>

		@else
		<div class="row">
			<div class="col-sm-12 col-md-2">
					<label for="company"><strong>受け渡し場所</strong></label>
			</div>
			<div class="col-sm-12 col-md-5">
				<select id="uketori_place" value="" name="uketori_place" class="uketori_place form-control" required>
				@if(isset($deal))
					<option value="{{$deal->uketori_place}}" selected>{{$deal->uketori_place}}</option>
				@else
					@if(isset($setonagi->uketori_place))
					<option value="{{$setonagi->uketori_place}}" selected>{{$setonagi->uketori_place}}</option>
					@else
					<option value="" selected>選択してください</option>
					@endif
				@endif
				<!-- <option value="福山魚市引き取り">福山魚市引き取り</option> -->
				<option value="三原引き取り（マリンネクスト）">三原引き取り（マリンネクスト）</option>
				<option value="尾道引取り（ケンスイ）">尾道引取り（ケンスイ）</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-2">
					<label for="company"><strong>受け渡し希望時間</strong></label>
			</div>
			<div class="col-sm-12 col-md-5">
				<select id="uketori_time" value="" name="uketori_time" class="uketori_time form-control" required>
				@if(isset($deal))
					<option value="{{$deal->uketori_time}}" selected>{{$deal->uketori_time}}</option>
				@else
					@if(isset($setonagi->uketori_time))
					<option value="{{$setonagi->uketori_time}}" selected>{{$setonagi->uketori_time}}</option>
					@else
					<option value="" selected>選択してください</option>
					@endif
				@endif
					<option value="午前中">午前中</option>
					<option value="12時〜14時">12時〜14時</option>
					<option value="14時〜16時">14時〜16時</option>
					<option value="16時〜17時">16時〜17時</option>
				</select>
			</div>
		</div>
		@endif


		<div class="row">
			<div class="col-sm-12 col-md-2">
					<label for="company"><strong>お支払い方法</strong></label>
			</div>
			<div class="col-sm-12 col-md-10">


				@if ( Auth::guard('user')->check() )
					@if ( Auth::guard('user')->user()->setonagi()->kakebarai_riyou == 1 )
						<input required class="radio-input uketori_siharai_radio" type="radio" id="クロネコかけ払い" value="クロネコかけ払い" name="uketori_siharai" @if($setonagi->uketori_siharai == 'クロネコかけ払い') checked @endif ><label for="クロネコかけ払い"> クロネコかけ払い</label>
					@endif
				@endif

				@if ( Auth::guard('admin')->check() )
					@if ( $user->setonagi()->kakebarai_riyou == 1 )
					<input required class="radio-input uketori_siharai_radio" type="radio" id="クロネコかけ払い" value="クロネコかけ払い" name="uketori_siharai" @if($setonagi->uketori_siharai == 'クロネコかけ払い') checked @endif ><label for="クロネコかけ払い"> クロネコかけ払い</label>
					@endif
				@endif

					<input required class="radio-input uketori_siharai_radio" type="radio" id="クレジットカード払い" value="クレジットカード払い" name="uketori_siharai" @if($setonagi->uketori_siharai == 'クレジットカード払い') checked @endif @if(isset($shipping_code)) checked @endif><label for="クレジットカード払い"> クレジットカード払い</label>
					<input type="hidden" name="token_api" id="token_api" value="{{app('request')->input('token_api')}}"/>
					<div class="invalid-feedback">
					</div>
			</div>
		</div>





		<!-- クレジットカード払いが選択されたら表示 -->
		@if($user->setonagi & Auth::guard('user')->check() )
		<div id="pay_card">
			<div class="row mt-4">
				<div class="col-md-12">
					<div class="section-title">クレジットカード情報</div>
				</div>
			</div>
			<div class="form-group">
				<form method="POST" action="@if(isset($collect_touroku)){{$collect_touroku}}@endif" name="charge_form" class="charge_form" onsubmit="return false;">
					<div class="input-form row">
						<div class="col-sm-12 col-md-2">
							<label for="card_no">カード番号</label>
						</div>
						<div class="col-sm-12 col-md-5">
							<input type="text" class="form-control" name="card_no" maxlength="16" placeholder="************1234" value="" pattern="[0-9]*">
						</div>
					</div>
					<div class="input-form row">
						<div class="col-sm-12 col-md-2">
							<label>カード名義人</label>
						</div>
						<div class="col-sm-12 col-md-5">
							<input type="text" class="form-control" name="card_owner" maxlength="30" placeholder="KURONEKO TARO" value="">
						</div>
					</div>
					<div class="input-form row">
						<div class="col-sm-12 col-md-2">
							<label>カード有効期限</label>
						</div>
						<div class="col-sm-12 col-md-5">
							<input type="text" class="form-control yuukoukigen" name="exp_month" maxlength="2" placeholder="04" value="" pattern="[0-9]*">月 <input class="form-control yuukoukigen" type="text" name="exp_year" maxlength="2" value="" placeholder="28" pattern="[0-9]*">年
						</div>
					</div>
					<div class="input-form row">
						<div class="col-sm-12 col-md-2">
							<label>セキュリティコード</label>
						</div>
						<div class="col-sm-12 col-md-5">
							<input type="text" class="form-control" name="security_code" maxlength="4" placeholder="1234" value="" pattern="[0-9]*">
						</div>
					</div>
					<div class="input-form" style="display:none;">
						<input class="executePay" type="button" value="送信" onclick="executePay()">
					</div>
				</form>
			</div>
		</div>
		<input id="sano_nissuu" type="hidden" value="{{$sano_nissuu}}" />

		<!-- ライブラリ読み込み -->
		<script type="text/javascript" class="webcollect-embedded-token" src="@if(isset($collect_token)){{$collect_token}}@endif"></script>
		<script type="text/javascript">


		/*
		* 送信ボタン押下時に実行する JavaScript 関数
		*/
		function executePay() {


		$("#overlayajax").fadeIn(300);

		// function async_digestMessage(message) {
		// 	return new Promise(function(resolve){
		// 	var msgUint8 = new TextEncoder("utf-8").encode(message);
		// 	crypto.subtle.digest('SHA-256', msgUint8).then(
		// 		function(hashBuffer){
		// 				var hashArray = Array.from(new Uint8Array(hashBuffer));
		// 				var hashHex = hashArray.map(function(b){return b.toString(16).padStart(2, '0')}).join('');
		// 				return resolve(hashHex);
		// 		});
		// 	})
		// }
		// if(window.Promise && window.crypto){
		// 	async_digestMessage(text).then(
		// 			function(shatxt){
		// 					getHashText(shatxt);
		// 			}
		// 	).catch(function(e){
		// 			console.log('エラー：', e.message);
		// 	})
		// }else{
		// 	console.log('Promiseかcryptoに非対応');
		// }
		var text = '@if(isset($collect_password)){{$collect_password}}@endif';
		// var text = '@if(isset($collect_password_save)){{$collect_password_save}}@endif';

		// ハッシュ化された値を受け入れる非同期関数
		function async_digestMessage(hashValue) {
		  return new Promise(function (resolve) {
		    // サーバーからのハッシュ値を直接解決して返す
		    return resolve(hashValue);
		  });
		}

		// ブラウザがPromiseをサポートしているか確認
		if (window.Promise) {
		  // サーバーからのハッシュ値を非同期関数に渡し、結果を別の関数に渡す
		  async_digestMessage(text).then(
		    function (shatxt) {
		      getHashText(shatxt);
		    }
		  ).catch(function (e) {
		    // エラーが発生した場合、エラーメッセージをログに表示
		    console.log('エラー：', e.message);
		  })
		} else {
		  // サポートされていない場合、エラーメッセージをログに表示
		  console.log('Promiseに非対応');
		}

		function getHashText(text) {
		console.log(text);

		var elements = document.querySelectorAll('input[type="text"]'); Array.prototype.forEach.call(elements, function(element) {
		element.style.backgroundColor = "#fff"; });

		// コールバック関数(「正常」の場合)
		var callbackSuccess = function(response) {
		var formElement = document.charge_form;

		// カード情報がリクエストパラメータに含まれないようにする
		formElement.card_no.removeAttribute("name");
		formElement.card_owner.removeAttribute("name");
		formElement.exp_month.removeAttribute("name");
		formElement.exp_year.removeAttribute("name");
		formElement.security_code.removeAttribute("name");

		// form に発行したトークンを追加する
		var hiddenElement = document.createElement("input");
		hiddenElement.type = "hidden";
		hiddenElement.name = "webcollectToken";
		hiddenElement.value = response.token;
		formElement.appendChild(hiddenElement);

		var token_api = Object(response['token']);
		document.getElementById('token_api').value = token_api;
		// document.getElementById('addsuscess_btn').click();

		$(function(){
		    $("#approval_btn").click();
		});

		};

		// コールバック関数(「異常」の場合)
		var callbackFailure = function(response) {

		//エラー情報を取得
		var errorInfo = response.errorInfo;

		//errorItem の内容に応じてテキストボックスの背景色を変更する関数
		function changeColor(errorItem) {
		switch(errorItem) {
		case "cardNo":
		document.charge_form.card_no.style.backgroundColor = "#fdeef1";
		break;
		case "cardOwner":
		document.charge_form.card_owner.style.backgroundColor = "#fdeef1";
		break;
		case "cardExp":
		document.charge_form.exp_month.style.backgroundColor = "#fdeef1"; document.charge_form.exp_year.style.backgroundColor = "#fdeef1";
		break;
		case "securityCode":
		document.charge_form.security_code.style.backgroundColor = "#fdeef1";
		break; }
		}
		//フェードアウト
		$("#overlayajax").fadeOut(300);

		//エラーの数だけ処理を繰り返す
		for (var i = 0; i<errorInfo.length; i++) {
		if (errorInfo[i].errorItem) {
		changeColor(errorInfo[i].errorItem); }
			//メッセージを alert で出力
			alert(errorInfo[i].errorCode + " : " + errorInfo[i].errorMsg);
			if(errorInfo[i].errorCode === "Y021011302" || errorInfo[i].errorCode === "Y021011304" ){
				// security_code input要素にフォーカスを移動
				document.charge_form.security_code.focus();
			}
			if (errorInfo[i].errorCode === "Y021011105" || errorInfo[i].errorCode === "Y021011171" || errorInfo[i].errorCode === "Y021011105") {
			  // exp_month input要素にフォーカスを移動
			  document.charge_form.exp_month.focus();
			}
			if(errorInfo[i].errorCode === "Y021011004"){
				// card_owner input要素にフォーカスを移動
				document.charge_form.card_owner.focus();
			}
			if(errorInfo[i].errorCode === "Y021010904"){
				// card_no input要素にフォーカスを移動
				document.charge_form.card_no.focus();
			}
		}
		};

		// トークン発行 API へ渡すパラメータ
		var createTokenInfo = {
		traderCode: "@if(isset($collect_tradercode)){{$collect_tradercode}}@endif",
		authDiv: "2",
		// 保存しない場合
		optServDiv: "00",
		// 以下保存する場合
		// 追加
		// memberId: "@if(isset($user)){{$user->id}}@endif",
		// 追加
		// authKey: "test",
		// 保存する場合
		// optServDiv: "01",
		checkSum: text,
		cardNo: document.charge_form.card_no.value,
		cardOwner: document.charge_form.card_owner.value,
		cardExp: document.charge_form.exp_month.value + document.charge_form.exp_year.value,
		securityCode: document.charge_form.security_code.value
		};
		// webコレクトが提供する JavaScript 関数を実行し、トークンを発行する。
		WebcollectTokenLib.createToken(createTokenInfo, callbackSuccess, callbackFailure); }
		}



		</script>
		@endif



		<input type="hidden" name="setonagi_id" value="{{$setonagi->id}}"/>
		</div>
	</div>
</div>
@endif

@if(!$user->setonagi)
<div class="row mt-4">
  <div class="col-md-12">
    <div class="section-title">任意の商品</div>
    <div class="table-responsive" id="nini-wrap">
      <table id="{{$user->kaiin_number}}" class="table table-striped table-hover table-md">
        <tbody id="nini_tbody">
					<tr id="nini_header">
	          <th class="head-nini_item_name text-center">商品名</th>
	          <th class="head-nini_tantou text-center">担当</th>
	          <th class="head-store text-center">納品先店舗</th>
	          <th class="head-quantity text-center">数量</th>
	          <th class="head-yoteibi text-center">納品予定日</th>
	          <th class="head-sousa text-center">操作</th>
	        </tr>
					@foreach($cart_ninis as $cart_nini)
					<tr width="" id="{{$cart_nini->id}}" class="cart_nini_item">
						<input name="cart_nini_id[]" type="hidden" value="{{$cart_nini->id}}" />
						<td class="cart_nini_id_{{$cart_nini->id}} head-nini_item_name">
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
											<!-- <option value="all_store_nini">全店舗に追加</option> -->
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
		@if(isset($deal))
		@else
    <button style="min-width:200px;" type="button" name="" id="@if(isset($deal)){{$deal->id}}@endif" class="addniniorder btn btn-success"><i class="fas fa-plus"></i> 任意の商品を追加</button>
		@endif
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


<script>
$(document).ready(function () {
  updateFields();

  $('select.quantity').on('change', function () {
    updateFields();
  });

  function updateFields() {
    var sum = 0;


		if($('#c_shipping_price').is(':visible')) {
			var c_shipping_price = $("#c_shipping_price_val").val();
			$('.c_shipping_price').text('¥ ' + c_shipping_price);
			$('input[name="c_shipping_price"]').val(c_shipping_price);

			var shipping_price = $('#c_shipping_price_val').val();
			console.log(shipping_price);

			// 商品合計
			var sum = 0;
			$('.total').each(function () {
				var price = $(this).closest('tr').find('input.price').data('price');
				var quantity = $(this).closest('tr').find('select.quantity').val();
				var total = price * quantity;
				$(this).text(total);
				sum += total;
			});

			// 商品合計
			var itemTotal = sum.toLocaleString();
			$('#item_total').text('¥ ' + itemTotal);

			// 送料税込額
			var shipping_price_zei = Math.floor(shipping_price * 110 / 100);
			console.log(shipping_price_zei);

			// 送料税額
			var shipping_price_zei_only = shipping_price_zei - shipping_price;

			// 税込合計金額
			var allTotal = Math.floor(sum * 108 / 100) + shipping_price_zei;
			$('#all_total').text('¥ ' + allTotal.toLocaleString());
			$('#all_total_val').val(allTotal);

			// 税額
			var tax = Math.round(allTotal - sum - shipping_price);
			$('#tax').text('¥ ' + tax.toLocaleString());
			$('#tax_val').val(tax);

		}else{
	    $('.total').each(function () {
	      var price = $(this).closest('tr').find('input.price').data('price');
	      var quantity = $(this).closest('tr').find('select.quantity').val();
	      var total = price * quantity;
	      $(this).text(total);
	      sum += total;
	    });

			// 商品合計
	    var itemTotal = sum.toLocaleString();
	    $('#item_total').text('¥ ' + itemTotal);

			// 税込合計金額
	    var allTotal = Math.floor(sum * 108 / 100);
	    $('#all_total').text('¥ ' + allTotal.toLocaleString());
	    $('#all_total_val').val(allTotal);

			// 税額
	    var tax = Math.round(allTotal - sum);
	    $('#tax').text('¥ ' + tax.toLocaleString());
	    $('#tax_val').val(tax);
		}
  }
});
</script>


<div class="row mt-4">
	<div class="col-lg-8">
	  <div class="section-title">通信欄（任意）</div>
	    <textarea id="memo" style="height:250px; width:500px;" name="memo" rows="10" value="@if(isset($deal)){{$deal->memo}}@elseif(isset($user->memo)){{$user->memo}}@endif" class="form-control selectric" maxlength="374" onchange="Limit(event)" onkeyup="Limit(event)">@if(isset($deal)){{$deal->memo}}@elseif(isset($user->memo)){{$user->memo}}@endif</textarea>
			<p class="memo_note">※通信欄は「内容確認画面に進む」を押すと保存されます。<br />確認画面に進む直前に通信欄の入力をしてください。<br />「 " 」「 , 」「 # 」「 ! 」「 $ 」「 % 」<br class="sp"/>「 & 」「 = 」「 ; 」「 : 」「 ? 」「 + 」<br />上記の文字は使用できません。</p>
	</div>
	@if($user->setonagi)
  <div class="col-lg-4 text-right">
    <div class="invoice-detail-item">
      <div class="invoice-detail-name">合計</div>
      <div id="item_total" class="invoice-detail-value"></div>
    </div>

		<div id="c_shipping_price">
      <div class="c_shipping_price-detail-name">送料</div>
      <div class="c_shipping_price"></div>
			<input type="hidden" name="c_shipping_price" id="c_shipping_price_val" value="" />
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




<script>
// カートに商品がなければ非表示
$(document).ready(function() {
  $('.cartAccordion').each(function() {
    var hasCartItem = $(this).find('.cart_item').length > 0;
    if (!hasCartItem) {
      $(this).hide();
    }
  });
});
</script>





<!-- 操作に関する処理 -->
<script>
if(document.URL.match("/approval")) {
  $(function(){
		$('input').attr('readonly',true);
		$('select').attr('readonly',true);
		$('select').addClass('arrow_hidden');
		$("select[readonly] > option:not(:selected)").attr('disabled', 'disabled');
		$('textarea').attr('readonly',true);
		$('select').on('selectstart', false);
		$('select').on('contextmenu', false);
		$('.datepicker').attr("disabled", true);
		$('.head-sousa').remove();
		$('.addniniorder').remove();
  });
}
</script>
<style>
label,
.radio-input{
  pointer-events: none;
}
</style>


<!-- 発注済・CN操作に関する処理 -->
@if ( Auth::guard('user')->check() )
	@if(isset($deal))
		@if($deal->status == '発注済' or $deal->status == 'キャンセル' or $deal->status == 'リピートオーダー')
	<script>
	  $(function(){
			$('input').attr('readonly',true);
			$('select').attr('readonly',true);
			$('select').addClass('arrow_hidden');
			$("select[readonly] > option:not(:selected)").attr('disabled', 'disabled');
			$('textarea').attr('readonly',true);
			$('select').attr("disabled", true);
			$('.datepicker').attr("disabled", true);
			$('.head-sousa').remove();
			$('.addniniorder').remove();
	  });
	</script>
		@endif
	@endif
@endif




<script>
		// メモに最大13行の入力制限を追加
    function lineCheck(e) {
        var ta = document.getElementById("memo");
        var row = ta.getAttribute("rows");
        var r = (ta.value.split("\n")).length;
        if (document.all) {
            if (r >= row && window.event.keyCode === 13) { //keyCode for IE
                return false; //入力キーを無視
            }
        } else {
            if (r >= row && e.which === 13) { //which for NN
                return false;
            }
        }
    }
    window.document.onkeypress = lineCheck;
</script>

<script>

$(function(){
	//ＵＲＬのパラメータを取得するための関数
	function getUrlParam(param){
			var pageUrl = window.location.search.substring(1);
			var urlVar = pageUrl.split('&');
			for (var i = 0; i < urlVar.length; i++)
			{
					var paramName = urlVar[i].split('=');
					if (paramName[0] == param)
					{
							return decodeURI(paramName[1]);
					}
			}
	}
	$(function() {
			var memo = getUrlParam('memo');
			if (memo) {
				console.log(memo);
				$('#memo').text(memo);
				$('#memo').val(memo);
			}
	});
	// $(function() {
	// 		var change_all_nouhin_yoteibi = getUrlParam('change_all_nouhin_yoteibi');
	// 		if (change_all_nouhin_yoteibi) {
	// 			console.log(change_all_nouhin_yoteibi);
	// 			// $('#change_all_nouhin_yoteibi').text(change_all_nouhin_yoteibi);
	// 			$('#change_all_nouhin_yoteibi').val(change_all_nouhin_yoteibi);
	// 		}
	// });
});
</script>

<script>
if(document.URL.match('confirm|deal')) {
$(function() {
	// inputにフォーカス時、readonlyに変更
	$('.nouhin_yoteibi,.nini_nouhin_yoteibi')
	.focusin(function(e) {
		$(this).attr('readOnly', 'tlue');
	})
	.focusout(function(e) {
		$(this).removeAttr('readOnly');
	});
	$('.nouhin_yoteibi').css({'cssText': 'border: 1px solid #c7e2fe !important; background-color: #fdfdff !important;'});
	$('.nini_nouhin_yoteibi').css({'cssText': 'border: 1px solid #c7e2fe !important; background-color: #fdfdff !important;'});
});
}
</script>


@if(isset($user->setonagi))

@if(isset($setonagi->shipping_code))
<script>

$("#c_shipping_price").hide();
$("#c_shipping_date").hide();
$("#c_shipping_time").hide();
// $(".c_uketori_place").trigger("change");

$(document).ready(function() {
	// セットされているvalueを取得
	// var value = $("#set_uletori_place").val();
	// valueがセットされているかチェック
	// console.log(value);
	// if (value) {
	// 		// .c_uketori_place要素に対してchangeイベントを手動でトリガー
	// 		$(".c_uketori_place").trigger("change");
	// }
	$("#overlayajax").fadeOut();
  var methodId = $(".c_uketori_place").val();
  var user_id = $(".user_id").first().attr("id");
  var sano_nissuu = $("#sano_nissuu").val();
  console.log(methodId);
  console.log(user_id);
  console.log(sano_nissuu);
  $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: location.origin + '/get-delivery-times',
      type: 'POST',
      data: {
        'methodId': methodId,
        'user_id': user_id,
        'sano_nissuu': sano_nissuu,
      }
  })
  // Ajaxリクエスト成功時の処理
  .done(function(data) {
    console.log("Data received:", data); // データをコンソールに表示
    if(data.ukewatasibi_nyuuryoku_umu == 1) {

      var dates = data.holidays.map(function(item) {
          return "'" + item.date + "'";
      });
      var holidays = dates.join(',');
      console.log(holidays);

      $('.datepicker').datepicker('destroy'); //Destroy it before re-initing
      $('.nouhin_yoteibi_c').datepicker({
				format: 'yyyy-mm-dd',
				autoclose: true,
				assumeNearbyYear: true,
				language: 'ja',
				startDate: sano_nissuu,
				endDate: '+31d',
        setDate: null,
				// defaultViewDate: Date(),
				// defaultViewDate: { year: null, month: null, day: null },
				datesDisabled: holidays,
			});
      // 表示を有効にする
      $("#c_shipping_date").show();
			// 前の画面から戻った場合、confirmのみ予め設定されている入力値をクリア
      if(document.URL.match("/confirm")) {
				$(function() {
						var change_all_nouhin_yoteibi = getUrlParam('change_all_nouhin_yoteibi');
						if (change_all_nouhin_yoteibi) {
							console.log(change_all_nouhin_yoteibi);
							// $('#change_all_nouhin_yoteibi').text(change_all_nouhin_yoteibi);
							// $('#change_all_nouhin_yoteibi').val(change_all_nouhin_yoteibi);
						}else{
							$('.nouhin_yoteibi_c').datepicker('setDate', null);
							$('.nouhin_yoteibi_c').val('');
						}
				});
      }
    } else {
      $("#c_shipping_date").hide();
    }
    if(data.ukewatasi_kiboujikan_umu == 1) {
      $("#c_shipping_time").show();
    	$('#uketori_time').val($('#uketori_time option:first').val());
    } else {
      $("#c_shipping_time").hide();
  		$('#uketori_time').val('');
    }
    if(data.shipping_price > 1) {
      $("#c_shipping_price").show();
      var c_shipping_price = data.shipping_price.toLocaleString();
      $('.c_shipping_price').text('¥ ' + c_shipping_price);
      $('input[name="c_shipping_price"]').val(c_shipping_price);

      var shipping_price = $('#c_shipping_price_val').val();
      console.log(shipping_price);

      // 商品合計
      var sum = 0;
      $('.total').each(function () {
        var price = $(this).closest('tr').find('input.price').data('price');
        var quantity = $(this).closest('tr').find('select.quantity').val();
        var total = price * quantity;
        $(this).text(total);
        sum += total;
      });

      // 商品合計
      var itemTotal = sum.toLocaleString();
      $('#item_total').text('¥ ' + itemTotal);

      // 送料税込額
      var shipping_price_zei = Math.floor(shipping_price * 110 / 100);
      console.log(shipping_price_zei);

      // 送料税額
      var shipping_price_zei_only = shipping_price_zei - shipping_price;

  		// 税込合計金額
      var allTotal = Math.floor(sum * 108 / 100) + shipping_price_zei;
      $('#all_total').text('¥ ' + allTotal.toLocaleString());
      $('#all_total_val').val(allTotal);

  		// 税額
      var tax = Math.round(allTotal - sum - shipping_price);
      $('#tax').text('¥ ' + tax.toLocaleString());
      $('#tax_val').val(tax);


      // $('.nouhin_yoteibi_c').datepicker('setDate', null);
      // $('.nouhin_yoteibi_c').val('');
      // $('#change_all_nouhin_yoteibi').datepicker('setDate', null);
      // $('#change_all_nouhin_yoteibi').val('');
      // $('#change_all_nouhin_yoteibi').trigger('change');

    } else {
      $("#c_shipping_price").hide();
			// $('input[name="security_code"]').val('');
			// $('#card_approval_btn').addClass('disabled_btn');

      // 商品合計
      var sum = 0;

      $('.total').each(function () {
        var price = $(this).closest('tr').find('input.price').data('price');
        var quantity = $(this).closest('tr').find('select.quantity').val();
        var total = price * quantity;
        $(this).text(total);
        sum += total;
      });

      // 商品合計
      var itemTotal = sum.toLocaleString();
      $('#item_total').text('¥ ' + itemTotal);

  		// 税込合計金額
      var allTotal = Math.floor(sum * 108 / 100);
      $('#all_total').text('¥ ' + allTotal.toLocaleString());
      $('#all_total_val').val(allTotal);

  		// 税額
      var tax = Math.round(allTotal - sum);
      $('#tax').text('¥ ' + tax.toLocaleString());
      $('#tax_val').val(tax);

    }



    // Swal.fire({
    //   type:"success",
    //   title: "変更完了",
    //   position: 'center-center',
    //   toast: true,
    //   icon: 'success',
    //   showConfirmButton: false,
    //   timer: 1500
    // });
  })
  // Ajaxリクエスト失敗時の処理
  .fail(function(jqXHR, textStatus, errorThrown) {
    alert('配送方法を変更できませんでした。');
    console.log("ajax通信に失敗しました");
    console.log("XMLHttpRequest : " + XMLHttpRequest.status);
    console.log("textStatus     : " + textStatus);
    console.log("errorThrown    : " + errorThrown.message);
  });
});
// 配送方法が選択されない状態だと、カード情報の入力エリアを非表示にするconfirmのみ起動
$(document).ready(function() {
	if (window.location.pathname.indexOf('confirm') !== -1) {
		// 日付の手入力を禁止
		$(".nouhin_yoteibi_c").keydown(function(e) {
			// if (e.which == 13 && $(this).prop("readonly")) {
			// 		e.preventDefault();
			// 		e.stopImmediatePropagation();
			// 		return false;
			// }
			e.preventDefault();
			e.stopImmediatePropagation();
			return false;
		});

		// URLパスに「confirm」が含まれている場合に実行
		updatePayCardVisibility();
		$('#uketori_place, .nouhin_yoteibi_c, #uketori_time').change(function() {
		// セレクトボックスの値が変更されたときに実行
			// updatePayCardVisibility();
		});
		function updatePayCardVisibility() {
			// 納品予定日などが存在する場合
		  if ($('#uketori_time').is(':visible') && $('.nouhin_yoteibi_c').is(':visible')) {
		    if ($('#uketori_place').val() !== '' && $('.nouhin_yoteibi_c').val() !== '' && $('#uketori_time option:selected').val() !== '') {
		      $('#pay_card').show();
		    } else {
		      $('#pay_card').hide();
		    }
		  }else if ($('#uketori_time').is(':hidden') && $('.nouhin_yoteibi_c').is(':visible')) {
		    if ($('#uketori_place').val() !== '' && $('.nouhin_yoteibi_c').val() !== '') {
		      $('#pay_card').show();
		    } else {
		      $('#pay_card').hide();
		    }
		  } else {
		    if ($('#uketori_place').val() !== '') {
		      $('#pay_card').show();
		    } else {
		      $('#pay_card').hide();
		    }
		  }
		}
	}
});

</script>
@else
<script>
$(function() {
$("#c_shipping_price").hide();
// $('input[name="security_code"]').val('');
// $('#card_approval_btn').addClass('disabled_btn');
});
</script>
@endif

@if(Auth::guard('user')->check() )
<script>
if(document.URL.match('confirm')) {

$(function() {

if ($("[id=クロネコかけ払い]").prop("checked") == true) {
$('#pay_card').hide();
} else if ($('[id=クレジットカード払い]').prop('checked')) {
$('#pay_card').show();
$('input[name="card_no"]').attr('required', true);
$('input[name="card_owner"]').attr('required', true);
$('input[name="exp_month"]').attr('required', true);
$('input[name="yuukoukigen"]').attr('required', true);
$('input[name="exp_year"]').attr('required', true);
$('input[name="security_code"]').attr('required', true);
//始めにjQueryで送信ボタンを無効化する
$('#card_approval_btn').addClass('disabled_btn');
//始めにjQueryで必須欄を加工する
$('.charge_form input, #uketori_place').each(function () {
		$(this).prev("label").addClass("required");
});
// 入力欄の操作時
$('.charge_form input, #uketori_place').change(function () {
		//必須項目が空かどうかフラグ
		let flag = true;
		//必須項目をひとつずつチェック
		$('.charge_form input:required').each(function(e) {
				//もし必須項目が空なら
				if ($('.charge_form input:required').eq(e).val() === "") {
						flag = false;
				}
		});
		//全て埋まっていたら
		if (flag) {
				//送信ボタンを復活
				$('#card_approval_btn').removeClass('disabled_btn');
		}
		else {
				//送信ボタンを閉じる
				$('#card_approval_btn').addClass('disabled_btn');
		}
});
}

$('[name="uketori_siharai"]:radio').change( function() {
if($('[id=クロネコかけ払い]').prop('checked')){
$('#pay_card').hide();
$('').fadeIn();
} else if ($('[id=クレジットカード払い]').prop('checked')) {
$('#pay_card').show();
$('input[name="card_no"]').attr('required', true);
$('input[name="card_owner"]').attr('required', true);
$('input[name="exp_month"]').attr('required', true);
$('input[name="yuukoukigen"]').attr('required', true);
$('input[name="exp_year"]').attr('required', true);
$('input[name="security_code"]').attr('required', true);
//始めにjQueryで送信ボタンを無効化する
$('#card_approval_btn').addClass('disabled_btn');
//始めにjQueryで必須欄を加工する
$('.charge_form input:required').each(function () {
		$(this).prev("label").addClass("required");
});
//入力欄の操作時
$('.charge_form input, #uketori_place').change(function () {
		//必須項目が空かどうかフラグ
		let flag = true;
		//必須項目をひとつずつチェック
		$('.charge_form input:required').each(function(e) {
				//もし必須項目が1つでも空なら
				if ($('.charge_form input:required').eq(e).val() === "") {
						flag = false;
				}
		});
		//全て埋まっていたら
		if (flag) {
				//送信ボタンを復活
				$('#card_approval_btn').removeClass('disabled_btn');
		}
		else {
				//送信ボタンを閉じる
				$('#card_approval_btn').addClass('disabled_btn');
		}
});
}
});
});


// ボタンの表示非表示
$(function() {
if ($("[id=クロネコかけ払い]").prop("checked") == false) {
$('#card_approval_btn').hide();
$('#approval_btn').hide();
} else if ($('[id=クレジットカード払い]').prop('checked') == false) {
$('#card_approval_btn').hide();
$('#approval_btn').hide();
}

if ($("[id=クロネコかけ払い]").prop("checked") == true) {
$('#card_approval_btn').hide();
$('#approval_btn').show();
} else if ($('[id=クレジットカード払い]').prop('checked') == true) {
$('#card_approval_btn').show();
$('#approval_btn').hide();
}

$('[name="uketori_siharai"]:radio').change( function() {
if($('[id=クロネコかけ払い]').prop('checked')){
$('#card_approval_btn').hide();
$('#approval_btn').show();
$('').fadeIn();
} else if ($('[id=クレジットカード払い]').prop('checked')) {
$('#card_approval_btn').show();
$('#approval_btn').hide();
}
});
});


$(function(){
  $("#card_approval_btn").click(function(e){
    $(".executePay").trigger("click");
    $(".executePay").click();
  })
});

}else{
	$(function(){
		$('#pay_card').hide();
	});
}
</script>
	@endif
@endif

<script>
function equalizeHeightByClass(className) {
  var elements = document.getElementsByClassName(className);
  var maxHeight = 0;

  // 最大の高さを計算
  for (var i = 0; i < elements.length; i++) {
    var height = elements[i].offsetHeight;
    if (height > maxHeight) {
      maxHeight = height;
    }
  }

  // 最大の高さを設定
  for (var j = 0; j < elements.length; j++) {
    elements[j].style.height = maxHeight + 'px';
  }
}

// ページ読み込み時に実行
window.onload = function() {
  equalizeHeightByClass('order_item');
};


$(document).ready(function() {
	// テキストエリアのIDを指定
	var textarea = $('#memo');

	// 入力があった場合のイベントを設定
	textarea.on('input', function() {
		// 入力されたテキストを取得
		var inputText = textarea.val();

		// 禁止文字のリスト
		var forbiddenChars = ['"', ',', '#', '!', '$', '%', '&', '=', ';', ':', '?', '+'];

		// 禁止文字が含まれているかチェック
		for (var i = 0; i < forbiddenChars.length; i++) {
			var forbiddenChar = forbiddenChars[i];
			// 禁止文字が含まれている場合、その文字を削除
			inputText = inputText.split(forbiddenChar).join('');
		}

		// 修正されたテキストをセット
		textarea.val(inputText);
	});
});


</script>

@if(Auth::guard('user')->check() )
@if(isset($deal))
<style>
/* @media (max-width: 767px) {
	.head-price input{
		height: 23px !important;
	}
	.head-quantity select{
		height: 23px !important;
	}
} */
</style>
@endif
@endif
