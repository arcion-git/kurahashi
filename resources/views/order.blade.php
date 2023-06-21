


@if(request()->input('addtype') == 'addbuyerrecommend' && $user->setonagi)
@else
<div class="table-responsive" id="nouhin_store_nouhin_yoteibi">
	@if(!$user->setonagi)
	<div class="section-title">納品先・納品日</div>
	@elseif(request()->input('addtype') === 'addbuyerrecommend')
	<div class="section-title">お気に入り表示</div>
	@endif
	<table id="{{$user->id}}" class="user_id table table-striped table-hover table-md cart-wrap">
		@if(!$user->setonagi)
		<tr>
			<th class="">納入先店舗</th>
			<th class="">
				<select id="change_all_store" name="change_all_store" class="change_all_store text-center form-control" value="" required>
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

					<input id="change_all_nouhin_yoteibi" type="text" name="change_all_nouhin_yoteibi" class="change_all_nouhin_yoteibi text-center form-control daterange-cus datepicker" value="@if(isset($set_order)){{$set_order->nouhin_yoteibi}}@endif" autocomplete="off" required>
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



						@if(isset($groupedItems))
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

															<!-- お気に入り商品表示非表示 -->
															@if(!isset($show_favorite) && ($cart->addtype == 'addbuyerrecommend' || $cart->addtype == 'addsetonagi' || $cart->addtype == 'addspecialprice') || (isset($show_favorite) && ($cart->favoriteitem())))


															<!-- 最終確認画面で在庫がある商品のみ -->


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
																		@if($cart->addtype == 'addbuyerrecommend')
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
																		<tr id="{{$val->id}}" class="order_item">
																			<td class="head-price text-center">
																				<!-- BtoB金額表示 -->
																				@if(!$user->setonagi)
																					@if($cart->hidden_price() == '1')
																					<!-- 担当のおすすめ商品の価格非表示が選択されている場合 -->
																					<input name="price[]" pattern="^[0-9]+$" class="price text-center form-control" data-price="未定" value="未定" readonly>
																					@else
																					<!-- BtoB通常金額表示 -->
																					<input name="price[]" pattern="^[0-9]+$" class="price text-center form-control" data-price="@if($val->price=='未定'){{(0)}}@else{{ $val->price }}@endif" value="@if($val->price=='未定')未定@else{{number_format($val->price)}}@endif" readonly>
																					@endif
																				@else
																					@if(isset($deal))
																						<input name="price[]" pattern="^[0-9]+$" class="price text-center form-control" data-price="{{$val->price}}" value="@if( Auth::guard('admin')->check() ){{ $val->price }}@else{{number_format($val->price)}}@endif">
																					@else
																					<!-- BtoSB金額表示 -->
																						<input name="price[]" pattern="^[0-9]+$" class="price text-center form-control" data-price="{{ $val->price }}" value="{{number_format($val->price)}}" readonly>
																					@endif
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
																				ﾊﾞﾗ
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
																			<td class="head-shoukei total text-center"></td>
																			<td class="head-sousa text-center">
																				<button type="button" id="{{$val->id}}" class="removeid_{{$val->id}} removeorder btn btn-info">削除</button>
																				@if(!$user->setonagi)
																				<button style="margin-top:10px;" type="button" id="{{$cart->item->id}}" class="cloneid_{{$cart->item->id}} clonecart btn btn-success">配送先を追加</button>
																				@endif
																			</td>
																			<input name="order_id[]" class="order_id" type="hidden" value="{{$val->id}}" />
																		</tr> --}}
																	@endforeach
																	</table>
																</td>
															</tr>

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
				@if(isset($setonagi->uketori_place))
				<option value="{{$setonagi->uketori_place}}" selected>{{$setonagi->uketori_place}}</option>
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
				@if(isset($setonagi->uketori_time))
				<option value="{{$setonagi->uketori_time}}" selected>{{$setonagi->uketori_time}}</option>
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
						<input required class="radio-input uketori_siharai_radio" type="radio" id="クロネコかけ払い" value="クロネコかけ払い" name="uketori_siharai" @if($setonagi->uketori_siharai == 'クロネコかけ払い') checked @endif ><label for="クロネコかけ払い"> クロネコかけ払い</label>
					@endif
				@endif

				@if ( Auth::guard('admin')->check() )
					<input required class="radio-input uketori_siharai_radio" type="radio" id="クロネコかけ払い" value="クロネコかけ払い" name="uketori_siharai" @if($setonagi->uketori_siharai == 'クロネコかけ払い') checked @endif ><label for="クロネコかけ払い"> クロネコかけ払い</label>
				@endif

					<input required class="radio-input uketori_siharai_radio" type="radio" id="クレジットカード払い" value="クレジットカード払い" name="uketori_siharai" @if($setonagi->uketori_siharai == 'クレジットカード払い') checked @endif><label for="クレジットカード払い"> クレジットカード払い</label>
					<input type="hidden" name="token_api" id="token_api" value="{{app('request')->input('token_api')}}"/>
					<div class="invalid-feedback">
					</div>
			</div>
		</div>





		<!-- カード情報confirmでクレジットカード払いが選択されたら表示 -->
		@if($user->setonagi & Auth::guard('user')->check() )
		<div id="pay_card">
			<div class="row mt-4">
				<div class="col-md-12">
					<div class="section-title">クレジットカード情報</div>
				</div>
			</div>
			<div class="form-group col-12">
				<form method="POST" action="@if(isset($collect_touroku)){{$collect_touroku}}@endif" name="charge_form" class="charge_form" onsubmit="return false;">
					<div class="input-form row">
						<div class="col-sm-12 col-md-2">
							<label for="card_no">カード番号</label>
						</div>
						<div class="col-sm-12 col-md-5">
							<input type="text" class="form-control" name="card_no" maxlength="16" placeholder="************1234" value="">
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
							<input type="text" class="form-control yuukoukigen" name="exp_month" maxlength="2" placeholder="10" value="">月/ <input class="form-control yuukoukigen" type="text" name="exp_year" maxlength="2" value="" placeholder="24">年
						</div>
					</div>
					<div class="input-form row">
						<div class="col-sm-12 col-md-2">
							<label>セキュリティコード</label>
						</div>
						<div class="col-sm-12 col-md-5">
							<input type="text" class="form-control" name="security_code" maxlength="4" placeholder="1234" value="">
						</div>
					</div>
					<div class="input-form" style="display:none;">
						<input class="executePay" type="submit" value="送信" onclick="executePay()">
					</div>
				</form>
			</div>
		</div>

		<!-- JavaScript ライブラリ読み込み body タグ内に記述する必要があります。-->
		<script type="text/javascript" class="webcollect-embedded-token" src="@if(isset($collect_token)){{$collect_token}}@endif"></script>
		<script type="text/javascript">
		/*
		* 送信ボタン押下時に実行する JavaScript 関数
		*/


		function executePay() {
		$("#overlayajax").fadeIn(300);

		var text = '@if(isset($collect_password)){{$collect_password}}@endif';
		function async_digestMessage(message) {
			return new Promise(function(resolve){
			var msgUint8 = new TextEncoder("utf-8").encode(message);
			crypto.subtle.digest('SHA-256', msgUint8).then(
					function(hashBuffer){
							var hashArray = Array.from(new Uint8Array(hashBuffer));
							var hashHex = hashArray.map(function(b){return b.toString(16).padStart(2, '0')}).join('');
							return resolve(hashHex);
					});
			})
		}
		if(window.Promise && window.crypto){
			async_digestMessage(text).then(
					function(shatxt){
							getHashText(shatxt);
					}
			).catch(function(e){
					console.log('エラー：', e.message);
			})
		}else{
			console.log('Promiseかcryptoに非対応');
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
		// alert(token_api);
		// document.location.href = "https://www.ipentec.com/"+token;

		// form をサブミットする
		// formElement.submit(params);
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
		alert(errorInfo[i].errorCode + " : " + errorInfo[i].errorMsg); }
		};

		// トークン発行 API へ渡すパラメータ
		var createTokenInfo = {
		traderCode: "@if(isset($collect_tradercode)){{$collect_tradercode}}@endif",
		authDiv: "2",
		optServDiv: "00",
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
        <tbody>
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
    <button style="min-width:200px;" type="button" name="" id="@if(isset($deal)){{$deal->id}}@endif" class="addniniorder btn btn-success"><i class="fas fa-plus"></i> 任意の商品を追加</button>
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
$(document).ready(function () {
  updateFields();

  $('select.quantity').on('change', function () {
    updateFields();
  });

  function updateFields() {
    var sum = 0;

    $('.total').each(function () {
      var price = $(this).closest('tr').find('input.price').data('price');
      var quantity = $(this).closest('tr').find('select.quantity').val();
      var total = price * quantity;
      $(this).text(total);

      sum += total;
    });

    var itemTotal = sum.toLocaleString();
    $('#item_total').text('¥ ' + itemTotal);

    var allTotal = Math.round(sum * 108 / 100);
    $('#all_total').text('¥ ' + allTotal.toLocaleString());
    $('#all_total_val').val(allTotal);

    var tax = Math.round(allTotal - sum);
    $('#tax').text('¥ ' + tax.toLocaleString());
    $('#tax_val').val(tax);
  }
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





<!-- <tr>
		<td class="teika">
			<input name="nini_item_name[]" class="text-center form-control" value=" ハマグリ 4kg 天然"></td>
		<td class="text-center">
			<select name="nini_tantou[]" class="text-center form-control" value="1">
				<option value="鮮魚">鮮魚</option>
				<option value="青物">青物</option>
				<option value="太物">太物</option>
				<option value="近海">近海</option>
				<option value="特殊">特殊</option>
				<option value="養魚">養魚</option>
				<option value="水産">水産</option>
			</select>
		</td>

		<td class="text-center">
			<select name="nini_store[]" class="text-center form-control" value="1">
				<option value="サンプル1">春日店</option>
				<option value="サンプル2">緑町店</option>
				<option value="サンプル3">蔵王店</option>
			</select>
		</td>
		<td class="text-center"><input name="nini_quantity[]" class="quantity text-center form-control" value="1"></td>
		<td class="text-center">
			<div class="input-group">
				<div class="input-group-prepend">
					<div class="input-group-text">
						<i class="fas fa-calendar"></i>
					</div>
				</div>
				<input  type="text" name="nini_nouhinbi[]" class="nouhinbi text-center form-control daterange-cus" value="">
				<inputclass="form-control daterange-cus">
			</div>
		</td>
		<td class="text-center">
			<button id="" class="removeid_ removecart btn btn-info">削除</button>
			<button id="" class="btn btn-success" style="margin-top:20px;">配送先を追加</button>
		<input type="hidden" value="" />
		</td>
</tr> -->




<script>
$(document).ready(function() {
  $('.cartAccordion').each(function() {
    var hasCartItem = $(this).find('.cart_item').length > 0;
    if (!hasCartItem) {
      $(this).hide();
    }
  });
});
</script>





<!-- 取引確認画面で操作ができないようにする -->
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


<!-- 発注済、キャンセルの場合操作ができないようにする -->
@if ( Auth::guard('user')->check() )
	@if(isset($deal))
		@if($deal->status == '発注済' or $deal->status == 'キャンセル')
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
$('.charge_form input:required').each(function () {
		$(this).prev("label").addClass("required");
});
//入力欄の操作時
$('.charge_form input:required').change(function () {
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
$('.charge_form input:required').change(function () {
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
});
});


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
</script>


	@endif
@endif
