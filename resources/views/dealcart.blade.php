@foreach($carts as $cart)
<tr>
	<td>{{$cart->item->item_name}}</td>
	<td class="teika text-center">

		@if ( Auth::guard('admin')->check() )
			@if($deal->success_flg)
				@if(isset($cart->discount))
				<input name="discount[]" class="teika text-center form-control" value="{{$cart->discount}}" readonly>
				@else
				<input name="teika[]" class="teika text-center form-control" value="{{$cart->item->teika}}" readonly>
				@endif
			@else
				@if(isset($cart->discount))
				<input name="discount[]" class="teika text-center form-control" value="{{$cart->discount}}">
				@else
				<input name="teika[]" class="teika text-center form-control" value="{{$cart->item->teika}}">
				@endif
			@endif
		@endif

		@if ( Auth::guard('user')->check() )
			@if(isset($cart->discount))
			<input name="discount[]" class="teika text-center form-control" value="{{$cart->discount}}" readonly>
			@else
			<input name="teika[]" class="teika text-center form-control" value="{{$cart->item->teika}}" readonly>
			@endif
		@endif

	</td>
	<td class="text-center">
		@if($deal->success_flg)
		<input name="quantity[]" class="quantity text-center form-control" value="{{$cart->quantity}}" readonly>
		@else
		<input name="quantity[]" class="quantity text-center form-control" value="{{$cart->quantity}}">
		@endif
	</td>
	<td class="total text-center"></td>
	@if($deal->success_flg)
	@else
	<td class="text-center"><button id="{{$cart->item->id}}" class="removeid_{{$cart->item->id}} removecart btn btn-info">削除</button>
	<input name="item_id[]" type="hidden" value="{{$cart->item->id}}" />
	<input name="cart_id[]" type="hidden" value="{{$cart->id}}" class="cart_id"/>
	</td>
	@endif
</tr>
@endforeach
