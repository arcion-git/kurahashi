<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Order;
use App\Deal;
use App\Item;
use App\Category;
use App\Tag;
use App\User;
use App\Holiday;
use App\Store;
use App\StoreUser;
use App\FavoriteCategory;
use App\Price;
use App\PriceGroupe;
use App\SpecialPrice;
use App\Recommend;
use App\BuyerRecommend;
use App\RecommendCategory;
use App\CartNini;
use App\OrderNini;
use App\Repeatcart;
use App\Repeatorder;
use App\Setonagi;
use App\SetonagiItem;
use App\Favorite;
use App\Contact;

// デバッグを出力
use Log;
use Response;

// 時間に関する処理
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

// BtoC向け
use App\ShippingCalender;
use App\ShippingCompanyCode;
use App\ShippingInfo;
use App\ShippingSetting;

// 配列をページネーションする
use Illuminate\Pagination\LengthAwarePaginator;

// API通信
use GuzzleHttp\Client;


class LoginPageController extends Controller
{
  public function __construct(){
		$this->middleware('user');
	}



  public function questionnaire()
  {
    // if ( Auth::guard('admin')->check() ){
    //     Auth::guard('admin')->logout();
    // }

      $categories = Category::get();
      $categories = $categories->groupBy('bu_ka_name');
      // dd($categories);

      $user_id = Auth::guard('user')->user()->id;
      $carts =  Cart::where('user_id',$user_id)->get();

      return view('user/auth/questionnaire', ['categories' => $categories]);
  }




    public function bulk()
    {
        if ( Auth::guard('admin')->check() ){
            Auth::guard('admin')->logout();
        }

        $categories = Category::get();
        $categories = $categories->groupBy('bu_ka_name');
        // dd($categories);
        $user_id = Auth::guard('user')->user()->id;
        $carts = Cart::where('user_id',$user_id)->get();

        $setonagi = Setonagi::where(['user_id'=> $user_id])->first();
        if($setonagi){
          $shipping_code = $setonagi->shipping_code;
        }
        if(isset($shipping_code)){
          return redirect()->route('setonagi');
        }else{
          return view('user/auth/bulk', ['categories' => $categories]);
        }
    }


  public function index(Request $request)
  {
    if ( Auth::guard('admin')->check() ){
        Auth::guard('admin')->logout();
    }

      // $search = $request->search;
      // dd($search);





      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->first();

      if ($favorite_categories === null) {
        $categories = Category::get();
        $categories = $categories->groupBy('bu_ka_name');

        // dd($categories);
        return view('user/auth/questionnaire', ['categories' => $categories]);
      }

      $setonagi_user = Auth::guard('user')->user()->setonagi;
      // セトナギユーザーの場合は取得しない
      if(!$setonagi_user){
        // 価格が存在するユーザーごとの商品に絞り込み
        $kaiin_number = Auth::guard('user')->user()->kaiin_number;
        // dd($kaiin_number);
        $store_user = StoreUser::where('user_id',$kaiin_number)->first(['store_id','tokuisaki_id']);
        // dd($store_user);
        $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
        // dd($store);
        $price_groupe = PriceGroupe::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
        // ここまで



        $kaiin_number = Auth::guard('user')->user()->kaiin_number;
        $now = Carbon::now();

        $special_prices = [];
        $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');
        foreach ($tokuisaki_ids as $key => $value) {
          // 担当のおすすめ商品の納品期日を探す
          $price_groupe = PriceGroupe::where(['tokuisaki_id'=>$value->tokuisaki_id,'store_id'=>$value->store_id])->first();
          $special_prices_loop = SpecialPrice::where(['price_groupe'=>$price_groupe->price_groupe])
          ->where('start', '<=' , $now)
          ->where('end', '>=', $now)->get();
          // dd($buyer_recommend_item);
          $special_prices = collect($special_prices)->merge($special_prices_loop);
        }
        // dd($special_prices);







        // ここから追加

        // dd($price_groupe->price_groupe);
        // $price_groupe = $store->price_groupe();
        // dd($price_groupe->price_groupe);

        // dd($price_groupe);

        // 価格グループの取得はしない
        // $prices = Price::where(['price_groupe'=>$price_groupe->price_groupe])->get();
        //
        // $user_items = [];
        // $n=1;
        // foreach ($prices as $price) {
        //   $item = $price->item();
        //   // dd($item);
        //   if($item->zaikosuu >= 0.01){
        //   array_push($user_items, $item);
        // }
        // $n++;
        // }
        // $items = $user_items;
        // $items = collect($items);
        // 価格グループの取得はしない


        // dd($request->page);
        // dd($items);
        // $all_num = count($items);
        // $disp_limit = '10';
        // $items = new LengthAwarePaginator($items , $all_num, $disp_limit, $request->page, array('path' => $request->url()));
        // $items = new LengthAwarePaginator(
        //             // $items->forPage($request->page, 30),
        //             $items->forPage($request->page, 30),
        //             // $items->get($request->page - 30),
        //             count($items),
        //             30,
        //             $request->page,
        //             array('path' => $request->url() , "pageName" => "page")
        //         );


        // dd($now);
        $items = Item::where('zaikosuu', '>=', '0.01')->paginate(30);
        // $special_prices = SpecialPrice::where(['price_groupe'=>$price_groupe->price_groupe])->get();
        // dd($price_groupe->price_groupe);

        // $items = Item::where('zaikosuu', '>=', '0.01')->paginate(30);
                // dd($items->first());
      }else{
        // セトナギユーザーに対する処理
        $items = Item::where('zaikosuu', '>=', '0.01')->paginate(30);
        // セトナギユーザーにはC帯の商品を出力
        $special_prices = SpecialPrice::where(['price_groupe'=>'10000000005'])->get();
      }
      // dd($items);








      $categories = Category::get()->groupBy('bu_ka_name');

      $user_id = Auth::guard('user')->user()->id;

      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

      $favorite_items = Favorite::where('user_id', $user_id)->get();
      // dd($favorite_items);

      $carts =  Cart::where('user_id',$user_id)->get();

      $kaiin_number = Auth::guard('user')->user()->kaiin_number;

      // $now = Carbon::now()->addDay(3)->format('Y-m-d');
      $now = Carbon::now();
      // dd($user_id);

      $kaiin_number = Auth::guard('user')->user()->kaiin_number;
      $buyer_recommends = [];
      $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');
      foreach ($tokuisaki_ids as $key => $value) {
        // dd($value->tokuisaki_id);
        $buyer_recommend_loop = BuyerRecommend::where('tokuisaki_id', $value->tokuisaki_id)
        ->whereNotNull('nouhin_end')
        ->where('price', '>=', '1')
        ->where('start', '<=' , $now)
        ->where('end', '>=', $now)
        ->orderBy('order_no', 'asc')->get();
        // array_push($buyer_recommends, $buyer_recommend_loop);
        $buyer_recommends = collect($buyer_recommends)->merge($buyer_recommend_loop);
      }
      // dd($buyer_recommends);

      $recommends = Recommend::where('user_id', $user_id)
      ->where('price', '>=', '1')
      ->where('start', '<=' , $now)
      ->where('end', '>=', $now)
      ->orderBy('order_no', 'asc')->get();

      $recommends = $recommends->merge($buyer_recommends);
      // $recommends = $buyer_recommends;



      // dd($recommends);

      $now = Carbon::now()->toDateTimeString();




      return view('user/home',
      ['items' => $items ,
       'carts' => $carts ,
       'categories' => $categories ,
       'favorite_categories' => $favorite_categories,
       'favorite_items' => $favorite_items,
       'recommends' => $recommends,
       'special_prices' => $special_prices,
       'page' => $request->page,
       'now' => $now,
      ]);
  }


  public function contact()
  {
      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->first();
        if ($favorite_categories === null) {
          $categories = Category::get();
          $categories = $categories->groupBy('bu_ka_name');
          // dd($categories);
          return view('user/auth/questionnaire', ['categories' => $categories]);
        }
      $categories = Category::get()->groupBy('bu_ka_name');
      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
      $carts =  Cart::where('user_id',$user_id)->get();
      $kaiin_number = Auth::guard('user')->user()->kaiin_number;
      $now = Carbon::now()->addDay(3)->format('Y-m-d');
      $recommends = Recommend::where('user_id', $kaiin_number)->where('end', '>=', $now)->orWhere('end',null)->where('user_id', $kaiin_number)->get();
      $special_prices = SpecialPrice::get();
      return view('user/contact',
      ['carts' => $carts ,
       'categories' => $categories ,
       'favorite_categories' => $favorite_categories,
       'recommends' => $recommends,
       'special_prices' => $special_prices,
      ]);
  }

  public function postcontact(Request $request){
      $name = $request->name;
      $address = $request->address;
      $email = $request->email;
      $tel = $request->tel;
      $shubetu = $request->shubetu;
      $naiyou = $request->naiyou;
      $contact = Contact::create(['name'=> $name, 'address'=> $address ,'email'=> $email, 'tel'=> $tel, 'shubetu'=> $shubetu, 'naiyou'=> $naiyou ]);
      $admin_mail = config('app.admin_mail');
      $text = 'この度はお問い合わせいただきありがとうございます。<br />下記の内容でお問い合わせを受け付けました。<br />
      <br />
      お名前：'.$name.'<br />
      ご住所：'.$address.'<br />
      メールアドレス：'.$email.'<br />
      電話番号：'.$tel.'<br />
      お問い合わせ種別：'.$shubetu.'<br />
      お問い合わせ内容：<br />
      '.$naiyou.'<br />
      <br />内容を確認し、担当より1〜3営業日以内にご返信させていただきます。
      <br />今しばらくお待ちください。';
      Mail::send('emails.register', [
          'name' => $name,
          'text' => $text,
          'admin_mail' => $admin_mail,
      ], function ($message) use ($email , $admin_mail) {
          $message->to($email)->bcc($admin_mail)->subject('SETOnagiお問い合わせ完了のお知らせ');
      });
      $message = 'お問い合わせ内容が送信されました';
      $data=[
        'message'=>$message,
      ];
      return redirect()->route('contact',$data);
  }



  public function saiji(){
      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->first();

      if ($favorite_categories === null) {
        $categories = Category::get();
        $categories = $categories->groupBy('bu_ka_name');
        // dd($categories);
        return view('user/auth/questionnaire', ['categories' => $categories]);
      }

      $categories = Category::get()->groupBy('bu_ka_name');

      $user_id = Auth::guard('user')->user()->id;

      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

      $carts =  Cart::where('user_id',$user_id)->get();

      $kaiin_number = Auth::guard('user')->user()->kaiin_number;

      $now = Carbon::now()->addDay(3)->format('Y-m-d');

      $recommends = Recommend::where('user_id', $kaiin_number)->where('end', '>=', $now)->orWhere('end',null)->where('user_id', $kaiin_number)->get();

      $special_prices = SpecialPrice::get();

      return view('user/saiji',
      ['carts' => $carts ,
       'categories' => $categories ,
       'favorite_categories' => $favorite_categories,
       'recommends' => $recommends,
       'special_prices' => $special_prices,
      ]);
  }


  public function firstguide()
  {
      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->first();
        if ($favorite_categories === null) {
          $categories = Category::get();
          $categories = $categories->groupBy('bu_ka_name');
          // dd($categories);
          return view('user/auth/questionnaire', ['categories' => $categories]);
        }
      $categories = Category::get()->groupBy('bu_ka_name');
      $user_id = Auth::guard('user')->user()->id;

      $shipping_code = Setonagi::where('user_id',$user_id)->first('shipping_code');

      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
      $carts =  Cart::where('user_id',$user_id)->get();
      $kaiin_number = Auth::guard('user')->user()->kaiin_number;
      $now = Carbon::now()->addDay(3)->format('Y-m-d');
      $recommends = Recommend::where('user_id', $kaiin_number)->where('end', '>=', $now)->orWhere('end',null)->where('user_id', $kaiin_number)->get();
      $special_prices = SpecialPrice::get();
      return view('user/firstguide',
      ['carts' => $carts ,
       'categories' => $categories ,
       'favorite_categories' => $favorite_categories,
       'recommends' => $recommends,
       'special_prices' => $special_prices,
       'shipping_code' => $shipping_code,
      ]);
  }
  public function guide()
  {
      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->first();
        if ($favorite_categories === null) {
          $categories = Category::get();
          $categories = $categories->groupBy('bu_ka_name');
          // dd($categories);
          return view('user/auth/questionnaire', ['categories' => $categories]);
        }
      $setonagi = Setonagi::where('user_id',$user_id)->first();
      $categories = Category::get()->groupBy('bu_ka_name');
      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
      $carts =  Cart::where('user_id',$user_id)->get();
      $kaiin_number = Auth::guard('user')->user()->kaiin_number;
      $now = Carbon::now()->addDay(3)->format('Y-m-d');
      $recommends = Recommend::where('user_id', $kaiin_number)->where('end', '>=', $now)->orWhere('end',null)->where('user_id', $kaiin_number)->get();
      $special_prices = SpecialPrice::get();
      return view('user/guide',
      ['carts' => $carts ,
       'setonagi' => $setonagi ,
       'categories' => $categories ,
       'favorite_categories' => $favorite_categories,
       'recommends' => $recommends,
       'special_prices' => $special_prices,
      ]);
  }
  public function law()
  {
      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->first();
        if ($favorite_categories === null) {
          $categories = Category::get();
          $categories = $categories->groupBy('bu_ka_name');
          // dd($categories);
          return view('user/auth/questionnaire', ['categories' => $categories]);
        }
      $categories = Category::get()->groupBy('bu_ka_name');
      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
      $carts =  Cart::where('user_id',$user_id)->get();
      $kaiin_number = Auth::guard('user')->user()->kaiin_number;
      $now = Carbon::now()->addDay(3)->format('Y-m-d');
      $recommends = Recommend::where('user_id', $kaiin_number)->where('end', '>=', $now)->orWhere('end',null)->where('user_id', $kaiin_number)->get();
      $special_prices = SpecialPrice::get();
      return view('user/law',
      ['carts' => $carts ,
       'categories' => $categories ,
       'favorite_categories' => $favorite_categories,
       'recommends' => $recommends,
       'special_prices' => $special_prices,
      ]);
  }
  public function privacypolicy()
  {
      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->first();
        if ($favorite_categories === null) {
          $categories = Category::get();
          $categories = $categories->groupBy('bu_ka_name');
          // dd($categories);
          return view('user/auth/questionnaire', ['categories' => $categories]);
        }
      $categories = Category::get()->groupBy('bu_ka_name');
      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
      $carts =  Cart::where('user_id',$user_id)->get();
      $kaiin_number = Auth::guard('user')->user()->kaiin_number;
      $now = Carbon::now()->addDay(3)->format('Y-m-d');
      $recommends = Recommend::where('user_id', $kaiin_number)->where('end', '>=', $now)->orWhere('end',null)->where('user_id', $kaiin_number)->get();
      $special_prices = SpecialPrice::get();
      return view('user/privacypolicy',
      ['carts' => $carts ,
       'categories' => $categories ,
       'favorite_categories' => $favorite_categories,
       'recommends' => $recommends,
       'special_prices' => $special_prices,
      ]);
  }


  public function setonagi()
  {
    if ( Auth::guard('admin')->check() ){
        Auth::guard('admin')->logout();
    }

      // $search = $request->search;
      // dd($search);

      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->first();

        if ($favorite_categories === null) {
          $categories = Category::get();
          $categories = $categories->groupBy('bu_ka_name');
          // dd($categories);
          return view('user/auth/questionnaire', ['categories' => $categories]);
        }
      $categories = Category::get()->groupBy('bu_ka_name');

      // $items = Item::where('zaikosuu', '>=', '0.01')->paginate(30);

      // foreach($setonagi_items as $setonagi_item){
      //   $setonagi_item = $setonagi_item->item()->item_name;
      // }

      $setonagi = Setonagi::where('user_id',$user_id)->first();

      if($setonagi){
        $shipping_code = Setonagi::where('user_id',$user_id)->first();
        if(isset($shipping_code->shipping_code)){
          $shipping_info = ShippingInfo::where('shipping_code', $shipping_code->shipping_code)->first();
          $setonagi_items = SetonagiItem::where('price_groupe', $shipping_info->price_groupe)->get();
        }else{
          $setonagi_items = SetonagiItem::where('price_groupe', '00000000001')->get();
        }
      }else{
        $setonagi_items = SetonagiItem::where('price_groupe', '00000000001')->get();
      }

      $items = [];
      $n=1;

      foreach ($setonagi_items as $setonagi_item) {
      $item = $setonagi_item->item();
      if($item){
        if($item->zaikosuu >= 0.01){
          array_push($items, $setonagi_item);
        }
      }
      $n++;
      }
      $setonagi_items = $items;

      $user_id = Auth::guard('user')->user()->id;

      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

      $favorite_items = Favorite::where('user_id', $user_id)->get();

      $carts =  Cart::where('user_id',$user_id)->get();

      $kaiin_number = Auth::guard('user')->user()->kaiin_number;

      $now = Carbon::now()->addDay(3)->format('Y-m-d');

      $recommends = Recommend::where('user_id', $kaiin_number)->where('end', '>=', $now)->orWhere('end',null)->where('user_id', $kaiin_number)->get();

      // $special_prices = SpecialPrice::get();
      $now = Carbon::now()->toDateTimeString();

      return view('user/setonagi',
      ['setonagi_items' => $setonagi_items,
       'carts' => $carts ,
       'categories' => $categories ,
       'favorite_categories' => $favorite_categories,
       'favorite_items' => $favorite_items,
       'recommends' => $recommends,
       // 'special_prices' => $special_prices,
       'now' => $now,
      ]);
  }


    public function search(Request $request)
    {

        $search = $request->search;

        $user_id = Auth::guard('user')->user()->id;
        $favorite_categories = FavoriteCategory::where('user_id', $user_id)->first();

        if ($favorite_categories === null) {
          $categories = Category::get();
          $categories = $categories->groupBy('bu_ka_name');
          return view('user/auth/questionnaire', ['categories' => $categories]);
        }

        $cat = $request->cat;
        if($cat == -1){
          $items = Item::Where('zaikosuu', '>=', '0.01')
          ->where(function($items) use($search){
            $items->where('item_name','like', "%$search%")->orWhere('item_id','like', "%$search%");
          })->paginate(30);

          // $items::where('item_name','like', "%$search%")->orWhere('item_id','like', "%$search%")->paginate(30);
          // $items = Item::Where('item_id',$search)->orWhere('item_name','like', "%$search%")->where('zaikosuu', '!=', '0')->paginate(30);
          $search_category = null;
          $search_category_parent = null;
        }elseif(is_numeric($cat)){
          $cat_id = $request->cat;
          $search_category = Category::where('category_id',$cat_id)->first();
          // $items = $search_category->items()->where('zaikosuu', '>=', '0.01')->Where('item_name','like', "%$search%")->paginate(30);

          $items = $search_category->items()->where('zaikosuu', '>=', '0.01')->Where('item_name','like', "%$search%")->paginate(30);
          // dd($items);

          $search_category_parent = null;
        }else{
          $items = Item::Where('busho_name',$cat)->Where('zaikosuu', '>=', '0.01')
          ->where(function($items) use($search){
            $items->where('item_name','like', "%$search%")->orWhere('item_id','like', "%$search%");
          })->paginate(30);
          $search_category = null;
          $search_category_parent = $cat;
        }

        $categories = Category::get()->groupBy('bu_ka_name');

        $user_id = Auth::guard('user')->user()->id;
        $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
        $carts =  Cart::where('user_id',$user_id)->get();
        $kaiin_number = Auth::guard('user')->user()->kaiin_number;
        $now = Carbon::now()->addDay(3)->format('Y-m-d');
        $recommends = Recommend::where('user_id', $kaiin_number)->where('end', '>=', $now)->orWhere('end',null)->get();
        // dd($categories);

        return view('user/auth/search',
        ['items' => $items ,
         'carts' => $carts ,
         'categories' => $categories ,
         'favorite_categories' => $favorite_categories,
         'recommends' => $recommends,
         'search' => $search,
         'search_category' => $search_category,
         'search_category_parent' => $search_category_parent,
        ]);
    }



  public function PostFavoriteCategory(Request $request){

    $favorite_categories = $request->input('favorite_category');
    // dd($favorite_categories);

    if(!isset($favorite_categories)){
      // dd("カートが空です");
      $categories = Category::get();
      $categories = $categories->groupBy('bu_ka_name');
      $data=[
        'categories' => $categories,
        'message' => 'カテゴリーが1つも選択されていません。',
      ];
      // dd($categories);
      return redirect()->route('questionnaire',$data);
    }



    $user_id = Auth::guard('user')->user()->id;



    foreach ($favorite_categories as $favorite_category) {
      $favorite_category=FavoriteCategory::firstOrNew(['user_id'=> $user_id , 'category_id'=> $favorite_category]);
      $favorite_category->save();
    }

    $user = User::where('id', $user_id)->first();
    $user->first_login = 1;
    $user->save();

    $data = "sucsess";
    return redirect()->route('firstguide',$data);
  }







  public function category($id)
  {

      $category = Category::where('category_id',$id)->first();

      // $category_items = CategoryItem::where('category_id',$id)->get();
      // dd($category_items);
      $items = Item::get();

      // $items = Tag::first()->items()->get();

      $items = Category::where('category_id',$id)->first()->items()->where('zaikosuu', '>=', '0.01')->paginate(30);

      $categories = Category::get()->groupBy('bu_ka_name');
      // $category_name = Category::where('category_id',$id)->first()->category_name;
      $category_name = Category::where('category_id',$id)->first()->category_name;
      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
      $carts =  Cart::where('user_id',$user_id)->get();

      $now = Carbon::now()->addDay(3)->format('Y-m-d');
      $recommendcategories = RecommendCategory::where('category_id', $category->category_id)->where('end', '>=', $now)->orWhere('end',null)->get();
      // dd($recommendcategories);


      return view('user/home', ['items' => $items , 'carts' => $carts , 'categories' => $categories ,  'category_name' => $category_name ,'favorite_categories' => $favorite_categories ,'recommendcategories' => $recommendcategories]);
  }


  public function test(Request $request){
    // 直近の納品予定日を取得
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');


    // dd($holidays);
    // 今日が休みの場合は無条件で19時以降の処理に飛ばす
    // $key = array_search($today,(array)$holidays,true);
    // dd($key);

    // 19時より前の処理
    if (strtotime($currentTime) < strtotime('17:00:00')) {
      // dd('test');
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 1; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
        // dd($today_plus);
        $key = array_search($today_plus,(array)$holidays,true);
        if($key){
            // 休みでないので納品日を格納
        }else{
            // 休みなので次の日付を探す
            $nouhin_yoteibi = $today_plus;
            break;
        }
      }
    }else{
    // 19時より後の処理
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 2; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));

        $key = array_search($today_plus,(array)$holidays,true);
                // dd($today_plus);
        if($key){
            // 休みでないので納品日を格納
        }else{
            // 休みなので次の日付を探す
            $nouhin_yoteibi = $today_plus;
            break;
        }
      }
    }
    // dd($nouhin_yoteibi);
    return redirect()->route('setonagi');
  }


  public function addcart(Request $request){

    $user = Auth::guard('user')->user();
    $user_id = Auth::guard('user')->user()->id;
    $item_id = $request->item_id;
    $item = Item::where('id',$item_id)->first();

    $setonagi_user = $user->setonagi;
    // dd($setonagi_user);

    // BtoB納品店舗取得
    if(!$setonagi_user){

      $kaiin_number = $user->kaiin_number;

      $store_user = StoreUser::where('user_id',$kaiin_number)->first(['store_id','tokuisaki_id']);

      $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();

      // $price_groupe = PriceGroupe::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
      //
      // $price = Price::where(['price_groupe'=>$price_groupe->price_groupe, 'item_id'=> $item->item_id , 'sku_code'=> $item->sku_code])->first();

    }



    // 既にカートに入っていないか確認
    $cart_in=Cart::where(['user_id'=> $user_id , 'item_id'=> $item->id , 'deal_id'=> null , 'addtype'=> 'addsetonagi'])->first();
    if($cart_in){
      return response()->json([
      'message' => 'cart_in',
      ]);
    }





    // HOMEから追加されるのは限定お買い得商品（セトナギ商品だけ）なのでaddtypeをaddsetonagiに変更
    $cart=Cart::firstOrNew(['user_id' => $user_id , 'item_id' => $item->id , 'deal_id' => null , 'addtype' => 'addsetonagi']);
    $cart->save();

    // 次の営業日を取得
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');
    // 17時より前の処理
    if (strtotime($currentTime) < strtotime('17:00:00')) {
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 1; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
        // dd($today_plus2);
        $key = array_search($today_plus,(array)$holidays,true);
        if($key){
          // 休みなので次の日付を探す
        }else{
          // 休みでないので納品日を格納
          $nouhin_yoteibi = $today_plus;
          break;
        }
      }
    }else{
    // 17時より後の処理
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 2; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
        // dd($today_plus2);
        $key = array_search($today_plus,(array)$holidays,true);
        if($key){
          // 休みなので次の日付を探す
        }else{
          // 休みでないので納品日を格納
          $nouhin_yoteibi = $today_plus;
          break;
        }
      }
    }



    // hiddenで値を持たせる
    // $setonagi_item_id = $request->setonagi_item_id;
    // if(isset($setonagi_item_id)){
    //   $setonagi_item = SetonagiItem::where(['id'=>$setonagi_item_id])->first();
    //   $price = $setonagi_item->price;
    //   $order=Order::firstOrNew(['cart_id'=> $cart->id , 'tokuisaki_name'=> $store->tokuisaki_name , 'store_name'=> $store->store_name , 'quantity'=> 1 , 'nouhin_yoteibi'=> $nouhin_yoteibi]);
    //   if(isset($price)){
    //   $order->price = $price;
    //   }
    // }else{
    //  $order=Order::firstOrNew(['cart_id'=> $cart->id , 'tokuisaki_name'=> $store->tokuisaki_name , 'store_name'=> $store->store_name , 'quantity'=> 1 , 'nouhin_yoteibi'=> $nouhin_yoteibi]);
    //  if(isset($price->price)){
    //  $order->price = $price->price;
    //  }
    // }


    // Log::debug($setonagi_user);
    // return Response::json($setonagi_user);

    // セトナギユーザーの場合は得意先を取得しない
    if(!$setonagi_user){
      $order=Order::firstOrNew(['cart_id'=> $cart->id , 'tokuisaki_name'=> $store->tokuisaki_name , 'store_name'=> $store->store_name , 'quantity'=> 1 , 'nouhin_yoteibi'=> $nouhin_yoteibi]);
      // $order=Order::firstOrNew(['cart_id'=> $cart->id , 'quantity'=> 1 , 'nouhin_yoteibi'=> $nouhin_yoteibi]);
      // if(isset($price->price)){
      // $order->price = $price->price;
      // }
    }else{
      $order=Order::firstOrNew(['cart_id'=> $cart->id , 'quantity'=> 1 , 'nouhin_yoteibi'=> $nouhin_yoteibi]);
    }



    // return Response::json($item);

    // // 市況商品価格上書き
    // $special_price_item = SpecialPrice::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])->first();
    // if(isset($special_price_item->price)){
    // $order->price = $special_price_item->price;
    // }



    // $user = Auth::guard('user')->user();
    // $now = Carbon::now();
    // if(!$setonagi_user){
    //   $buyer_recommends = [];
    //   $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');
    //   foreach ($tokuisaki_ids as $key => $value) {
    //     // 担当のおすすめ商品の価格を探す
    //     $buyer_recommend_item = BuyerRecommend::where('tokuisaki_id', $value->tokuisaki_id)
    //     ->where('price', '>=', '1')
    //     ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
    //     ->where('start', '<=' , $now)
    //     ->where('end', '>=', $now)
    //     ->orderBy('order_no', 'asc')->first();
    //     // dd($buyer_recommend_item);
    //     if(isset($buyer_recommend_item)){
    //       $order->price = $buyer_recommend_item->price;
    //     }
    //     // 市況商品の価格を探す
    //     $price_groupe = PriceGroupe::where(['tokuisaki_id'=>$value->tokuisaki_id,'store_id'=>$value->store_id])->first();
    //     $special_price_item = SpecialPrice::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'price_groupe'=>$price_groupe->price_groupe])
    //     ->where('start', '<=' , $now)
    //     ->where('end', '>=', $now)->first();
    //     if(isset($special_price_item)){
    //       $order->price = $special_price_item->price;
    //     }
    //   }
    // }




    // セトナギユーザーの場合取得
    if($setonagi_user){
      $shipping_code = Setonagi::where('user_id',$user_id)->first('shipping_code');
    }
    if(isset($shipping_code->shipping_code)){
      $shipping_info = ShippingInfo::where('shipping_code', $shipping_code->shipping_code)->first();
      $price_groupe_code = $shipping_info->price_groupe;
    }else{
      $price_groupe_code = '00000000001';
    }
    // セトナギ商品価格上書き
    $setonagi_item = SetonagiItem::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'price_groupe'=>$price_groupe_code])->first();
    if(isset($setonagi_item->price)){
    $order->price = $setonagi_item->price;
    }

    // 担当のおすすめ商品価格上書き
    // $recommend_item = Recommend::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'user_id'=>$user_id])->first();
    // if(isset($recommend_item->price)){
    // $order->price = $recommend_item->price;
    // }

    $order->save();

    $data = "sucsess";
    return $data;
  }


  public function addall(Request $request){

      $user = Auth::guard('user')->user();
      $user_id = $user->id;
      $setonagi_user = $user->setonagi;
      $addtype = $request->addtype;

      // 日時
      $now = Carbon::now()->toDateTimeString();

      // BtoBの会員情報を取得
      if(!$setonagi_user){

        // 会員番号
        $kaiin_number = $user->kaiin_number;

        // デフォルトでセットを行う店舗を取得
        $store_user = StoreUser::where('user_id',$kaiin_number)->first(['store_id','tokuisaki_id']);
        $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();

        // 得意先IDを取得
        $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');

        // 価格グループを取得
        $price_groupe = PriceGroupe::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
      }

      // BtoSBユーザーごとのおすすめ商品取得
      if($setonagi_user){
      $recommends = Recommend::where('user_id', $user_id)
        ->where('start', '<=' , $now)
        ->where('end', '>=', $now)->get();
      }

      // カートに追加する商品をaddtypeごとに取得
      if($addtype == 'addsetonagi'){
        // 限定お買い得商品を取得
        $setonagi_items = SetonagiItem::where('start_date', '<=' , $now)->where('end_date', '>=', $now)->get();
        $get_items = $setonagi_items;
      }elseif($addtype == 'addbuyerrecommend'){
        // 得意先のおすすめ商品を取得
        if(!$setonagi_user){
          // 法人会員
          $kaiin_number = $user->kaiin_number;
          $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');
          $buyer_recommends = [];
          foreach ($tokuisaki_ids as $key => $value) {
            $buyer_recommend_loop = BuyerRecommend::where('tokuisaki_id', $value->tokuisaki_id)
            ->whereNotNull('nouhin_end')
            ->where('price', '>=', '1')
            ->where('start', '<=' , $now)
            ->where('end', '>=', $now)
            ->orderByRaw('CAST(buyer_recommends.order_no AS UNSIGNED) asc')
            ->get();
            $buyer_recommends = collect($buyer_recommends)->merge($buyer_recommend_loop);
          }
          $get_items = $buyer_recommends;
        }else{
          // セトナギ会員
          // dd($user_id);
          $recommends = Recommend::where('user_id', $user_id)
          ->where('start', '<=' , $now)
          ->where('end', '>=', $now)
          ->orderBy('order_no', 'asc')->get();
          $get_items = $recommends;
          // dd($get_items);
          // dd($get_items);
        }
      }elseif($addtype == 'addspecialprice'){
        // 市況商品を取得
        if(!$setonagi_user){
          // 法人会員
          $kaiin_number = Auth::guard('user')->user()->kaiin_number;
          $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');
          $special_prices = [];
          foreach ($tokuisaki_ids as $key => $value) {
            $price_groupe = PriceGroupe::where(['tokuisaki_id'=>$value->tokuisaki_id,'store_id'=>$value->store_id])->first();
            $special_prices_loop = SpecialPrice::where(['price_groupe'=>$price_groupe->price_groupe])
            ->where('start', '<=' , $now)
            ->where('end', '>=', $now)->get();
            $special_prices = collect($special_prices)->merge($special_prices_loop);
          }
          $get_items = $special_prices;
        }else{
          // セトナギユーザー
          $get_items = SpecialPrice::where(['price_groupe'=>'10000000005'])->get();
        }
      }



      // 在庫がある商品だけを取得（addbuyerrecommendば全て入れる）
      $items = [];
      $n=1;
      foreach ($get_items as $get_item) {
      $item = $get_item->item();
      if($item){
        if($addtype == 'addbuyerrecommend'){
            array_push($items, $get_item);
        }else{
          if($item->zaikosuu >= 0.01){
            array_push($items, $get_item);
          }
        }
      }
      $n++;
      }
      $get_items = $items;

      // カートに1つでも商品があるか判定
      // $carts =  Cart::where('user_id',$user_id)->get();

      // 次の営業日を取得
      $today = date("Y-m-d");
      $holidays = Holiday::pluck('date');
      $currentTime = date('H:i:s');
      // 17時より前の処理
      if (strtotime($currentTime) < strtotime('17:00:00')) {
        $holidays = Holiday::pluck('date')->toArray();
        for($i = 1; $i < 10; $i++){
          $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
          // dd($today_plus2);
          $key = array_search($today_plus,(array)$holidays,true);
          if($key){
            // 休みなので次の日付を探す
          }else{
            // 休みでないので納品日を格納
            $nouhin_yoteibi = $today_plus;
            break;
          }
        }
      }else{
      // 17時より後の処理
        $holidays = Holiday::pluck('date')->toArray();
        for($i = 2; $i < 10; $i++){
          $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
          // dd($today_plus2);
          $key = array_search($today_plus,(array)$holidays,true);
          if($key){
            // 休みなので次の日付を探す
          }else{
            // 休みでないので納品日を格納
            $nouhin_yoteibi = $today_plus;
            break;
          }
        }
      }

      // ここからカートへの追加処理を商品ごとに行う（限定お買い得商品は追加しない）
      if($addtype == 'addsetonagi'){
        }else{
        foreach ($get_items as $get_item) {

          // アイテム情報を取得
          $item = Item::where([ 'item_id'=>$get_item->item_id,'sku_code'=> $get_item->sku_code ])->first();

          if(!$setonagi_user){
            // 価格グループから取得ができる商品価格を取得
            $price = Price::where(['price_groupe'=>$price_groupe->price_groupe, 'item_id'=> $item->item_id , 'sku_code'=> $item->sku_code])->first();
          }

          // 商品ごとに商品価格の上書きを始める

          // 法人会員
          // if(!$setonagi_user){
          //   // 担当のおすすめ商品の場合の上書き項目を取得
          //   if($addtype == 'addbuyerrecommend'){
          //     foreach ($tokuisaki_ids as $key => $value) {
          //       $buyerrecommend = BuyerRecommend::where('tokuisaki_id', $value->tokuisaki_id)
          //       ->where('item_id' , $item->item_id)
          //       ->where('sku_code' , $item->sku_code)
          //       ->where('price', '>=', '1')
          //       ->where('start', '<=' , $now)
          //       ->where('end', '>=', $now)->first();
          //       if(isset($buyerrecommend)){
          //         break;
          //       }
          //     }
          //     $groupe = $buyerrecommend->groupe;
          //     $uwagaki_item_name = $buyerrecommend->uwagaki_item_name;
          //     $uwagaki_kikaku = $buyerrecommend->uwagaki_kikaku;
          //   }
          // }

          if(!$setonagi_user){
            if($addtype == 'addbuyerrecommend'){
              $groupe = $get_item->groupe;
              $uwagaki_item_name = $get_item->uwagaki_item_name;
              $uwagaki_kikaku = $get_item->uwagaki_kikaku;
            }
          }else{
            $groupe = $item->busho_name;
          }

          // 市況商品の場合は、部署名をグループ名とする
          if($addtype == 'addspecialprice'){
            $groupe = $item->busho_name;
          }

          if($addtype == 'addbuyerrecommend'){
            // カート情報を保存（BtoSBだとグループとかがない）
            if(!$setonagi_user){
              $cart=Cart::firstOrNew(['user_id'=> $user_id, 'item_id'=> $item->id, 'deal_id'=> null, 'addtype'=> $addtype, 'groupe'=> $groupe, 'uwagaki_item_name'=> $uwagaki_item_name, 'uwagaki_kikaku'=> $uwagaki_kikaku]);
            }else{
              $cart=Cart::firstOrNew(['user_id'=> $user_id , 'item_id'=> $item->id , 'deal_id'=> null, 'addtype'=> $addtype , 'groupe'=>$groupe]);
            }
          }else{
            // カート情報を保存
            $cart=Cart::firstOrNew(['user_id'=> $user_id , 'item_id'=> $item->id , 'deal_id'=> null, 'addtype'=> $addtype , 'groupe'=>$groupe]);
          }
          $cart->save();
          // dd($cart);
          // オーダー情報を保存
          // BtoBユーザーの場合は、オーダーに納品予定日と得意先名を保存

          if($addtype == 'addbuyerrecommend'){
            // if($get_item->hidden_price){
            //   $price = '-';
            // }else{
            //   $price = $get_item->price;
            // }
            $price = $get_item->price;
          }
          if($addtype == 'addspecialprice'){
            $price = $get_item->price;
          }


          $order=Order::firstOrNew(['cart_id'=> $cart->id]);
          if($addtype == 'addbuyerrecommend'){
            if($get_item->hidden_price){
              $order->hidden_price = '-';
            }
          }
          $order->price = $price;
          if(!$setonagi_user){
            $order->tokuisaki_name = $store->tokuisaki_name;
            $order->store_name = $store->store_name;
          }
          $order->nouhin_yoteibi = $nouhin_yoteibi;
          $order->save();
          $set_order = $order;
        }
      }


      // 任意の商品の納品先店舗と予定日を変更
      if(!$setonagi_user){
        if(isset($order)){
          $cart_ninis =  CartNini::where(['user_id'=>$user_id, 'deal_id'=> null])->get();
          foreach ($cart_ninis as $cart_nini) {
            $order_ninis = OrderNini::where(['cart_nini_id'=> $cart_nini->id])->get();
            foreach ($order_ninis as $order_nini) {
              $order_nini->tokuisaki_name = $order->tokuisaki_name;
              $order_nini->store_name = $order->store_name;
              $order_nini->nouhin_yoteibi = $order->nouhin_yoteibi;
              $order_nini->save();
            }
          }
        }
      }

      if($addtype == 'addsetonagi'){
          $cart = Cart::where(['user_id'=>$user_id, 'deal_id'=> null])->first();
          $order = Order::where(['cart_id'=>$cart->id])->first();
      }

      if($addtype == 'addbuyerrecommend' || $addtype == 'addspecialprice'){
        if(!isset($order)){
          $data=[
            'message'=>'商品が見つかりませんでした。',
          ];
          return redirect()->route('bulk',$data);
        }
      }

      if(!$setonagi_user && !$addtype == 'addsetonagi'){
        $data=[
          'change_all_nouhin_yoteibi'=>$order->nouhin_yoteibi,
          'set_tokuisaki_name'=>$order->tokuisaki_name,
          'change_all_store'=>$order->store_name,
          'addtype'=>$addtype,
        ];
      }else{
        $data=[
          'addtype'=>$addtype,
        ];
      }
      // dd($data);
      return redirect()->route('confirm',$data);
  }


  public function updateorder(Request $request){

    $prices = $request->array;

    $kekka=1;
    foreach($prices as $key => $value) {
    $order = Order::where(['id'=> $key , 'price'=> $value])->first();
    if ($order){
    }else{
    $kekka=0;
    }
    }

    return ($kekka);
  }



  public function updateordergtest(Request $request){

    // dd($request->order_id);

    $prices = array_combine($request->order_id, $request->price);
    // dd($prices);

    $kekka=1;
    foreach($prices as $key => $value) {
    $order = Order::where(['id'=> $key , 'price'=> $value])->first();
    if ($order){
    }else{
    $kekka=0;
    }
    }
    dd($kekka);

    return ($kekka);
  }





  // カート画面から削除
  public function removecart(Request $request){
    $cart_id = $request->cart_id;
    $delete_cart = Cart::where(['id'=> $cart_id])->first()->delete();
    $delete_order = Order::where(['cart_id'=> $cart_id])->first()->delete();
    $data = "sucsess";
    return redirect()->route('setonagi',$data);
  }



  // カートの中身を表示
  public function cart(){
    $user_id = Auth::guard('user')->user()->id;
    $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> null , 'addtype' => 'addsetonagi'])->get();
    // dd($carts);
    // $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> null ])->get();
    $data=[
      'carts'=>$carts,
    ];
    return view('cart', $data);
  }



  public function dealcart(Request $request){
    $id = $request->deal_id;
    $user_id = Auth::guard('user')->user()->id;
    $deal = Deal::where('id',$id)->first();
    $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> $id])->get();
    $data=[
      'carts'=>$carts,
      'deal'=>$deal
    ];
    return view('dealcart', $data);
  }



  public function confirm(Request $request){

    // dd($request);

    $change_all_store = $request->change_all_store;
    $set_tokuisaki_name = $request->set_tokuisaki_name;
    $change_all_nouhin_yoteibi = $request->change_all_nouhin_yoteibi;

    $addtype = $request->addtype;

    $show_favorite = $request->show_favorite;

    $categories = Category::get()->groupBy('bu_ka_name');

    $user = Auth::guard('user')->user();
    $user_id = $user->id;
    $setonagi = $user->setonagi;
    // $setonagi_uketori_place = $setonagi->uketori_place;
    // dd($setonagi_uketori_place);
    // BtoC用の配送コードを取得

    if(isset($setonagi)){
      $shipping_code = Setonagi::where('user_id',$user_id)->first('shipping_code');
      // 配送設定を呼び出し
      if(isset($shipping_code)){
        $shipping_code = $shipping_code->shipping_code;
      }else{
        // BtoSB
        $shipping_code = null;
      }
    }else{
      $shipping_code = null;
    }

    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

    if(isset($addtype)){
      $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> null , 'addtype'=>$addtype ])->get();
    }else{
      $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> null])->get();
    }

    if($carts->isNotEmpty()) {
      // foreach ($carts as $cart) {
      //   $set_order = Order::where(['cart_id'=>$cart->id])->first();
      // }
    }else{
      $data=[
        'message' => 'カートが空です。',
      ];
      return redirect()->route('setonagi',$data);
    }

    //
    // if(!$setonagi){
    // $kaiin_number = $user->kaiin_number;
    // $stores = [];
    // $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get();
    // foreach ($tokuisaki_ids as $key => $value) {
    //   $stores_loop = Store::where(['tokuisaki_id'=>$value->tokuisaki_id,'store_id'=>$value->store_id])->first();
    //   array_push($stores, $stores_loop);
    //   // $stores = collect($stores);
    // }
    // }else{
    //   $stores=null;
    // }
    //
    // // 直近の納品予定日を取得
    // $today = date("Y-m-d");
    // $holidays = Holiday::pluck('date');
    // $currentTime = date('H:i:s');
    // // 19時より前の処理
    // if (strtotime($currentTime) < strtotime('17:00:00')) {
    //   $holidays = Holiday::pluck('date')->toArray();
    //   for($i = 1; $i < 10; $i++){
    //     $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
    //     // dd($today_plus2);
    //     $key = array_search($today_plus,(array)$holidays,true);
    //     if($key){
    //         // 休みでないので納品日を格納
    //     }else{
    //         // 休みなので次の日付を探す
    //         $nouhin_yoteibi = $today_plus;
    //         break;
    //     }
    //   }
    // }else{
    // // 19時より後の処理
    //   $holidays = Holiday::pluck('date')->toArray();
    //   for($i = 2; $i < 10; $i++){
    //     $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
    //     // dd($today_plus2);
    //     $key = array_search($today_plus,(array)$holidays,true);
    //     if($key){
    //         // 休みでないので納品日を格納
    //     }else{
    //         // 休みなので次の日付を探す
    //         $nouhin_yoteibi = $today_plus;
    //         break;
    //     }
    //   }
    // }
    // $sano_nissuu = $nouhin_yoteibi;
    //
    // $all_nouhin_end = null;


    // 直近の納品予定日を取得
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');



    $today = date('Y-m-d');
    // dd($holidays);
    // 今日が休みの場合は無条件で19時以降の処理に飛ばす
    $key = array_search($today,(array)$holidays,true);
    // dd($key);

    // 19時より前の処理
    if (strtotime($currentTime) < strtotime('17:00:00')) {
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 1; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
        // dd($today_plus);
        $key = array_search($today_plus,(array)$holidays,true);
        if($key){
            // 休みでないので納品日を格納
        }else{
            // 休みなので次の日付を探す
            $nouhin_yoteibi = $today_plus;
            break;
        }
      }
    }else{
    // 19時より後の処理
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 2; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
        // dd($today_plus2);
        $key = array_search($today_plus,(array)$holidays,true);
        if($key){
            // 休みでないので納品日を格納
        }else{
            // 休みなので次の日付を探す
            $nouhin_yoteibi = $today_plus;
            break;
        }
      }
    }



    // dd($nouhin_yoteibi);
    // 'url': url,
    // 'nouhin_yoteibi': nouhin_yoteibi,
    // 'tokuisaki_name': tokuisaki_name,
    // 'store_name': store_name,


    return view('user/auth/confirm',
    ['carts' => $carts,
     'categories' => $categories,
     'favorite_categories' => $favorite_categories,
     // 'all_nouhin_end' => $all_nouhin_end,
     // 'stores' => $stores,
     // 'sano_nissuu' => $sano_nissuu,
     'holidays' => $holidays,
     'user_id' => $user_id,
     'user' => $user,
     'setonagi' => $setonagi,
     'nouhin_yoteibi' => $nouhin_yoteibi,
     'addtype' => $addtype,
     'show_favorite' => $show_favorite,

     'change_all_store' => $change_all_store,
     'set_tokuisaki_name' => $set_tokuisaki_name,
     'change_all_nouhin_yoteibi' => $change_all_nouhin_yoteibi,
     'shipping_code' => $shipping_code,

    ]);

  }

  public function approval(Request $request){

    // dd($request);

    $data = $request->all();
    // dd($data);

    // 現在の時刻を取得
    $current_time = date('Y-m-d H:i:s');

    $addtype = $request->addtype;
    $change_all_store = $request->change_all_store;
    $change_all_nouhin_yoteibi = $request->change_all_nouhin_yoteibi;
    $set_tokuisaki_name = $request->set_tokuisaki_name;

    if(!$request->has('cart_id') && !$request->has('cart_nini_id')){
      $data=[
        'addtype' => $addtype,
        'change_all_store' => $change_all_store,
        'change_all_nouhin_yoteibi' => $change_all_nouhin_yoteibi,
        'set_tokuisaki_name' => $set_tokuisaki_name,
        'message' => 'カートが空です。',
      ];
      return redirect()->route('confirm',$data);
    }

    if(count(array_filter($request->quantity)) == 0 ){
      $data=[
        'addtype' => $addtype,
        'change_all_store' => $change_all_store,
        'change_all_nouhin_yoteibi' => $change_all_nouhin_yoteibi,
        'set_tokuisaki_name' => $set_tokuisaki_name,
        'message' => 'カートが空です。',
      ];
      return redirect()->route('confirm',$data);
    }

    // $show_favorite = $request->show_favorite;
    $show_favorite = null;
    $addtype = $request->addtype;

    if(!$request->has('cart_id') and !$request->has('cart_nini_id')){
      $data=[
        'addtype' => $addtype,
        'change_all_store' => $change_all_store,
        'change_all_nouhin_yoteibi' => $change_all_nouhin_yoteibi,
        'set_tokuisaki_name' => $set_tokuisaki_name,
        'message' => 'カートが空です。',
      ];
      return redirect()->route('confirm',$data);
    }


    if($request->setonagi_id){
      $setonagi = Setonagi::where('id',$request->setonagi_id)->first();
      // $setonagi = 1;
      $setonagi->uketori_place = $request->uketori_place;
      $setonagi->uketori_time = $request->uketori_time;
      $setonagi->uketori_siharai = $request->uketori_siharai;
      $setonagi->save();
    }

    $categories = Category::get()->groupBy('bu_ka_name');
    $user_id = Auth::guard('user')->user()->id;
    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
    $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> null])->get();



    $memo = $request->memo; //チェックしたい本文
    if($memo){
      $ng_words = array('"',',','#','!','$','%','&','=',';',':','?','+'); //禁止ワード
      $flg = 0;
      foreach( $ng_words as $word ){
          if(strpos($memo, $word) !== false){
              $flg = 1;
              break;
          }
      }
      if($flg){
        $data=[
          'addtype' => $addtype,
          'change_all_store' => $change_all_store,
          'change_all_nouhin_yoteibi' => $change_all_nouhin_yoteibi,
          'set_tokuisaki_name' => $set_tokuisaki_name,
          'message' => '通信欄に入力できない文字が含まれています。',
        ];
        return redirect()->route('confirm',$data);
      }
      $user = Auth::guard('user')->user();
      $user->memo = $memo;
      $user->save();
    }


    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $approval = 1;
    $user = Auth::guard('user')->user();

    $url = 'approval';
    // $memo = $request->memo;
    // dd($memo);

    $data =
    ['user' => $user,
     'carts' => $carts,
     'categories' => $categories,
     'favorite_categories' => $favorite_categories,
     // 'stores' => $stores,
     'holidays' => $holidays,
     'user_id' => $user_id,
     'approval' => $approval,
     'addtype' => $addtype,
     'url' => $url,
     'show_favorite' => $show_favorite,
     'change_all_nouhin_yoteibi' => $change_all_nouhin_yoteibi,
     'change_all_store' => $change_all_store,
     'set_tokuisaki_name' => $set_tokuisaki_name,
     'current_time' => $current_time,
     // 'memo' => $memo,
    ];

    return view('user/auth/approval',$data);

    // return view('user/auth/approval',
    // ['carts' => $carts,
    //  'categories' => $categories,
    //  'favorite_categories' => $favorite_categories,
    //  // 'stores' => $stores,
    //  'holidays' => $holidays,
    //  'user_id' => $user_id,
    //  'approval' => $approval,
    //  'memo' => $memo,
    // ]);

  }


// カート画面からの遷移先
  public function order(Request $request){




    // 会員情報を取得
    $user = Auth::guard('user')->user();
    $user_id = $user->id;
    $setonagi = Setonagi::where('user_id',$user->id)->first();

    // BtoBユーザーの会員情報を取得
    if(!$setonagi){
      $kaiin_number = $user->kaiin_number;
      $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');
      $store_users = StoreUser::where('user_id',$kaiin_number)->get(['store_id','tokuisaki_id']);
    }
    // オーダー画面の変数を格納
    if(isset($request->addtype)){
      $addtype = $request->addtype;
    }

    if(isset($request->tokuisaki_name)){
      $tokuisaki_name = $request->tokuisaki_name;
    }else{
      $tokuisaki_name = null;
    }

    if(isset($request->store_name)){
      $store = $request->store_name;
    }else{
      $store = null;
    }

    if(isset($request->nouhin_yoteibi)){
      $nouhin_yoteibi = $request->nouhin_yoteibi;
    }else{
      $nouhin_yoteibi = null;
    }

    if(isset($request->url)){
      $url = $request->url;
    }else{
      $url = null;
    }

    $show_favorite = $request->show_favorite;

    // 時間を取得
    $now = Carbon::now();





    // BtoC用の配送コードを取得
    if($setonagi){
      $shipping_code = Setonagi::where('user_id',$user_id)->first('shipping_code');
      $shipping_code = $shipping_code->shipping_code;
      // 配送設定を呼び出し
      if(isset($shipping_code)){
        $shipping_settings = ShippingSetting::where('shipping_code',$shipping_code)->get();
      }else{
        // BtoSB
        $shipping_code = null;
        $shipping_settings = null;
      }
    }else{
      // BtoB
      $shipping_code = null;
      $shipping_settings = null;
    }
    // 価格グループコードを分岐
    if(isset($shipping_code)){
      // 価格グループコードを配送コードごとに分岐
      $shipping_info = ShippingInfo::where('shipping_code', $shipping_code)->first();
      $price_groupe_code = $shipping_info->price_groupe;
    }else{
      $price_groupe_code = '00000000001';
    }

    // toC納品予定日の再設定を回避
    if($url == 'approval' && isset($shipping_code) || isset($shipping_code) && isset($request->nouhin_yoteibi)){
      $c_nouhin_yoteibi = $request->nouhin_yoteibi;
    }

    // toB納品予定日の再設定を回避
    if(!$setonagi && isset($request->nouhin_yoteibi)){
      $b_nouhin_yoteibi = $request->nouhin_yoteibi;
    }


    // 直近の納品予定日を取得
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');
    // 19時より前の処理
    if (strtotime($currentTime) < strtotime('17:00:00')) {
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 1; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
        // dd($today_plus2);
        $key = array_search($today_plus,(array)$holidays,true);
        if($key){
            // 休みでないので納品日を格納
        }else{
            // 休みなので次の日付を探す
            $nouhin_yoteibi = $today_plus;
            break;
        }
      }
    }else{
    // 19時より後の処理
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 2; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
        // dd($today_plus2);
        $key = array_search($today_plus,(array)$holidays,true);
        if($key){
            // 休みでないので納品日を格納
        }else{
            // 休みなので次の日付を探す
            $nouhin_yoteibi = $today_plus;
            break;
        }
      }
    }
    $sano_nissuu = $nouhin_yoteibi;


    // BtoC用納品予定日の再設定を回避
    if($url == 'approval' && isset($shipping_code) || isset($shipping_code) && isset($request->nouhin_yoteibi)){
      $nouhin_yoteibi = $c_nouhin_yoteibi;
    }

    // BtoB用納品予定日の再設定を回避
    if(!$setonagi && isset($request->nouhin_yoteibi)){
      $nouhin_yoteibi = $b_nouhin_yoteibi;
    }

// Log::debug($nouhin_yoteibi);

    // toC納品予定日の再設定を回避（order_update）
    // if(isset($shipping_code) && isset($request->change_all_nouhin_yoteibi)){
    //   $nouhin_yoteibi = $c_nouhin_yoteibi;
    // }


    if(isset($addtype)){
      if(isset($store) && isset($nouhin_yoteibi) && !isset($setonagi) || isset($setonagi)){
        if(!$setonagi){
          $store = Store::where(['store_name'=>$store, 'tokuisaki_name'=> $tokuisaki_name])->first();
          $tokuisaki_id = $store->tokuisaki_id;
          $store_name = $store->store_name;
        }
        // 限定お買い得商品を利用する場合
        if($addtype == 'addsetonagi'){
          $carts = Cart::join('items', 'carts.item_id', '=', 'items.id')
          ->join('setonagi_items', function ($join) {
              $join->on('setonagi_items.item_id', '=', 'items.item_id')
                   ->on('setonagi_items.sku_code', '=', 'items.sku_code');
          })
          ->where(['carts.user_id'=>$user_id, 'deal_id'=> null, 'addtype'=>$addtype , 'setonagi_items.price_groupe' => $price_groupe_code])
          ->where('items.zaikosuu', '>=', 0.1)
          ->get(['carts.*']);
          $groupedItems = $carts->groupBy('groupe');
          // dd($groupedItems);
        }elseif($addtype == 'addbuyerrecommend'){
          // 担当のおすすめ商品を取得
          // $carts = Cart::where(['carts.user_id'=>$user_id, 'deal_id'=> null, 'addtype'=>$addtype])->get();
          // dd($carts);

          if(!$setonagi){
            $carts = Cart::join('items', 'carts.item_id', '=', 'items.id')

            ->join('buyer_recommends', function ($join) {
                $join->on('buyer_recommends.item_id', '=', 'items.item_id')
                     ->on('buyer_recommends.sku_code', '=', 'items.sku_code')
                     ->on('buyer_recommends.uwagaki_item_name', '=', 'carts.uwagaki_item_name')
                     ->on('buyer_recommends.uwagaki_kikaku', '=', 'carts.uwagaki_kikaku');
            })

            ->where('buyer_recommends.tokuisaki_id', $tokuisaki_id)

            ->where('buyer_recommends.price', '>=', 1)
            ->where(function ($query) use ($store_name) {
                $query->Where('buyer_recommends.gentei_store', $store_name)
                      ->orWhereNull('buyer_recommends.gentei_store');
            })

            ->where(['carts.user_id'=>$user_id, 'deal_id'=> null, 'addtype'=>$addtype])

            ->where('buyer_recommends.start', '<=', $now)
            ->where('buyer_recommends.end', '>=', $now)
            ->where('buyer_recommends.nouhin_end', '>=', $nouhin_yoteibi)

            // 下記の条件に直してくれ、 buyer_recommends.gentei_store が store_name と一致するレコードが取得しているものを取得。また、buyer_recommends.gentei_storeがnullのものも取得を行うが、取得をする際の条件として、既に同じitem_idのものがアイテム内に存在している場合は、取得をしない。

            // ここに途中で、同じitem_idを持つアイテムがあるか確認して、もしある場合は、gentei_storeの値がnullの方を削除したい

            // ->where('buyer_recommends.zaikokanri' , null)
            // ->where('buyer_recommends.zaikosuu', '>=', 0.1)
            // ->where('buyer_recommends.zaikokanri' , 1)
            // ->orwhere(function ($query) use ($store_name) {
            //     $query->Where('buyer_recommends.zaikokanri' , null)
            //           ->Where('buyer_recommends.zaikosuu', '>=', 0.1);
            // })

            ->orderByRaw('CAST(buyer_recommends.order_no AS UNSIGNED) asc')
            ->select('carts.*', 'buyer_recommends.gentei_store', DB::raw('IF(buyer_recommends.zaikokanri IS NULL AND buyer_recommends.zaikosuu >= 0, buyer_recommends.zaikosuu, IF(buyer_recommends.zaikokanri = 1, 999, items.zaikosuu)) AS zaikosuu'))
            ->get();

            // dd($store_name);

            $carts = $carts->filter(function ($cart) use ($carts) {
                $count = $carts->where('item_id', $cart->item_id)->count();
                return $count === 1 || (!is_null($cart->gentei_store) && $count > 1);
            });

            foreach ($carts as $cart) {
              $cart->zaikosuu = floatval($cart->zaikosuu);
            }
          }else{
            // BtoSBの価格グループを取得
            // $price_groupe = '10000000005';
            $carts = Cart::join('items', 'carts.item_id', '=', 'items.id')
            ->join('recommends', function ($join) {
                $join->on('recommends.item_id', '=', 'items.item_id')
                     ->on('recommends.sku_code', '=', 'items.sku_code');
            })
            ->where(['carts.user_id'=>$user_id, 'deal_id'=> null, 'addtype'=>$addtype])
            ->where('recommends.start', '<=' , $now)
            ->where('recommends.end', '>=', $now)
            ->where('items.zaikosuu', '>=', 0.1)
            ->selectRaw('carts.*, items.zaikosuu as zaikosuu') // "items.zaikosuu"の値を取得
            ->get();
            // ->get(['carts.*']);
            // foreach ($carts as $cart) {
            //   $cart->zaikosuu = floatval($cart->zaikosuu);
            // }
            // $addtype_items = $carts;
          }
          $groupedItems = $carts->groupBy('groupe');

          // dd($carts);
          // dd($groupedItems);


        }elseif($addtype == 'addspecialprice'){
          // 市況商品を取得
          if($setonagi){
            // BtoSBの価格グループを取得
            $price_groupe = '10000000005';
          }else{
            // BtoBの価格グループを取得
            $price_groupe = PriceGroupe::where(['tokuisaki_id'=>$store->tokuisaki_id,'store_id'=>$store->store_id])->first()->price_groupe;
            // dd($price_groupe);
          }
          $carts = Cart::join('items', 'carts.item_id', '=', 'items.id')
          ->join('special_prices', function ($join) {
              $join->on('special_prices.item_id', '=', 'items.item_id')
                   ->on('special_prices.sku_code', '=', 'items.sku_code');
          })
          ->where(['carts.user_id'=>$user_id, 'deal_id'=> null, 'addtype'=>$addtype])
          ->where('special_prices.price_groupe',$price_groupe)
          ->where('special_prices.start', '<=' , $now)
          ->where('special_prices.end', '>=', $now)
          ->where('special_prices.nouhin_end', '>=', $nouhin_yoteibi)
          ->where('items.zaikosuu', '>=', 0.1)
          ->get(['carts.*']);
          $addtype_items = $carts;
          $groupedItems = $carts->groupBy('groupe');
        }else{
          $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> null, 'addtype'=>$addtype])->get();
          $groupedItems = $carts->groupBy('groupe');
        }
      }else{
        $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> null , 'addtype'=> $addtype])->get();
        $groupedItems = null;
      }
    }else{
      $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> null , 'addtype'=> $addtype])->get();
      $groupedItems = null;
    }


    // 納品予定日を変更
    if($setonagi){
      // $shipping_code = Setonagi::where('user_id',$user_id)->first('shipping_code');
      // $shipping_code = $shipping_code->shipping_code;
      // BtoCの納品予定日を変更
      if(isset($shipping_code) && isset($nouhin_yoteibi)){
        foreach ($carts as $cart) {
          // オーダー内容を保存
          $order = Order::where(['cart_id'=> $cart->id])->first();
          $order->nouhin_yoteibi = $nouhin_yoteibi;
          $order->save();
        }
      }
    }

    // 納品先・納品予定日を変更
    if(!$setonagi){
      if(isset($store) && isset($nouhin_yoteibi)){
        foreach ($carts as $cart) {
          // オーダー内容を保存
          $order = Order::where(['cart_id'=> $cart->id])->first();
          $order->store_name = $store_name;
          $order->tokuisaki_name = $tokuisaki_name;
          $order->nouhin_yoteibi = $nouhin_yoteibi;
          $order->save();
        }

        $cart_ninis = CartNini::where(['user_id' => $user_id , 'deal_id' => null])->get();
        if($cart_ninis->count() > 0) {

          foreach ($cart_ninis as $cart_nini) {
            // オーダー内容を保存

            $ordernini = OrderNini::where(['cart_nini_id'=> $cart_nini->id])->first();
            $ordernini->nouhin_yoteibi = $nouhin_yoteibi;
            $ordernini->save();
          }
        }
      }
    }


    // Log::debug($carts);

    if($carts->isNotEmpty()) {
      foreach ($carts as $cart) {
        $set_order = Order::where(['cart_id'=>$cart->id])->first();
        // dd($set_order);
        $message = null;
        if($set_order){
          break;
        }
      }
    }else{
      $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> null , 'addtype'=> $addtype])->get();
      foreach ($carts as $cart) {
        $set_order = Order::where(['cart_id'=>$cart->id])->first();
        $message = null;
        if($set_order){
          break;
        }
      }
      // $set_order = null;
      $message = '商品が取得できませんでした。';
    }



    if(!$setonagi){
      $cart_ninis = CartNini::where(['user_id'=>$user_id, 'deal_id'=> null])->get();
    }else{
      $cart_ninis = null;
    }

    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');


    // 納品予定日を取得
    $currentDate = Carbon::now();
    $oneMonthLater = $currentDate->addMonth();

    // 1ヶ月後をフォーマット指定
    $oneMonthLaterFormatted = $oneMonthLater->format('Y-m-d');
    $all_nouhin_end = $oneMonthLaterFormatted;




    if(!$setonagi){
      $stores = [];
      foreach ($store_users as $store_user) {
      $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
        array_push($stores, $store);
      }
    }else{
      $stores = null;
    }



    // dd($stores);


    // 表示可能な得意先のおすすめ商品を取得
    // $now = Carbon::now();
    // $kaiin_number = Auth::guard('user')->user()->kaiin_number;
    // $buyer_recommends = [];
    // $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');
    // foreach ($tokuisaki_ids as $key => $value) {
    //   // dd($value->tokuisaki_id);
    //   $buyer_recommend_loop = BuyerRecommend::where('tokuisaki_id', $value->tokuisaki_id)
    //   ->whereNotNull('nouhin_end')
    //   ->where('price', '>=', '1')
    //   ->where('start', '<=' , $now)
    //   ->where('end', '>=', $now)
    //   ->orderBy('order_no', 'asc')->get();
    //   // array_push($buyer_recommends, $buyer_recommend_loop);
    //   $buyer_recommends = collect($buyer_recommends)->merge($buyer_recommend_loop);
    // }



    // dd($buyer_recommends);

    // 表示可能な担当のおすすめ商品を取得
    // $recommends = Recommend::where('user_id', $user_id)
    // ->where('price', '>=', '1')
    // ->where('start', '<=' , $now)
    // ->where('end', '>=', $now)
    // ->orderBy('order_no', 'asc')->get();
    //
    // // 得意先のおすすめ商品、担当のおすすめ商品をマージ処理
    // $recommends = $recommends->merge($buyer_recommends);







    if(isset($data->memo)){
      $memo -> $request->memo;
    }

    // Log::debug($message);
    // return $request;

    $collect = config('app.collect_password');
    $collect_tradercode = config('app.collect_tradercode');
    $collect_password = config('app.collect_password').'2';
    $collect_password_save = $user->id.'test'.$collect_password;
    $collect_password = hash('sha256', utf8_encode($collect_password));
    // dd($collect_password_save);
    $collect_password_save = hash('sha256', utf8_encode($collect_password_save));
    $collect_touroku = config('app.collect_touroku');
    $collect_token = config('app.collect_token');


    // dd($hashedPassword);


    $message=['message' => $message];

    // return $nouhin_yoteibi;
    $data=
    ['carts' => $carts,
     'cart_ninis' => $cart_ninis,
     'stores' => $stores,
     'holidays' => $holidays,
     'user' => $user,
     'setonagi' => $setonagi,
     'today_plus' => $today_plus,
     'sano_nissuu' => $sano_nissuu,
     'collect_tradercode' => $collect_tradercode,
     'collect_password' => $collect_password,
     'collect_password_save' => $collect_password_save,
     'collect' => $collect,
     'collect_touroku' => $collect_touroku,
     'collect_token' => $collect_token,
     'set_order' => $set_order,
     'all_nouhin_end' => $all_nouhin_end,
     'groupedItems' => $groupedItems,
     // 'recommends' => $recommends,
     'show_favorite' => $show_favorite,
     'url' => $url,
     'seted_store' => $store,
     'nouhin_yoteibi' => $nouhin_yoteibi,
     'message' => $message,
     'shipping_code' => $shipping_code,
     'shipping_settings' => $shipping_settings,
    ];
    // return view('order', $data)->with($message);
    return view('order', $data);
  }






  public function line(){
    $user_id = Auth::guard('user')->user()->id;
    $categories = Category::get()->groupBy('bu_ka_name');
    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

    return view('user.auth.line', ['categories' => $categories, 'favorite_categories' => $favorite_categories]);
  }



  public function favorite(){
    $user_id = Auth::guard('user')->user()->id;
    $categories = Category::get()->groupBy('bu_ka_name');
    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

    return view('user.auth.favorite', ['categories' => $categories, 'favorite_categories' => $favorite_categories]);
  }


  public function EditFavoriteCategory(Request $request){
    $user_id = Auth::guard('user')->user()->id;
    $favorite_categories = $request->input('favorite_category');
    // dd($favorite_categories);

    $delete_favorite_categories = FavoriteCategory::where('user_id', $user_id)->delete();

    foreach ($favorite_categories as $favorite_category) {
      $favorite_category=FavoriteCategory::firstOrNew(['user_id'=> $user_id , 'category_id'=> $favorite_category]);
      $favorite_category->save();
    }

    $user = User::where('id', $user_id)->first();
    $user->first_login = 1;
    $user->save();

    $data = "sucsess";
    return redirect()->route('favorite',$data);
  }


  public function deal(){
    $user_id = Auth::guard('user')->user()->id;
    $categories = Category::get()->groupBy('bu_ka_name');

    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
    $deals = Deal::where('user_id',$user_id)->latest('created_at')->paginate(30);
    // dd($deals);

    // 全ての取引を取得→自分の持っている店舗の情報を取得→一致する取引は全て表示→ダブりを消してユニークにする。

    return view('deal', ['deals' => $deals, 'categories' => $categories, 'favorite_categories' => $favorite_categories]);
  }



  // public function dealdetail($id){
  //
  //
  //   $user = Auth::guard('user')->user();
  //   $user_id = Auth::guard('user')->user()->id;
  //   $setonagi = Setonagi::where('user_id',$user_id)->first();
  //
  //
  //   $today = date("Y-m-d");
  //   $holidays = Holiday::pluck('date');
  //
  //   // 直近の納品予定日を取得
  //   $today = date("Y-m-d");
  //   $holidays = Holiday::pluck('date')->toArray();
  //   for($i = 3; $i < 10; $i++){
  //     $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
  //     // dd($today_plus2);
  //     $key = array_search($today_plus,(array)$holidays,true);
  //     if($key){
  //         // 休みでないので納品日を格納
  //     }else{
  //         // 休みなので次の日付を探す
  //         $nouhin_yoteibi = $today_plus;
  //         break;
  //     }
  //   }
  //
  //
  //   $categories = Category::get()->groupBy('bu_ka_name');
  //
  //   $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
  //
  //   $deal = Deal::where('id',$id)->first();
  //   $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> $id])->get();
  //
  //
  //   // キャンセルができるか判定
  //   $user = User::where(['id'=> $user_id])->first();
  //
  //   // セトナギユーザーの場合
  //   // 注文完了時間
  //   $success_time = $deal->success_time;
  //   $success_jikan = date('H:i:s', strtotime($success_time));
  //   // dd($success_jikan);
  //
  //   // 注文完了日から受け取り予定日時を取得
  //   $today = date("Y-m-d");
  //   $holidays = Holiday::pluck('date');
  //   $currentTime = date('H:i:s');
  //   // 19時より前の処理
  //   if (strtotime($success_jikan) < strtotime('17:00:00')) {
  //     $holidays = Holiday::pluck('date')->toArray();
  //     for($i = 1; $i < 10; $i++){
  //       $today_plus = date('Y-m-d', strtotime($success_time.'+'.$i.'day'));
  //       // dd($today_plus2);
  //       $key = array_search($today_plus,(array)$holidays,true);
  //       if($key){
  //           // 休みでないので納品日を格納
  //       }else{
  //           // 休みなので次の日付を探す
  //           $nouhin_yoteibi = $today_plus;
  //           break;
  //       }
  //     }
  //   }else{
  //   // 19時より後の処理
  //     $holidays = Holiday::pluck('date')->toArray();
  //     for($i = 2; $i < 10; $i++){
  //       $today_plus = date('Y-m-d', strtotime($success_time.'+'.$i.'day'));
  //       // dd($today_plus2);
  //       $key = array_search($today_plus,(array)$holidays,true);
  //       if($key){
  //           // 休みでないので納品日を格納
  //       }else{
  //           // 休みなので次の日付を探す
  //           $nouhin_yoteibi = $today_plus;
  //           break;
  //       }
  //     }
  //   }
  //   // dd($nouhin_yoteibi);
  //   // 注文の翌営業日の納品予定19時を取得
  //   $zenjitu19ji = date($nouhin_yoteibi.'17:00:00');
  //   // 納品予定日の19時を取得
  //   $zenjitu19ji = date('Y-m-d H:i:s', strtotime($zenjitu19ji.'-1 day'));
  //   // dd($zenjitu19ji);
  //
  //   // 今の日付時間
  //   $now = date("Y-m-d H:i:s");
  //   // 次の営業日の前日19時以降はキャンセル不可
  //
  //   // 翌営業日締め時間より前
  //   if (strtotime($now) < strtotime($zenjitu19ji)) {
  //     // dd('キャンセル可');
  //     $deal_cancel_button = 1;
  //   }else{
  //     // dd('キャンセル不可');
  //     $deal_cancel_button = null;
  //   }
  //
  //   $user = User::where(['id'=>$deal->user_id])->first();
  //   $nouhin_yoteibi = null;
  //   if($user->setonagi == 1){
  //     $cart_id = Cart::where(['user_id'=>$deal->user_id, 'deal_id'=> $id])->first()->id;
  //     $nouhin_yoteibi = Order::where(['cart_id'=>$cart_id])->first()->nouhin_yoteibi;
  //   }
  //
  //
  //   $collect_tradercode = config('app.collect_tradercode');
  //   $collect_password = config('app.collect_password');
  //   $collect_touroku = config('app.collect_touroku');
  //   $collect_token = config('app.collect_token');
  //
  //
  //   $data=[
  //     // 'carts'=>$carts,
  //     'nouhin_yoteibi'=>$nouhin_yoteibi,
  //     'deal'=>$deal,
  //     'categories' => $categories,
  //     'favorite_categories' => $favorite_categories,
  //     'holidays' => $holidays,
  //     'user_id' => $user_id,
  //     'user' => $user,
  //     'setonagi' => $setonagi,
  //     'today_plus' => $today_plus,
  //     'deal_cancel_button' => $deal_cancel_button,
  //     'collect_tradercode' => $collect_tradercode,
  //     'collect_password' => $collect_password,
  //     'collect_touroku' => $collect_touroku,
  //     'collect_token' => $collect_token,
  //   ];
  //   return view('dealdetail', $data);
  // }
  //
  //
  // public function dealorder(Request $request){
  //
  //   $deal_id = $request->deal_id;
  //   $user_id = Auth::guard('user')->user()->id;
  //
  //   $deal =  Deal::where(['id'=>$deal_id])->first();
  //
  //   $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> $deal_id])->get();
  //   $groupedItems = $carts->groupBy('groupe');
  //
  //   // 取引IDが一致しているものを取得
  //   $cart_ninis =  CartNini::where(['user_id'=>$user_id, 'deal_id'=> $deal_id])->get();
  //   // dd($cart_ninis);
  //
  //   // 休日についての処理
  //   $today = date("Y-m-d");
  //   $holidays = Holiday::pluck('date');
  //
  //   // 店舗一覧取得
  //   $kaiin_number = Auth::guard('user')->user()->kaiin_number;
  //   $store_users = StoreUser::where('user_id',$kaiin_number)->get(['store_id','tokuisaki_id']);
  //   $stores = [];
  //   $n=1;
  //   foreach ($store_users as $store_user) {
  //   $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
  //     array_push($stores, $store);
  //   $n++;
  //   }
  //
  //   $user = Auth::guard('user')->user();
  //   $setonagi = Setonagi::where('user_id',$user_id)->first();
  //   // dd($user->setonagi);
  //
  //   // 直近の納品予定日を取得
  //   $today = date("Y-m-d");
  //   $holidays = Holiday::pluck('date');
  //   $currentTime = date('H:i:s');
  //   // 19時より前の処理
  //   if (strtotime($currentTime) < strtotime('17:00:00')) {
  //     $holidays = Holiday::pluck('date')->toArray();
  //     for($i = 1; $i < 10; $i++){
  //       $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
  //       // dd($today_plus2);
  //       $key = array_search($today_plus,(array)$holidays,true);
  //       if($key){
  //           // 休みでないので納品日を格納
  //       }else{
  //           // 休みなので次の日付を探す
  //           $nouhin_yoteibi = $today_plus;
  //           break;
  //       }
  //     }
  //   }else{
  //   // 19時より後の処理
  //     $holidays = Holiday::pluck('date')->toArray();
  //     for($i = 2; $i < 10; $i++){
  //       $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
  //       // dd($today_plus2);
  //       $key = array_search($today_plus,(array)$holidays,true);
  //       if($key){
  //           // 休みでないので納品日を格納
  //       }else{
  //           // 休みなので次の日付を探す
  //           $nouhin_yoteibi = $today_plus;
  //           break;
  //       }
  //     }
  //   }
  //   $sano_nissuu = '+'.((strtotime($nouhin_yoteibi) - strtotime($today)) / 86400).'d';
  //   $collect = config('app.collect_password');
  //
  //   $data=
  //   ['carts' => $carts,
  //    'cart_ninis' => $cart_ninis,
  //    'stores' => $stores,
  //    'holidays' => $holidays,
  //    'deal' => $deal,
  //    'user' => $user,
  //    'setonagi' => $setonagi,
  //    'sano_nissuu' => $sano_nissuu,
  //    'collect' => $collect,
  //    'groupedItems' => $groupedItems,
  //   ];
  //   return view('dealorder', $data);
  // }










  public function adddeal(Request $request){

    $user = Auth::guard('user')->user();
    $user_id = $user->id;
    $data = $request->all();
    // dd($data);

    // セトナギユーザーのバリデーション
    if($user->setonagi == 1){
      $validator = $request->validate([
        'addtype' => ['required', 'in:addsetonagi,addbuyerrecommend,addspecialprice'],
        'uketori_siharai' => ['required', 'in:クレジットカード払い,クロネコかけ払い'],
        'uketori_time' => ['in:午前中,12時〜14時,14時〜16時,16時〜17時'],
      ]);
    }else{
      $validator = $request->validate([
        'addtype' => ['required', 'in:addsetonagi,addbuyerrecommend,addspecialprice'],
      ]);
    }

    // $uketori_place = $request->uketori_place;

    $addtype = $request->addtype;
    $now = Carbon::now();

    $change_all_nouhin_yoteibi = $request->change_all_nouhin_yoteibi;

    // toC納品予定日の再設定を回避（必要なかった）
    // $shipping_code = Setonagi::where('user_id',$user_id)->first();
    // if(isset($shipping_code->shipping_code) && isset($change_all_nouhin_yoteibi)){
    //   $c_nouhin_yoteibi = $change_all_nouhin_yoteibi;
    // }

    if(!$user->setonagi == 1){
      // BtoBの必要な情報を保存
      $store_name =  $request->change_all_store;
      $tokuisaki_name = $request->set_tokuisaki_name;
      $store = Store::where(['tokuisaki_name'=> $tokuisaki_name,'store_name'=> $store_name])->first();
      $tokuisaki_id = $store->tokuisaki_id;
      $store_id = $store->store_id;
      $kaiin_number = $user->kaiin_number;
    }

    // BtoCの必要な情報を保存
    $setonagi = Setonagi::where('user_id',$user_id)->first();
    if($setonagi){
      $shipping_code = $setonagi->shipping_code;
      // 配送設定を呼び出し
      if(isset($shipping_code)){
        $shipping_setting = ShippingSetting::where(['shipping_code'=> $shipping_code,'shipping_method'=> $request->uketori_place])->first();
        // 配送方法が取得できない場合
        if(!isset($shipping_setting)){
          $message = '配送方法が取得できません';
          $data=[
            'addtype' => $addtype,
            'message' => $message,
          ];
          return redirect()->route('bulk',$data);
        }
        $shipping_name = $shipping_setting->shipping_name;
        $shipping_price = $shipping_setting->shipping_price;
        $calender_id= $shipping_setting->calender_id;
      }
    }
    // dd($shipping_price);





    // 次の営業日を取得
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');

    // dd($currentTime);

    // 17時より前の処理
    if (strtotime($currentTime) < strtotime('17:00:00')) {
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 1; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
        // dd($today_plus2);
        $key = array_search($today_plus,(array)$holidays,true);
        if($key){
            // 休みなので次の日付を探す
        }else{
            // 休みでないので納品日を格納
            $nouhin_kanoubi = $today_plus;
            break;
        }
      }
    }else{
    // 17時より後の処理
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 2; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
        // dd($today_plus2);
        $key = array_search($today_plus,(array)$holidays,true);
        if($key){
            // 休みなので次の日付を探す
        }else{
          $nouhin_kanoubi = $today_plus;
          break;
        }
      }
    }

    $current_time = $request->current_time;
    // dd($current_time);
    $cutoff_time = date('Y-m-d 17:00:00');
    $order_time = date('Y-m-d H:i:s');
    // 確認画面からオーダーまでに17:00を跨いだ場合一度戻す
    if (strtotime($current_time) < strtotime($cutoff_time) && strtotime($cutoff_time) < strtotime($order_time)) {
        $message = '締め時間を過ぎたため一度注文確認画面に戻ります。';
        if($user->setonagi == 1){
          $data=[
            'addtype' => $addtype,
            'message' => $message,
          ];
        }else{
          // リダイレクトやメッセージの表示など、最終確認画面に戻す処理を追加
          $data=[
            'addtype' => $addtype,
            'change_all_nouhin_yoteibi' => $nouhin_kanoubi,
            'change_all_store' => $store_name,
            'set_tokuisaki_name' => $tokuisaki_name,
            'message' => $message,
          ];
        }
        return redirect()->route('confirm',$data);
    }

    // 個数が0の商品を配列から消す
    $keysToDelete = [];
    foreach ($data["quantity"] as $key => $quantity) {
        if ($quantity === "0") {
            $keysToDelete[] = $key;
        }
    }
    foreach ($keysToDelete as $index) {
        foreach ($data as &$array) {
            if (is_array($array)) {
                unset($array[$index]);
            }
        }
    }
    // $data = array_map('array_values', $data);
    // dd($data);
    $cart_ids = $data['cart_id'];

    if(isset($data['cart_nini_id'])){
      $cart_nini_ids = $data['cart_nini_id'];
    }

    // 在庫チェック
    $lost_items = [];
    foreach($cart_ids as $cart_id) {
      // カート内の該当アイテムを検索
      $cart = Cart::where(['id'=> $cart_id])->first();

      // アイテムが取得できない場合
      if(!isset($cart)){
        $message = '商品コードエラー';
        $data=[
          'addtype' => $addtype,
          'message' => $message,
        ];
        return redirect()->route('bulk',$data);
      }

      // アイテム情報を取得
      $item = item::where(['id'=> $cart->item_id])->first();
      $item_name = $item->item_name;

      // カートに関連する注文情報を取得
      $orders = Order::where(['cart_id'=> $cart->id])->get();
      $order = Order::where(['cart_id'=> $cart->id])->first();

      // 注文個数を計算
      $total = 0;
      foreach ($orders as $order) {
          $total += $order['quantity'];
      }

      $buyer_recommend_item = null;
      $zaikosuu = null;
      // BtoB担当のおすすめ商品で在庫状況の上書きがあるか確認
      if(!$user->setonagi == 1){
        // $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');
        // foreach ($tokuisaki_ids as $key =buyer_recommends.> $value) {
        // 担当のおすすめ商品の在庫管理をしない場合
        $buyer_recommend_item = BuyerRecommend::where('tokuisaki_id', $tokuisaki_id)
        // ->where('price', '>=', 1)
        ->where('zaikokanri', 1)
        ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
        ->where('start', '<=' , $now)
        ->where('end', '>=', $now)->first();
        // dd($buyer_recommend_item);
        if(isset($buyer_recommend_item)){
          $zaikosuu = 999;
        }else{
          // 担当のおすすめ商品の在庫数を取得
          $buyer_recommend_item = BuyerRecommend::where('tokuisaki_id', $tokuisaki_id)
          // ->where('price', '>=', '1')
          ->whereNull('zaikokanri')
          ->where('zaikosuu', '>=', 0)
          ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
          ->where('start', '<=' , $now)
          ->where('end', '>=', $now)->first();
          // dd($buyer_recommend_item);
          if(isset($buyer_recommend_item)){
            $zaikosuu = $buyer_recommend_item->zaikosuu;
          }
        }
      }

      // 担当のおすすめ商品から在庫数が取得できた場合はその在庫数を使う。
      if(isset($buyer_recommend_item)){

      }else{
        $zaikosuu = $item->zaikosuu;
      }


      // 在庫数から注文個数を差し引いた残りの在庫数を計算
      $nokori_zaiko = $zaikosuu - $total;

      // dd($nokori_zaiko);

      // lost_itemという変数に、$nokori_zaikoの値が、0を下回った場合、「lost_item」に「$item_name」と「$nokori_zaiko」を格納し、さらに、「$lost_items」という変数に配列として追加する。
      $lost_item = null;
      if($nokori_zaiko < 0){
        if(isset($buyer_recommend_item)){
          // 配列を格納
          $lost_item =
          ['item_name' => $buyer_recommend_item->uwagaki_item_name,
           'nokori_zaiko' => $nokori_zaiko,
          ];
        }else{
          // 配列を格納
          $lost_item =
          ['item_name' => $item_name,
           'nokori_zaiko' => $nokori_zaiko,
          ];
        }
        array_push($lost_items, $lost_item);
      }
    }

    // $lost_itemsに１つでも配列がある場合、メッセージを作成
    if(!empty($lost_items)){
      $messages = [];
      foreach ($lost_items as $lost_item) {
        $lost_item_name = $lost_item['item_name'];
        $lost_item_zaiko = abs($lost_item['nokori_zaiko']);
        $message = "{$lost_item_name}は、在庫が{$lost_item_zaiko}不足しています。";
        $messages[] = $message;
      }
      // dd($messages);

      $messages = implode("\n", $messages);
      $formatted_message = nl2br(htmlspecialchars(urldecode($messages), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
      $message = $formatted_message;

      $data=[
        'addtype' => $addtype,
        'change_all_nouhin_yoteibi' => $order->nouhin_yoteibi,
        'change_all_store' => $order->store_name,
        'set_tokuisaki_name' => $order->tokuisaki_name,
        'message' => $message,
      ];
      return redirect()->route('confirm',$data);
    }
    // 在庫チェックここまで


    // 掲載期限チェック
    // $over_deadline_items = [];
    // if(!$user->setonagi == 1){
    //   foreach($cart_ids as $cart_id) {
    //     $buyer_recommend_item = null;
    //     $cart = Cart::where(['id'=> $cart_id])->first();
    //     $order = Order::where(['cart_id'=> $cart->id])->first();
    //     $item = Item::where('id', $cart->item_id)->first();
    //     // dd($item);
    //     // 担当のおすすめ商品の納品期日を探す
    //     $buyer_recommend_item = BuyerRecommend::
    //     // Order::join('orders', 'orders.price', '=', 'buyer_recommends.price')
    //     where('tokuisaki_id', $tokuisaki_id)
    //     // ->where('price', '>=', '1')
    //     ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
    //     // ->where('start', '>=' , $now)
    //     ->where('end', '<=', $now)->first();
    //     // dd($buyer_recommend_item);
    //     if(isset($buyer_recommend_item)){
    //       // 配列を格納
    //       $over_deadline_item = [
    //         'cart_id' => $cart->id,
    //         'item_name' => $buyer_recommend_item->uwagaki_item_name,
    //       ];
    //       array_push($over_deadline_items, $over_deadline_item);
    //     }
    //     // 市況商品を探す
    //     $price_groupe = PriceGroupe::where(['tokuisaki_id'=>$tokuisaki_id,'store_id'=>$store_id])->first();
    //     $special_price_item = SpecialPrice::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'price_groupe'=>$price_groupe->price_groupe])
    //     // ->where('start', '>=' , $now)
    //     ->where('end', '<=', $now)->first();
    //     if(isset($special_price_item)){
    //       // 配列を格納
    //       $over_deadline_item = [
    //         'cart_id' => $cart->id,
    //         'item_name' => $item->item_name,
    //       ];
    //       array_push($over_deadline_items, $over_deadline_item);
    //     }
    //   }
    //   if(!empty($over_deadline_items)){
    //     $messages = [];
    //     foreach ($over_deadline_items as $over_deadline_item) {
    //       $over_deadline_item_name = $over_deadline_item['item_name'];
    //       $order = Order::where(['cart_id'=> $cart->id])->delete();
    //       $cart = Cart::where(['id'=> $cart->id])->delete();
    //       $message = "{$over_deadline_item_name}は、掲載期限を過ぎたため削除されました。";
    //       $messages[] = $message;
    //     }
    //
    //     $messages = implode("\n", $messages);
    //     $formatted_message = nl2br(htmlspecialchars(urldecode($messages), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    //     $message = $formatted_message;
    //
    //     $data=[
    //       'addtype' => $addtype,
    //       'change_all_nouhin_yoteibi' => $change_all_nouhin_yoteibi,
    //       'change_all_store' => $store->store_name,
    //       'set_tokuisaki_name' => $store->tokuisaki_name,
    //       'message' => $message,
    //     ];
    //     return redirect()->route('confirm',$data);
    //   }
    // }
    //
    //
    //
    //
    //
    //
    // if($user->setonagi == 1){
    //   // BtoSmallBユーザーの掲載期限を過ぎた市況商品、担当のおすすめ商品がカートに含まれていないかチェック
    //
    //   foreach($cart_ids as $cart_id) {
    //     $cart = Cart::where(['id'=> $cart_id])->first();
    //     $item = Item::where('id', $cart->item_id)->first();
    //     // dd($item);
    //     // 担当のおすすめ商品の納品期日を探す
    //     $recommend_item = Recommend::where('user_id', $user->id)
    //     ->where('price', '>=', '1')
    //     ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
    //     ->where('end', '<=', $now)->first();
    //     // dd($buyer_recommend_item);
    //     if(isset($recommend_item)){
    //       $order = Order::where(['cart_id'=> $cart->id])->delete();
    //       $cart = Cart::where(['id'=> $cart->id])->delete();
    //       $message = $item->item_name.'は掲載期限を過ぎているため削除されました。';
    //       $data=[
    //         'message' => $message,
    //       ];
    //       return redirect()->route('confirm',$data);
    //     }
    //     // 市況商品を探す
    //     $price_groupe = '10000000005';
    //     $special_price_item = SpecialPrice::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'price_groupe'=>$price_groupe])
    //     ->where('end', '<=', $now)->first();
    //     if(isset($special_price_item)){
    //       $order = Order::where(['cart_id'=> $cart->id])->delete();
    //       $cart = Cart::where(['id'=> $cart->id])->delete();
    //       $message = $item->item_name.'は掲載期限を過ぎているため削除されました。';
    //       $data=[
    //         'message' => $message,
    //       ];
    //       return redirect()->route('confirm',$data);
    //     }
    //   }
    // }


    // 次の営業日を取得
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');

    // 17時より前の処理
    if (strtotime($currentTime) < strtotime('17:00:00')) {
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 1; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
        // dd($today_plus2);
        $key = array_search($today_plus,(array)$holidays,true);
        if($key){
            // 休みなので次の日付を探す
        }else{
            // 休みでないので納品日を格納
            $nouhin_kanoubi = $today_plus;
            break;
        }
      }
    }else{
    // 17時より後の処理
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 2; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
        // dd($today_plus2);
        $key = array_search($today_plus,(array)$holidays,true);
        if($key){
            // 休みなので次の日付を探す
        }else{
          //
          // その前日が休みかどうか確認、
          // $zenjitu = date('Y-m-d', strtotime($today_plus.'-1'.'day'));
          // $zenjitu_yasumi = array_search($zenjitu,(array)$holidays,true);
          //
          // // 休みの場合さらに1営業日をプラスして翌日が営業日か確認
          // if($zenjitu_yasumi){
          //   for($n = 1; $n < 10; $n++){
          //     $yoku_eigyoubi = date('Y-m-d', strtotime($today_plus.'+'.$n.'day'));
          //     $key = array_search($yoku_eigyoubi,(array)$holidays,true);
          //     if($key){
          //         // 休みなので次の日付を探す
          //     }else{
          //         // 休みでないので納品日を格納
          //         $nouhin_kanoubi = $yoku_eigyoubi;
          //         break;
          //     }
          //   }
          // }else{
          //   // 休みでなければそのまま納品日を格納
          //   $nouhin_kanoubi = $today_plus;
          //   break;
          // }
          //break;
          $nouhin_kanoubi = $today_plus;
          break;
        }
      }
    }


    // 納品日が納品可能日より前に設定されていないかチェック
    $nouhin_youbi_list = [];


    foreach($cart_ids as $cart_id) {
      $cart = Cart::where(['id'=> $cart_id])->first();
      $orders = Order::where(['cart_id'=> $cart->id])->get();
      foreach ($orders as $order) {
        // $array = $order->nouhin_yoteibi;
        $sano_nissuu = ((strtotime($order->nouhin_yoteibi) - strtotime($nouhin_kanoubi)) / 86400);
        $nouhin_yoteibi = date('Y年m月d日', strtotime($order->nouhin_yoteibi));
        // dd($nouhin_yoteibi);
        if($sano_nissuu < 0){
          if($user->setonagi == 1){
            foreach($cart_ids as $cart_id) {
              $cart = Cart::where(['id'=> $cart_id])->first();
              $orders = Order::where(['cart_id'=> $cart->id])->get();
              foreach ($orders as $order) {
                $order->nouhin_yoteibi = $nouhin_kanoubi;
                $order->save();
              }
            }
            $data=[
              // 'id' => $deal_id,
              // 'order_id' => $order->id,
              // 'nouhin_yoteibi' => $nouhin_yoteibi,
              'addtype' => $addtype,
              'change_all_nouhin_yoteibi' => $order->nouhin_yoteibi,
              'change_all_store' => $order->store_name,
              'set_tokuisaki_name' => $order->tokuisaki_name,
              'message' => '締め時間の17時を過ぎたため全ての商品の引き取り予定日が'.$nouhin_kanoubi.'に変更されました。よろしければこのまま注文に進んでください。',
            ];
            return redirect()->route('confirm',$data);
          }
          // 納品日エラーの場合カート画面に戻す
          $data=[
            // 'id' => $deal_id,
            // 'order_id' => $order->id,
            // 'nouhin_yoteibi' => $nouhin_yoteibi,
            'addtype' => $addtype,
            'change_all_nouhin_yoteibi' => $order->nouhin_yoteibi,
            'change_all_store' => $order->store_name,
            'set_tokuisaki_name' => $order->tokuisaki_name,
            'message' => '納品予定日'.$nouhin_yoteibi.'は指定できません。他の日付を設定してください。',
          ];
          return redirect()->route('confirm',$data);
        }
        array_push($nouhin_youbi_list, $sano_nissuu);
      }
    }

    $nouhin_youbi_list = [];
    $carts = CartNini::where(['user_id'=> $user_id,'deal_id'=> null])->get();

    // $today = date("Y-m-d");
    foreach ($carts as $cart) {
      $orders = OrderNini::where(['cart_nini_id'=> $cart->id])->get();

      foreach ($orders as $order) {
        // $array = $order->nouhin_yoteibi;
        $sano_nissuu = ((strtotime($order->nouhin_yoteibi) - strtotime($nouhin_kanoubi)) / 86400);
        $nouhin_yoteibi = date('Y年m月d日', strtotime($order->nouhin_yoteibi));
        // dd($nouhin_yoteibi);
        if($sano_nissuu < 0){
          // 納品日エラーの場合カート画面に戻す
          $data=[
            // 'id' => $deal_id,
            // 'order_id' => $order->id,
            // 'nouhin_yoteibi' => $nouhin_yoteibi,
            'addtype' => $addtype,
            'change_all_nouhin_yoteibi' => $order->nouhin_yoteibi,
            'change_all_store' => $order->store_name,
            'set_tokuisaki_name' => $order->tokuisaki_name,
            'message' => '納品予定日'.$nouhin_yoteibi.'は指定できません。他の日付を設定してください。',
          ];
          return redirect()->route('confirm',$data);
        }
        array_push($nouhin_youbi_list, $sano_nissuu);
      }
    }
    // dd($sano_nissuu);

    // 「担当のおすす商品」が存在しているかチェック
    // $nouhin_youbi_list = [];
    // $item_id=Cart::where(['id'=> $order->cart_id])->first()->item_id;
    // $item=Item::where('id',$item_id)->first();
    // $kaiin_number = Cart::where(['id'=> $order->cart_id])->first()->kaiin_number;
    //
    // $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');
    // dd($tokuisaki_ids);
    // if($tokuisaki_ids){
    //   foreach ($tokuisaki_ids as $key => $value) {
    //     // dd($value->tokuisaki_id);
    //     $buyerrecommend_item = BuyerRecommend::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'tokuisaki_id'=>$value->tokuisaki_id])->where('start', '<=' , $order->nouhin_yoteibi)->where('end', '>=', $order->nouhin_yoteibi)->first();
    //     if(isset($buyerrecommend_item)){
    //         dd($buyerrecommend_item);
    //     }
    //   }
    // }

    if(isset($request->memo)){
      $deal = Deal::create(['user_id'=> $user_id, 'memo'=> $request->memo]);
    }else{
      $deal = Deal::create(['user_id'=> $user_id]);
    }
    $deal_id = $deal->id;



    $now = Carbon::now()->format('Ymd');
    // dd($now);



    // 以下新規挿入
    // BtoC+SB合計金額計算
    if($setonagi){
      $total_price = [];
      $carts = Cart::where(['deal_id'=> $deal->id])->get();
      // カート商品の出力
      foreach($cart_ids as $cart_id) {
        $cart = Cart::where(['id'=> $cart_id])->first();
        $orders = Order::where(['cart_id'=> $cart->id])->get();
        foreach ($orders as $order) {
          // dd($store);
          // 金額未定に対応
          if($order->price == '未定' || $order->price == '-'){
            $array = 0;
          }else{
            $array = $order->price * $order->quantity;
          }
          array_push($total_price, $array);
        }
      }
      // 送料がかかる場合の表示設定
      // 商品合計
      $total_price = array_sum($total_price);
      // 商品合計税込
      $all_total_price = floor($total_price * (108 / 100));
      // 8%税額
      $zeinomi8 = $all_total_price - $total_price;

      if(isset($shipping_price)){
        // 送料税込
        $shipping_price_zei = floor($shipping_price * (110 / 100));
        // 10%税のみ
        $zeinomi10 = $shipping_price_zei - $shipping_price;
        // 10%対象消費合計額（カンマ表示）
        // 税込合計金額
        $all_total_price = $shipping_price_zei + $all_total_price;
        // 税金の計算
        $zei_price = $all_total_price - $total_price - $shipping_price;
      }else{
        // 税金の計算
        $zei_price = $all_total_price - $total_price;
      }

      // ここまで

      // $zeinuki = floor($request->all_total_val - $request->tax_val);
      // dd($zeinuki);
      // dd($total_price);

      if($request->uketori_siharai == 'クロネコかけ払い'){
        // ヤマトAPI連携確認
        $client = new Client();

        $url = config('app.kakebarai_touroku');
        $kakebarai_traderCode = config('app.kakebarai_traderCode');
        $kakebarai_passWord = config('app.kakebarai_passWord');
        $envi = config('app.envi');


        // カート商品の出力
        $cart_items = [];
        $n = 1;
        // カート商品の出力
        foreach($cart_ids as $cart_id) {
          $cart = Cart::where(['id'=> $cart_id])->first();
          $orders = Order::where(['cart_id'=> $cart_id])->get();
          foreach ($orders as $order) {
            $item = Item::where(['id' => $cart->item_id])->first();
          }
          $cart_items['shohinCode' . $n] = $item->item_id;
          $cart_items['shohinMei' . $n] = $item->item_name;
          $cart_items['suryo' . $n] = $order->quantity;
          $cart_items['tanka' . $n] = $order->price;
          $cart_items['tani' . $n] = $item->kuroneko_item_tani();
          $cart_items['kessaiKingaku' . $n] = $order->price * $order->quantity;
          $cart_items['zeiritsuKbn' . $n] = '2';
          $n++;
        }
        // dd($cart_items);

        $option = [
          'headers' => [
            'Accept' => '*/*',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'charset' => 'UTF-8',
          ],
          'form_params' => [
            'traderCode' => $kakebarai_traderCode,
            // 日付
            'orderDate' => $now,
            'orderNo' => $deal_id.$envi,
            // バイヤーid
            'buyerId' => $user_id,
            'settlePrice' => $total_price,
            'passWord' => $kakebarai_passWord
          ]
        ];

        // $cart_items 配列の内容を $option の 'form_params' に追加
        foreach($cart_items as $key => $value) {
            $option['form_params'][$key] = $value;
        }
        // dd($option);

        $response = $client->request('POST', $url, $option);
        $result = simplexml_load_string($response->getBody()->getContents());
        if($result->returnCode == 1){
          $delete_deal = Deal::where(['id'=> $deal_id])->first()->delete();
          // dd($result);
          if($result->errorCode == 'G55'){
            // 後で処理を作る

            // ヤマトAPI連携利用金額確認
            $client = new Client();
            $url = config('app.kakebarai_riyoukingaku');
            $option = [
              'headers' => [
                'Accept' => '*/*',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'charset' => 'UTF-8',
              ],
              'form_params' => [
                'traderCode' => $kakebarai_traderCode,
                // バイヤーid
                'buyerId' => $user_id,
                'buyerTelNo' => '',
                'passWord' => $kakebarai_passWord
              ]
            ];
            // dd($option);
            $response = $client->request('POST', $url, $option);
            $result = simplexml_load_string($response->getBody()->getContents());
            // dd($result);
            $message = '掛け払い金額オーバー';
            if($result->returnCode == 0){
              $usePayment = $result->usePayment;
              $useOverLimit = $result->useOverLimit;
            }
            // %2Cになっているのをカンマを直す
            $limitprice = $useOverLimit - $usePayment;
            // $limitprice = number_format($limitprice);
            // $limitprice = mb_convert_encoding($limitprice,"utf-8","sjis");
            // dd($limitprice);
            $message = 'かけ払い利用限度額オーバーです。<br />'.$limitprice.'円以内での購入をお願いします。';
            $data=[
              'addtype' => $addtype,
              'message' => $message,
            ];
          }else{
            $message = '決済エラーのため別の決済方法をお試しください。';
            $data=[
              'addtype' => $addtype,
              'message' => $message,
            ];
          }
          return redirect()->route('confirm',$data);
        }
      }

      // if($request->uketori_siharai == 'クレジットカード払い'){
      //   // dd($request->token_api);
      //   // EPトークン取得
      //   $client = new Client();
      //   // $url = 'https://api.kuronekoyamato.co.jp/api/credit';
      //
      //   $url = config('app.collect_touroku');
      //   $collect_tradercode = config('app.collect_tradercode');
      //
      //   $option = [
      //     'headers' => [
      //       'Accept' => '*/*',
      //       'Content-Type' => 'application/x-www-form-urlencoded',
      //       'charset' => 'UTF-8',
      //     ],
      //     'form_params' => [
      //       'function_div' => 'A08',
      //       'trader_code' => $collect_tradercode,
      //       // パソコンかスマホか
      //       'device_div' => 1,
      //       'order_no' => $deal_id,
      //       // 決済合計金額
      //       'settle_price' => $all_total_price,
      //       'buyer_name_kanji' => $user->name,
      //       'buyer_tel' => $user->tel,
      //       'buyer_email' => $user->email,
      //       'pay_way' => 1,
      //       'token' => $request->token_api,
      //
      //       // ここからカード預かりサービス追加分
      //
      //       // 'card_judge_div' => 1,
      //       // 'device_info' => 1,
      //       // 'option_service_div' => 00,
      //       // 'check_sum' => '',
      //       // 'cardNo' => '',
      //       // 'cardOwner' => '',
      //       // 'cardExp' => '',
      //       // 'securityCode' => '',
      //     ]
      //   ];
      //   // dd($option);
      //   $response = $client->request('POST', $url, $option);
      //   $result = simplexml_load_string($response->getBody()->getContents());
      //   if($result->returnCode == 1){
      //     $delete_deal = Deal::where(['id'=> $deal_id])->first()->delete();
      //     // dd($result);
      //     if($result->errorCode == 123456){
      //       // 後で処理を作る
      //       $message = '決済金額オーバー';
      //       $data=[
      //         'addtype' => $addtype,
      //         'message' => $message,
      //       ];
      //     }else{
      //       $message = '決済エラーのため別の決済方法をお試しください。';
      //       $data=[
      //         'addtype' => $addtype,
      //         'message' => $message,
      //       ];
      //     }
      //     return redirect()->route('confirm',$data);
      //   }
      //
      //   // 出荷登録
      //   $client = new Client();
      //   $url = config('app.collect_shipment');
      //   // $collect_tradercode = config('app.collect_tradercode');
      //   $option = [
      //     'headers' => [
      //       'Accept' => '*/*',
      //       'Content-Type' => 'application/x-www-form-urlencoded',
      //       'charset' => 'UTF-8',
      //     ],
      //     'form_params' => [
      //       'function_div' => 'E01',
      //       'trader_code' => $collect_tradercode,
      //       'order_no' => $deal_id,
      //       'slip_no' => $deal_id,
      //       'delivery_service_code' => 99,
      //     ]
      //   ];
      //   // dd($option);
      //   $response = $client->request('POST', $url, $option);
      //   $result = simplexml_load_string($response->getBody()->getContents());
      //   if($result->returnCode == 1){
      //     $delete_deal = Deal::where(['id'=> $deal_id])->first()->delete();
      //     // dd($result);
      //     if($result->errorCode == 123456){
      //       // 後で処理を作る
      //       $message = '決済金額オーバー';
      //       $data=[
      //         'addtype' => $addtype,
      //         'message' => $message,
      //       ];
      //     }else{
      //       $message = '決済エラーのため別の決済方法をお試しください。';
      //       $data=[
      //         'addtype' => $addtype,
      //         'message' => $message,
      //       ];
      //     }
      //     return redirect()->route('confirm',$data);
      //   }
      // }
    }



    if($request->has('addsuscess_btn')){
    // 在庫がある場合商品の在庫数を減らす
      foreach($cart_ids as $cart_id) {
        $cart = Cart::where(['id'=> $cart_id])->first();
        $item = item::where(['id'=> $cart->item_id])->first();
        $item_name = $item->item_name;
        // dd($item);
        $orders = Order::where(['cart_id'=> $cart->id])->get();
        // dd($orders);
        // 注文個数計算
        $total = 0;
        foreach ($orders as $order) {
            $total += $order['quantity'];
        }
        // BtoB担当のおすすめ商品で在庫状況の上書きがあるか確認
        if(!$user->setonagi == 1){
          $kaiin_number = $user->kaiin_number;
          $now = Carbon::now();
          // $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');

          $buyer_recommend_item_zaikokanri = null;
          $buyer_recommend_item_zaikosuu = null;

          // foreach ($tokuisaki_ids as $key => $value) {
            // 担当のおすすめ商品の在庫管理をしない場合
            $buyer_recommend_item = BuyerRecommend::where('tokuisaki_id', $tokuisaki_id)
            ->where('price', '>=', 1)
            ->where('zaikokanri', 1)
            ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
            ->where('start', '<=' , $now)
            ->where('end', '>=', $now)
            ->orderBy('order_no', 'asc')->first();
            if(isset($buyer_recommend_item)){
              $buyer_recommend_item_zaikokanri = $buyer_recommend_item;
            }else{
              // 担当のおすすめ商品の在庫数を取得
              $buyer_recommend_item = BuyerRecommend::where('tokuisaki_id', $tokuisaki_id)
              ->where('price', '>=', '1')
              ->whereNull('zaikokanri')
              ->where('zaikosuu', '>=', 1)
              ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
              ->where('start', '<=' , $now)
              ->where('end', '>=', $now)
              ->orderBy('order_no', 'asc')->first();
              if(isset($buyer_recommend_item)){
                $buyer_recommend_item_zaikosuu = $buyer_recommend_item;
              }
            }
          // }
        }
        if(isset($buyer_recommend_item_zaikokanri)){
        }else{
          if(isset($buyer_recommend_item_zaikosuu)){
            // 担当のおすすめ商品から在庫数を取得している場合は、そこから減らす
            $nokori_zaiko = $buyer_recommend_item_zaikosuu->zaikosuu - $total;
            $buyer_recommend_item_zaikosuu->zaikosuu = $nokori_zaiko;
            $buyer_recommend_item_zaikosuu->save();
          }else{
            // 通常の在庫から減らす
            $nokori_zaiko = $item->zaikosuu - $total;
            $item->zaikosuu = $nokori_zaiko;
            $item->save();
          }
        }
        $buyer_recommend_item_zaikokanri = null;
        $buyer_recommend_item_zaikosuu = null;
      }
    }



    // カートにオーダーIDを保存
    foreach($cart_ids as $cart_id) {
      $cart = Cart::where(['id'=> $cart_id])->first();
      $cart->deal_id = $deal_id;
      $cart->save();
    }

    // 任意のカートにオーダーIDを保存
    if(isset($cart_nini_ids)){
      foreach($cart_nini_ids as $key => $input) {
        $cart_nini = CartNini::firstOrNew(['user_id'=> $user_id ,'id'=> $cart_nini_ids[$key] , 'deal_id'=> null]);
        $cart_nini->deal_id = $deal_id;
        $cart_nini->save();
      }
    }

    $deal=Deal::firstOrNew(['id'=> $deal_id]);
    $deal->status = '交渉中';
    $deal->start_time = Carbon::now();

    // セトナギユーザーのみ「受け取り場所」「時間帯」「支払い方法を保存」
    if($request->uketori_siharai){
      $deal->uketori_siharai = $request->uketori_siharai;
      $deal->uketori_place = $request->uketori_place;
      $deal->uketori_time = $request->uketori_time;
    }
    $deal->save();

    if($request->has('addsuscess_btn')){
      // $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> $$deal_id])->get();
      // dd($carts);
      $deal=Deal::firstOrNew(['id'=> $deal_id]);
      $deal->status = '発注済';
      $deal->success_time = Carbon::now();
      $deal->save();

      // 注文完了メールの送信
      $user = User::where('id',$user_id)->first();

      $name = $user->name;
      // $email = $user->email;
      $email = $user->email;
      $url = url('');
      $text = 'SETOnagiをご利用くださいまして誠にありがとうございます。<br />
      下記の通りご注文をお受けいたしましたのでご確認をお願いいたします。<br />
      <br />
      【注文番号】<br />'.$deal->id.'<br /><br />
      【ご注文内容】';
      if($user->setonagi == 1){
        // 支払方法
        $pay = '【お支払い方法】<br />'.$deal->uketori_siharai;
        // 受け取り場所
        $uketori_place = '【配送方法】<br />'.$deal->uketori_place;
        // 受け取り予定日
        $uketori_time = '【受け渡し希望時間】<br />'.$deal->uketori_time;
        if(isset($shipping_code)){
          // 受け取り場所を配送方法に修正
          $uketori_place = '【配送方法】<br />'.$shipping_name;
        }
      }else{
        // 支払方法
        $pay = null;
        // 受け取り場所
        $uketori_place = null;
        // 受け取り時間
        $uketori_time = null;
      }
      // メモ
      $memo =  '【通信欄】<br />'.$deal->memo;

      // 合計金額
      $total_price = [];
      $carts = Cart::where(['deal_id'=> $deal->id])->get();
      // カート商品の出力
      foreach ($carts as $cart) {
        $orders = Order::where(['cart_id'=> $cart->id])->get();
        foreach ($orders as $order) {
          // dd($store);
          // 金額未定に対応
          if($order->price == '未定' || $order->price == '-'){
            $array = 0;
          }else{
            $array = $order->price * $order->quantity;
          }
          array_push($total_price, $array);
        }
      }

      // 送料がかかる場合の表示設定
      // 商品合計
      $total_price = array_sum($total_price);
      // 商品合計税込
      $total_price_zei = floor($total_price * (108 / 100));
      // 8%税額
      $zeinomi8 = $total_price_zei - $total_price;
      // 商品合計税込（カンマ表示）
      $total_price_zei_number = number_format($total_price_zei);
      // 8%対象消費合計額テキスト
      if($deal->uketori_siharai == 'クロネコかけ払い'){
        // クロネコかけ払いの場合
        $total_price_zei_number_text = '軽減税率8％対象：'.number_format($total_price).'円（税抜）';
      }else{
        // それ以外
        $total_price_zei_number_text = '軽減税率8％対象：'.number_format($total_price).'円（税抜）<br />軽減税率8％消費税：'.number_format($zeinomi8).'円<br />軽減税率8％対象：'.$total_price_zei_number.'円（税込）';
      }

      if(isset($shipping_price)){
        // 送料税込
        $shipping_price_zei = floor($shipping_price * (110 / 100));
        // 10%税のみ
        $zeinomi10 = $shipping_price_zei - $shipping_price;
        // 送料（カンマ表示）
        $shipping_price_number = number_format($shipping_price);
        // 送料税込（カンマ表示）
        $shipping_price_zei_number = number_format($shipping_price_zei);
        // 10%対象消費合計額（カンマ表示）
        $shipping_price_zei_number_text = '税率10%対象：'.$shipping_price_number.'円（税抜）<br />税率10％消費税：'.number_format($zeinomi10).'円<br />税率10％対象：'.$shipping_price_zei_number.'円（税込）';
        // 税込合計金額
        $all_total_price = $shipping_price_zei + $total_price_zei;
        // 税金の計算
        $zei_price = $all_total_price - $total_price - $shipping_price;
        // 合計金額表示
        $total_price = '【お支払い金額】<br />'.number_format($all_total_price).'円（税込）';
        // 送料のみの金額表示
        $shipping_price_text = '【送料】<br />'.$shipping_price_number.'円';
        // 税金のみの金額表示
        $zei_price_text = '消費税：'.number_format($zei_price).'円';
      }else{
        $shipping_price_zei_number_text = null;
        $shipping_price_text = null;
        // 税金の計算
        $zei_price = $total_price_zei - $total_price;
        // 金額表示部分（クロネコかけ払いの場合）
        if($deal->uketori_siharai == 'クロネコかけ払い'){
          // クロネコかけ払いの場合
          $total_price = '【お支払い金額】<br />'.number_format($total_price).'円（税抜）';
        }else{
          // それ以外
          $total_price = '【お支払い金額】<br />'.$total_price_zei_number.'円（税込）';
        }
        // 税金のみの金額表示
        $zei_price_text = '消費税：'.number_format($zei_price).'円';
      }
      // 合計金額表示をなしに設定
      if(!($user->setonagi == 1)){
        $total_price = null;
      }

      // オーダーリストの作成
      $order_list=[];
        $carts = Cart::where(['deal_id'=> $deal->id])->get();
        // カート商品の出力
        foreach ($carts as $cart) {
          $orders = Order::where(['cart_id'=> $cart->id])->get();
          foreach ($orders as $order) {
            $user = User::where('id',$deal->user_id)->first();
            $item = Item::where(['id'=> $cart->item_id])->first();
            // セトナギユーザーの場合
            if($user->setonagi == 1){
              $setonagi_user = Setonagi::where('user_id',$user->id)->first();
            }
            // BtoBユーザーの場合
            if(!($user->setonagi == 1)){
              $store = Store::where(['tokuisaki_name'=> $order->tokuisaki_name , 'store_name'=> $order->store_name])->first();
            }

            // 単位を取得
            if ($item->tani == 1){
            $tani = 'ｹｰｽ';
            }
            elseif ($item->tani == 2){
            $tani = 'ﾎﾞｰﾙ';
            }
            elseif ($item->tani == 3){
            $tani = '個';
            }
            elseif ($item->tani == 4){
            $tani = 'kg';
            }

            if(isset($cart->uwagaki_item_name)){
              $item_name = $cart->uwagaki_item_name;
            }else{
              $item_name = $item->item_name;
            }
            if(isset($cart->uwagaki_kikaku)){
              $item_kikaku = $cart->uwagaki_kikaku;
            }else{
              $item_kikaku = $item->kikaku;
            }

            // 出力が分岐する項目
            // BtoBユーザー
            if(!($user->setonagi == 1)){
              // BtoBの場合は配送先の店舗を追加する
              $store = $order->tokuisaki_name.$order->store_name;
              // $nouhin_yoteibi = $order->nouhin_yoteibi;
              $nouhin_yoteibi = '【納品予定日】<br />'.$order->nouhin_yoteibi;
              $nouhin_store = '【納品店舗】<br />'.$store;
              // 未定の金額表示に対応
              if($order->price == '未定'){
                $item_price = '金額未定 ' ;
              }elseif($order->price == '-'){
                $item_price = '- ' ;
              }else{
                $item_price = number_format($order->price).'円 ';
              }
              $array =
                '・'.
                // 商品コード
                // $item->item_id.
                // SKUコード
                // $item->sku_code.
                // 商品名
                $item_name.' '.
                // 数量
                $order->quantity.
                // 単位
                $tani.' '
                // 単価
                // $item_price.' '.$store.' '.$nouhin_yoteibi
                // 配送希望日
                // $order->nouhin_yoteibi.
                // 受け取り
                // ''.
                // 内消費税
                // '10%'
              ;
            // セトナギユーザー
            }else{
              $store = null;
              // 受け取り予定日
              // $nouhin_yoteibi = '【受け取り予定日】<br />'.$order->nouhin_yoteibi;
              $nouhin_yoteibi = '【受け渡し希望日】<br />'.$deal->first_order_nouhin_yoteibi();
              // 納品店舗
              $nouhin_store = null;
              // 金額未定に対応
              if($order->price == '未定' || $order->price == '-'){
                $item_price = 0 ;
              }else{
                $item_price = $order->price;
              }
              $array =
                '・'.
                // 商品コード
                // $item->item_id.
                // SKUコード
                // $item->sku_code.
                // 商品名
                $item_name.' '.
                // 数量
                $order->quantity.
                // 単位
                $tani.' '.
                // 単価
                number_format($item_price).'円'
                // 配送希望日
                // $order->nouhin_yoteibi.
                // 受け取り
                // ''.
                // 内消費税
                // '10%'
              ;
            }
            array_push($order_list, $array);
          }
        }
        // BtoBユーザーのみ任意の商品を出力
        if(!($user->setonagi == 1)){
          $cart_ninis = CartNini::where(['deal_id'=> $deal->id])->get();
          // dd($cart_ninis);
          foreach ($cart_ninis as $cart_nini) {
            $order_ninis = OrderNini::where(['cart_nini_id'=> $cart_nini->id])->get();
            foreach ($order_ninis as $order_nini) {
              // dd($orders);
              // orderslist出力
              $user = User::where('id',$deal->user_id)->first();
              // dd($user);
              $store = Store::where(['tokuisaki_name'=> $order_nini->tokuisaki_name , 'store_name'=> $order_nini->store_name])->first();
              // 出力が分岐する項目
              // BtoBユーザー
              if(!($user->setonagi == 1)){
                // BtoBの場合は配送先の店舗を追加する
                // $store = $order_nini->tokuisaki_name.$order_nini->store_name;
                // $nouhin_yoteibi = $order_nini->nouhin_yoteibi;
                $array =
                  '・[任意の商品] '.
                  $cart_nini->item_name.' × '.
                  // 数量
                  // $order_nini->quantity.' '.$store.' '.$nouhin_yoteibi
                  $order_nini->quantity
                ;
              // セトナギユーザー
              }
              array_push($order_list, $array);
            }
          }
        }
      $order_list = implode('<br>', $order_list);
      // dd($order_list);
      $admin_mail = config('app.admin_mail');
      Mail::send('emails.register', [
          'name' => $name,
          'user' => $user,
          'text' => $text,
          'pay' => $pay,
          'deal' => $deal,
          'nouhin_store' => $nouhin_store,
          'uketori_place' => $uketori_place,
          'uketori_time' => $uketori_time,
          'order_list' => $order_list,
          'nouhin_yoteibi' => $nouhin_yoteibi,
          'zei_price_text' => $zei_price_text,
          'total_price_zei_number_text' => $total_price_zei_number_text,
          'shipping_price_text' => $shipping_price_text,
          'shipping_price_zei_number_text' => $shipping_price_zei_number_text,
          'total_price' => $total_price,
          'memo' => $memo,
      ], function ($message) use ($email , $admin_mail) {
          $message->to($email)->bcc($admin_mail)->subject('SETOnagiご注文承りました。');
      });
      // 注文完了メール送信ここまで
    }

    $user = Auth::guard('user')->user();
    $user->memo = null;
    $user->save();

    return redirect('deal');
  }


// 現在は使っていない
  // public function addsuscess(Request $request){
  //   $user_id = Auth::guard('user')->user()->id;
  //
  //   // $data = $request->all();
  //   // $item_ids = $data['item_id'];
  //   // $quantitys = $data['quantity'];
  //
  //   $deal_id = $request->deal_id;
  //   // 直近の納品予定日に問題がないかチェックする
  //   $today = date("Y-m-d");
  //   $holidays = Holiday::pluck('date');
  //   $currentTime = date('H:i:s');
  //   // 19時より前の処理
  //   if (strtotime($currentTime) < strtotime('17:00:00')) {
  //     $holidays = Holiday::pluck('date')->toArray();
  //     for($i = 1; $i < 10; $i++){
  //       $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
  //       // dd($today_plus2);
  //       $key = array_search($today_plus,(array)$holidays,true);
  //       if($key){
  //           // 休みでないので納品日を格納
  //       }else{
  //           // 休みなので次の日付を探す
  //           $nouhin_yoteibi = $today_plus;
  //           break;
  //       }
  //     }
  //   }else{
  //   // 19時より後の処理
  //     $holidays = Holiday::pluck('date')->toArray();
  //     for($i = 2; $i < 10; $i++){
  //       $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
  //       // dd($today_plus2);
  //       $key = array_search($today_plus,(array)$holidays,true);
  //       if($key){
  //           // 休みでないので納品日を格納
  //       }else{
  //           // 休みなので次の日付を探す
  //           $nouhin_yoteibi = $today_plus;
  //           break;
  //       }
  //     }
  //   }
  //
  //   // 納品日が今の日付より前に設定されていないかチェック
  //   $nouhin_youbi_list = [];
  //   $carts = Cart::where(['deal_id'=> $deal_id])->get();
  //   foreach ($carts as $cart) {
  //     $orders = Order::where(['cart_id'=> $cart->id])->get();
  //     foreach ($orders as $order) {
  //       $array = $order->nouhin_yoteibi;
  //       $sano_nissuu = ((strtotime($order->nouhin_yoteibi) - strtotime($today)) / 86400);
  //       $nouhin_yoteibi = date('Y年m月d日', strtotime($order->nouhin_yoteibi));
  //       if($sano_nissuu < 0){
  //         // 納品日エラーの場合カート画面に戻す
  //         $data=[
  //           'id' => $deal_id,
  //           'order_id' => $order->id,
  //           'nouhin_yoteibi' => $nouhin_yoteibi,
  //         ];
  //         return redirect()->route('dealdetail',$data);
  //       }
  //       array_push($nouhin_youbi_list, $sano_nissuu);
  //     }
  //   }
  //
  //   $deal=Deal::firstOrNew(['id'=> $deal_id]);
  //   $deal->status = '発注済';
  //   $deal->success_time = Carbon::now();
  //   // セトナギユーザーのみ「受け取り場所」「時間帯」「支払い方法を保存」
  //   if($request->uketori_siharai){
  //     $deal->uketori_siharai = $request->uketori_siharai;
  //     $deal->uketori_place = $request->uketori_place;
  //     $deal->uketori_time = $request->uketori_time;
  //   }
  //   $deal->memo = $request->memo;
  //   $deal->save();
  //
  //
  //
  //   // foreach($item_ids as $key => $input) {
  //   //   $cart = Cart::firstOrNew(['user_id'=> $user_id , 'item_id'=> $item_ids[$key], 'deal_id'=> $deal_id]);
  //   //   $cart->user_id = $user_id;
  //   //   $cart->deal_id = $deal_id;
  //   //   $cart->quantity = isset($quantitys[$key]) ? $quantitys[$key] : null;
  //   //   $cart->save();
  //   // }
  //
  //   return redirect('deal');
  // }

  public function dealcancel(Request $request){
    $user_id = Auth::guard('user')->user()->id;

    // $data = $request->all();
    // $item_ids = $data['item_id'];
    // $quantitys = $data['quantity'];

    $deal_id = $request->deal_id;

    // キャンセルができるか判定
    $deal=Deal::where(['id'=> $deal_id])->first();
    if($deal->status == 'キャンセル'){
      $id = $deal_id;
      $message = 'キャンセルエラーです。';
      $data=[
        'id' => $deal_id,
        'message' => $message,
      ];
      return redirect()->route('dealdetail',$data);
    }

    $user = User::where(['id'=> $user_id])->first();
    $setonagi = Setonagi::where(['user_id'=> $user_id])->first();


    // shipping_codeの内容を保存
    if($setonagi){
      $shipping_code = $setonagi->shipping_code;
      // 配送設定を呼び出し
      if(isset($shipping_code)){
        $shipping_setting = ShippingSetting::where(['shipping_code'=> $shipping_code,'shipping_method'=> $deal->uketori_place])->first();
        $shipping_name = $shipping_setting->shipping_name;
        $shipping_price = $shipping_setting->shipping_price;
        $calender_id= $shipping_setting->calender_id;
      }
    }

    // 注文完了時間
    $success_time = $deal->success_time;
    $success_jikan = date('H:i:s', strtotime($success_time));
    // dd($success_jikan);

    // 注文完了日から受け取り予定日時を取得
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');
    // 19時より前の処理
    if (strtotime($success_jikan) < strtotime('17:00:00')) {
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 1; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($success_time.'+'.$i.'day'));
        // dd($today_plus2);
        $key = array_search($today_plus,(array)$holidays,true);
        if($key){
            // 休みでないので納品日を格納
        }else{
            // 休みなので次の日付を探す
            $nouhin_yoteibi = $today_plus;
            break;
        }
      }
    }else{
    // 19時より後の処理
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 2; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($success_time.'+'.$i.'day'));
        // dd($today_plus2);
        $key = array_search($today_plus,(array)$holidays,true);
        if($key){
            // 休みでないので納品日を格納
        }else{
            // 休みなので次の日付を探す
            $nouhin_yoteibi = $today_plus;
            break;
        }
      }
    }
    // dd($nouhin_yoteibi);

    if(isset($setonagi->shipping_code)){
      $nouhin_yoteibi = $deal->first_order_nouhin_yoteibi_cancel();
    }

    // 注文の翌営業日の納品予定19時を取得
    $zenjitu19ji = date($nouhin_yoteibi.'17:00:00');
    // 納品予定日の19時を取得
    $zenjitu19ji = date('Y-m-d H:i:s', strtotime($zenjitu19ji.'-1 day'));
    // dd($zenjitu19ji);

    // 今の日付時間
    $now = date("Y-m-d H:i:s");
    // 次の営業日の前日19時以降はキャンセル不可

    // 翌営業日締め時間より前
    if (strtotime($now) < strtotime($zenjitu19ji)) {
      // dd('キャンセル可');
    }else{
      // dd('キャンセル不可');
    // 翌営業日締め時間より後
    // キャンセルはできないのでその旨をアラート
      $id= $deal_id;
      $message = '締め時間を過ぎているためキャンセルできません。';
      $data=[
        'id' => $deal_id,
        'message' => $message,
      ];
      return redirect()->route('dealdetail',$data);
    }


    // セトナギユーザーの場合
    if($user->setonagi == 1){

      // ヤマトAPIキャンセル
      if($deal->uketori_siharai == 'クロネコかけ払い'){
        $client = new Client();
        $url = config('app.kakebarai_cancel');
        $kakebarai_traderCode = config('app.kakebarai_traderCode');
        $kakebarai_passWord = config('app.kakebarai_passWord');
        $envi = config('app.envi');
        $option = [
          'headers' => [
            'Accept' => '*/*',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'charset' => 'UTF-8',
          ],
          'form_params' => [
            'traderCode' => $kakebarai_traderCode,
            // 取引id
            'orderNo' => $deal_id.$envi,
            // バイヤーid
            'buyerId' => $user_id,
            'passWord' => $kakebarai_passWord
          ]
        ];
        // dd($option);
        $response = $client->request('POST', $url, $option);
        $result = simplexml_load_string($response->getBody()->getContents());
        // dd($result);
        if($result->returnCode == 1){
            $id= $deal_id;
            $message = 'クロネコかけ払いキャンセルエラーです。';
            $data=[
              'id' => $deal_id,
              'cancel_error' => $message,
            ];
            return redirect()->route('dealdetail',$data);
          }
        }




      // クレジットカードAPIキャンセル
      // if($deal->uketori_siharai == 'クレジットカード払い'){
      //   // dd($request->token_api);
      //   // EPトークン取得
      //   $client = new Client();
      //   // $url = 'https://api.kuronekoyamato.co.jp/api/credit';
      //   $url = config('app.collect_cancel');
      //   $collect_tradercode = config('app.collect_tradercode');
      //   $option = [
      //     'headers' => [
      //       'Accept' => '*/*',
      //       'Content-Type' => 'application/x-www-form-urlencoded',
      //       'charset' => 'UTF-8',
      //     ],
      //     'form_params' => [
      //       'function_div' => 'A06',
      //       'trader_code' => $collect_tradercode,
      //       'order_no' => $deal_id,
      //     ]
      //   ];
      //   // dd($option);
      //   $response = $client->request('POST', $url, $option);
      //   $result = simplexml_load_string($response->getBody()->getContents());
      //   if($result->returnCode == 1){
      //     $id= $deal_id;
      //     $message = 'クレジットカード払いキャンセルエラーです。';
      //     $data=[
      //       'id' => $deal_id,
      //       'cancel_error' => $message,
      //     ];
      //     return redirect()->route('dealdetail',$data);
      //   }
      // }
    }




    // 在庫を戻す処理
    if(isset($deal)){
      $setonagi=Setonagi::where(['user_id'=> $deal->user_id])->first();
      $carts = Cart::where(['deal_id'=> $deal->id])->get();
      foreach ($carts as $cart) {
        $item=Item::where(['id'=> $cart->item_id])->first();
        $order = Order::where(['cart_id'=>$cart->id])->first();
        $addtype = $cart->addtype;
        if( $addtype == 'addsetonagi' || $addtype == 'addspecialprice' || $addtype == 'addbuyerrecommend' && isset($setonagi)){
          // 在庫数を増やすロジック
          $item->zaikosuu += $order->quantity;
          // 在庫数をデータベースに更新
          $item->save();
        }elseif ( $addtype == 'addbuyerrecommend' ) {
          $kaiin_number = $user->kaiin_number;
          $store = Store::where(['tokuisaki_name'=>$order->tokuisaki_name,'store_name'=>$order->store_name])->first();
          // 限定数の在庫増減があるか確認
          $buyer_recommend_item = BuyerRecommend::where('tokuisaki_id', $store->tokuisaki_id)
          ->where(function($query) use ($store) {
              $query->where('gentei_store', null)
                    ->orWhere('gentei_store', $store->store_name);
          })
          ->where('zaikokanri', null)
          ->whereNotNull('zaikosuu')
          ->where('price', '>=', '1')
          ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
          ->where('nouhin_end', '>=', $order->nouhin_yoteibi)
          ->where('start', '<=' , $deal->success_time)
          ->where('end', '>=', $deal->success_time)
          ->first();
          // Log::debug($buyer_recommend_item);
          if($buyer_recommend_item){
            // 在庫数を増減させるロジック
            $buyer_recommend_item->zaikosuu += $order->quantity;
            // 在庫数をデータベースに更新
            $buyer_recommend_item->save();
          }
          // 在庫を使う商品があるか確認
          $not_buyer_recommend_item = BuyerRecommend::where('tokuisaki_id', $store->tokuisaki_id)
          ->where(function($query) use ($store) {
              $query->where('gentei_store', null)
                    ->orWhere('gentei_store', $store->store_name);
          })
          ->where('zaikokanri', null)
          ->where('zaikosuu', null)
          ->where('price', '>=', '1')
          ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
          ->where('nouhin_end', '>=', $order->nouhin_yoteibi)
          ->where('start', '<=' , $deal->success_time)
          ->where('end', '>=', $deal->success_time)
          ->first();
          if($not_buyer_recommend_item){
            // 在庫数を増減させるロジック
            $item->zaikosuu += $order->quantity;
            // 在庫数をデータベースに更新
            $item->save();
          }
        }
      }
    }

    $deal=Deal::firstOrNew(['id'=> $deal_id]);
    $deal->status = 'キャンセル';
    $deal->cancel_time = Carbon::now();
    $deal->save();


    // キャンセル完了メールの送信
    $user = User::where('id',$user_id)->first();

    $name = $user->name;
    // $email = $user->email;
    $email = $user->email;
    $url = url('');
    $text = 'SETOnagiをご利用くださいまして誠にありがとうございます。<br />
    下記のご注文がキャンセルされましたのでご確認をお願いいたします。<br />
    <br />
    【注文番号】<br />'.$deal->id.'<br /><br />
    【ご注文内容】';
    if($user->setonagi == 1){
      // 支払方法
      $pay = '【お支払い方法】<br />'.$deal->uketori_siharai;
      // 受け取り場所
      $uketori_place = '【配送方法】<br />'.$deal->uketori_place;
      // 受け取り予定日
      $uketori_time = '【受け渡し希望時間】<br />'.$deal->uketori_time;
      if(isset($shipping_code)){
        // 受け取り場所を配送方法に修正
        $uketori_place = '【配送方法】<br />'.$shipping_name;
      }
    }else{
      // 支払方法
      $pay = null;
      // 受け取り場所
      $uketori_place = null;
      // 受け取り時間
      $uketori_time = null;
    }
    // メモ
    $memo =  '【通信欄】<br />'.$deal->memo;

    // 合計金額
    $total_price = [];
    $carts = Cart::where(['deal_id'=> $deal->id])->get();
    // カート商品の出力
    foreach ($carts as $cart) {
      $orders = Order::where(['cart_id'=> $cart->id])->get();
      foreach ($orders as $order) {
        // dd($store);
        // 金額未定に対応
        if($order->price == '未定' || $order->price == '-'){
          $array = 0;
        }else{
          $array = $order->price * $order->quantity;
        }
        array_push($total_price, $array);
      }
    }

    // 送料がかかる場合の表示設定
    // 商品合計
    $total_price = array_sum($total_price);
    // 商品合計税込
    $total_price_zei = floor($total_price * (108 / 100));
    // 8%税額
    $zeinomi8 = $total_price_zei - $total_price;
    // 商品合計税込（カンマ表示）
    $total_price_zei_number = number_format($total_price_zei);
    // 8%対象消費合計額テキスト
    if($deal->uketori_siharai == 'クロネコかけ払い'){
      // クロネコかけ払いの場合
      $total_price_zei_number_text = '軽減税率8％対象：'.number_format($total_price).'円（税抜）';
    }else{
      // それ以外
      $total_price_zei_number_text = '軽減税率8％対象：'.number_format($total_price).'円（税抜）<br />軽減税率8％消費税：'.number_format($zeinomi8).'円<br />軽減税率8％対象：'.$total_price_zei_number.'円（税込）';
    }

    if(isset($shipping_price)){
      // 送料税込
      $shipping_price_zei = floor($shipping_price * (110 / 100));
      // 10%税のみ
      $zeinomi10 = $shipping_price_zei - $shipping_price;
      // 送料（カンマ表示）
      $shipping_price_number = number_format($shipping_price);
      // 送料税込（カンマ表示）
      $shipping_price_zei_number = number_format($shipping_price_zei);
      // 10%対象消費合計額（カンマ表示）
      $shipping_price_zei_number_text = '税率10%対象：'.$shipping_price_number.'円（税抜）<br />税率10％消費税：'.number_format($zeinomi10).'円<br />税率10％対象：'.$shipping_price_zei_number.'円（税込）';
      // 税込合計金額
      $all_total_price = $shipping_price_zei + $total_price_zei;
      // 税金の計算
      $zei_price = $all_total_price - $total_price - $shipping_price;
      // 合計金額表示
      $total_price = '【お支払い金額】<br />'.number_format($all_total_price).'円（税込）';
      // 送料のみの金額表示
      $shipping_price_text = '【送料】<br />'.$shipping_price_number.'円';
      // 税金のみの金額表示
      $zei_price_text = '消費税：'.number_format($zei_price).'円';
    }else{
      $shipping_price_zei_number_text = null;
      $shipping_price_text = null;
      // 税金の計算
      $zei_price = $total_price_zei - $total_price;
      // 金額表示部分（クロネコかけ払いの場合）
      if($deal->uketori_siharai == 'クロネコかけ払い'){
        // クロネコかけ払いの場合
        $total_price = '【お支払い金額】<br />'.number_format($total_price).'円（税抜）';
      }else{
        // それ以外
        $total_price = '【お支払い金額】<br />'.$total_price_zei_number.'円（税込）';
      }
      // 税金のみの金額表示
      $zei_price_text = '消費税：'.number_format($zei_price).'円';
    }
    // 合計金額表示をなしに設定
    if(!($user->setonagi == 1)){
      $total_price = null;
    }

    // オーダーリストの作成
    $order_list=[];
      $carts = Cart::where(['deal_id'=> $deal->id])->get();
      // カート商品の出力
      foreach ($carts as $cart) {
        $orders = Order::where(['cart_id'=> $cart->id])->get();
        foreach ($orders as $order) {
          $user = User::where('id',$deal->user_id)->first();
          $item = Item::where(['id'=> $cart->item_id])->first();
          // セトナギユーザーの場合
          if($user->setonagi == 1){
            $setonagi_user = Setonagi::where('user_id',$user->id)->first();
          }
          // BtoBユーザーの場合
          if(!($user->setonagi == 1)){
            $store = Store::where(['tokuisaki_name'=> $order->tokuisaki_name , 'store_name'=> $order->store_name])->first();
          }

          // 単位を取得
          if ($item->tani == 1){
          $tani = 'ｹｰｽ';
          }
          elseif ($item->tani == 2){
          $tani = 'ﾎﾞｰﾙ';
          }
          elseif ($item->tani == 3){
          $tani = '個';
          }
          elseif ($item->tani == 4){
          $tani = 'kg';
          }

          if(isset($cart->uwagaki_item_name)){
            $item_name = $cart->uwagaki_item_name;
          }else{
            $item_name = $item->item_name;
          }
          if(isset($cart->uwagaki_kikaku)){
            $item_kikaku = $cart->uwagaki_kikaku;
          }else{
            $item_kikaku = $item->kikaku;
          }

          // 出力が分岐する項目
          // BtoBユーザー
          if(!($user->setonagi == 1)){
            // BtoBの場合は配送先の店舗を追加する
            $store = $order->tokuisaki_name.$order->store_name;
            // $nouhin_yoteibi = $order->nouhin_yoteibi;
            $nouhin_yoteibi = '【納品予定日】<br />'.$order->nouhin_yoteibi;
            $nouhin_store = '【納品店舗】<br />'.$store;
            // 未定の金額表示に対応
            if($order->price == '未定'){
              $item_price = '金額未定 ' ;
            }elseif($order->price == '-'){
              $item_price = '- ' ;
            }else{
              $item_price = number_format($order->price).'円 ';
            }
            $array =
              '・'.
              // 商品コード
              // $item->item_id.
              // SKUコード
              // $item->sku_code.
              // 商品名
              $item_name.' '.
              // 数量
              $order->quantity.
              // 単位
              $tani.' '
              // 単価
              // $item_price.' '.$store.' '.$nouhin_yoteibi
              // 配送希望日
              // $order->nouhin_yoteibi.
              // 受け取り
              // ''.
              // 内消費税
              // '10%'
            ;
          // セトナギユーザー
          }else{
            $store = null;
            // 受け取り予定日
            // $nouhin_yoteibi = '【受け取り予定日】<br />'.$order->nouhin_yoteibi;
            $nouhin_yoteibi = '【受け渡し希望日】<br />'.$deal->first_order_nouhin_yoteibi();
            // 納品店舗
            $nouhin_store = null;
            // 金額未定に対応
            if($order->price == '未定' || $order->price == '-'){
              $item_price = 0 ;
            }else{
              $item_price = $order->price;
            }
            $array =
              '・'.
              // 商品コード
              // $item->item_id.
              // SKUコード
              // $item->sku_code.
              // 商品名
              $item_name.' '.
              // 数量
              $order->quantity.
              // 単位
              $tani.' '.
              // 単価
              number_format($item_price).'円'
              // 配送希望日
              // $order->nouhin_yoteibi.
              // 受け取り
              // ''.
              // 内消費税
              // '10%'
            ;
          }
          array_push($order_list, $array);
        }
      }
      // BtoBユーザーのみ任意の商品を出力
      if(!($user->setonagi == 1)){
        $cart_ninis = CartNini::where(['deal_id'=> $deal->id])->get();
        // dd($cart_ninis);
        foreach ($cart_ninis as $cart_nini) {
          $order_ninis = OrderNini::where(['cart_nini_id'=> $cart_nini->id])->get();
          foreach ($order_ninis as $order_nini) {
            // dd($orders);
            // orderslist出力
            $user = User::where('id',$deal->user_id)->first();
            // dd($user);
            $store = Store::where(['tokuisaki_name'=> $order_nini->tokuisaki_name , 'store_name'=> $order_nini->store_name])->first();
            // 出力が分岐する項目
            // BtoBユーザー
            if(!($user->setonagi == 1)){
              // BtoBの場合は配送先の店舗を追加する
              // $store = $order_nini->tokuisaki_name.$order_nini->store_name;
              // $nouhin_yoteibi = $order_nini->nouhin_yoteibi;
              $array =
                '・[任意の商品] '.
                $cart_nini->item_name.' × '.
                // 数量
                // $order_nini->quantity.' '.$store.' '.$nouhin_yoteibi
                $order_nini->quantity
              ;
            // セトナギユーザー
            }
            array_push($order_list, $array);
          }
        }
      }
    $order_list = implode('<br>', $order_list);
    // dd($order_list);
    $admin_mail = config('app.admin_mail');
    Mail::send('emails.register', [
        'name' => $name,
        'user' => $user,
        'text' => $text,
        'pay' => $pay,
        'deal' => $deal,
        'nouhin_store' => $nouhin_store,
        'uketori_place' => $uketori_place,
        'uketori_time' => $uketori_time,
        'order_list' => $order_list,
        'nouhin_yoteibi' => $nouhin_yoteibi,
        'zei_price_text' => $zei_price_text,
        'total_price_zei_number_text' => $total_price_zei_number_text,
        'shipping_price_text' => $shipping_price_text,
        'shipping_price_zei_number_text' => $shipping_price_zei_number_text,
        'total_price' => $total_price,
        'memo' => $memo,
    ], function ($message) use ($email , $admin_mail) {
        $message->to($email)->bcc($admin_mail)->subject('SETOnagiご注文のキャンセルを承りました。');
    });
    // 注文完了メール送信ここまで

    // foreach($item_ids as $key => $input) {
    //   $cart = Cart::firstOrNew(['user_id'=> $user_id , 'item_id'=> $item_ids[$key], 'deal_id'=> $deal_id]);
    //   $cart->user_id = $user_id;
    //   $cart->deal_id = $deal_id;
    //   $cart->quantity = isset($quantitys[$key]) ? $quantitys[$key] : null;
    //   $cart->save();
    // }

    return redirect('deal');
  }



  public function updatecart(Request $request){

    $cart_id = $request->cart_id;

    $cart=Cart::where('id',$cart_id)->first();
    $cart->discount = $request->discount;
    $cart->quantity = $request->quantity;
    $cart->save();

    $id = $cart->deal_id;
    $cart=Cart::where('id',$cart_id)->first();



    $data=[
      'discount'=> $cart->discount,
      'quantity'=> $cart->quantity,
      'cart_id' => $cart_id,
    ];
    echo json_encode($data);
    return ;
  }

  public function repeatorder(){
    $user = Auth::guard('user')->user();
    $user_id = $user->id;
    $kaiin_number = $user->kaiin_number;
    // dd($user_id);
    $items = Item::get();
    // 店舗一覧取得
    $store_users = StoreUser::where('user_id',$kaiin_number)->get(['store_id','tokuisaki_id']);
    $stores = [];
    $n=1;
    foreach ($store_users as $store_user) {
    $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
      array_push($stores, $store);
    $n++;
    }
    $repeatcarts = Repeatcart::where('kaiin_number',$user->kaiin_number)->get();

    $user_id = Auth::guard('user')->user()->id;
    $categories = Category::get()->groupBy('bu_ka_name');
    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

    $data=[
      'id'=>$user_id,
      'items'=>$items,
      'stores'=>$stores,
      'user'=>$user,
      'repeatcarts'=>$repeatcarts,
      'categories' => $categories,
      'favorite_categories' => $favorite_categories
    ];
    return view('repeatorder', $data);
  }


  public function stoprepeatorder(Request $request){

    $stop_id = $request->stop_id;
    $repeatorder = Repeatorder::where('id',$stop_id)->first();
    $repeatorder->stop_flg = 1;
    $repeatorder->save();

    $repeatcart = Repeatcart::where('id',$repeatorder->cart_id)->first();
    $user = User::where('kaiin_number' , $repeatcart->kaiin_number)->first();

    $item = Item::where(['item_id'=> $repeatcart->item_id,'sku_code'=> $repeatcart->sku_code])->first();

    $name = 'ご担当者';
    $kaiin_number = $user->kaiin_number;
    $user_name = $user->name;
    $email = $user->email;
    $tel = $user->tel;
    $admin_mail = config('app.admin_mail');
    $sales_admin_mail = config('app.sales_admin_mail');
    $text = '下記のユーザーからリピートオーダーの停止申請がありました。<br />
    <br />
    【会員情報】<br />
    得意先名：'.$repeatorder->tokuisaki_name.'<br />
    会員番号：'.$kaiin_number.'<br />
    お名前：'.$user_name.'<br />
    メールアドレス：'.$email.'<br />
    電話番号：'.$tel.'<br />
    <br />
    【リピートオーダー詳細】<br />
    商品名：'.$item->item_name.'<br />
    価格：'.number_format($repeatorder->price).'円<br />
    数量：'.$repeatorder->quantity.'<br />
    納品先店舗：'.$repeatorder->store_name.'<br />
    開始日：'.$repeatorder->startdate.'<br />
    <br />
    ご対応をお願いいたします。';
    Mail::send('emails.register', [
        'name' => $name,
        'text' => $text,
        'admin_mail' => $admin_mail,
    ], function ($message) use ($sales_admin_mail) {
        $message->to($sales_admin_mail)->subject('リピートオーダーの停止申請がありました。');
    });

    return redirect('repeatorder');
  }



  public function getzipcode(){
    return view('zipcode');
  }


  public function postzipcode(Request $request){
      $zipcode = $request->zipcode;

      // dd($zipcode);
      $client = new Client();

      // $url = 'http://zipcloud.ibsnet.co.jp/api/search';
      // $option = [
      //   'headers' => [
      //     'Accept' => '*/*',
      //     'Content-Type' => 'application/x-www-form-urlencoded'
      //   ],
      //   'form_params' => [
      //     'zipcode' => $zipcode
      //   ]
      // ];
      // $response = $client->request('POST', $url, $option);
      // $result = json_decode($response->getBody()->getContents(), true);
      // dd($result);

      $url = 'https://demo.yamato-credit-finance.jp/kuroneko-anshin/AN060APIAction.action';
      $option = [
        'headers' => [
          'Accept' => '*/*',
          'Content-Type' => 'application/x-www-form-urlencoded',
          'charset' => 'UTF-8',
        ],
        'form_params' => [
          'traderCode' => '330000051',
          'cId' => '1234-57',
          'hjkjKbn' => '1',
          'houjinKaku' => '1',
          'houjinZengo' => '1',
          'sMei' => '黒猫商店',
          'shitenMei' => '高田馬場支店',
          'sMeikana' => 'クロネコショウテン',
          'shitenMeikana' => 'タカ',
          'ybnNo' => '7200824',
          'Adress' => '広島県福山市多治米町',
          'telNo' => '080-2888-5281',
          'keitaiNo' => '080-2888-5281',
          'gyscod1' => '07',
          'gyscod2' => '082',
          'setsurituNgt' => '200104',
          'shk' => '12345',
          'nsyo' => '12345',
          'kmssyainsu' => '12345',
          'daikjmeiSei' => '黒猫',
          'daikjmeiMei' => '花子',
          'daiknameiSei' => 'クロネコ',
          'daiknameiMei' => 'ハナコ',
          'daiYbnno' => '7200824',
          'daiAddress' => '広島県福山市多治米町',
          'szUmu' => '0',
          'szHjkjKbn' => '1',
          'szHoujinKaku' => '12',
          'szHoujinZengo' => '1',
          'szHonknjmei' => '黒猫商店',
          'szHonknamei' => 'クロネコショウテン',
          'szYbnno' => '7200824',
          'szAddress' => '広島県福山市多治米町',
          'szTelno' => '0849525627',
          'szDaikjmei_sei' => '黒猫',
          'szDaikjmei_mei' => '花子',
          'szDaiknamei_sei' => 'クロネコ',
          'szDaiknamei_mei' => 'ハナコ',
          'sqssfKbn' => '1',
          'sqYbnno' => '7200824',
          'sqAddress' => '広島県福山市多治米町',
          'sofuKnjnam' => '黒猫商店',
          'sofuTntnam' => '黒猫花子',
          'syz' => '購買部',
          'kmsTelno' => '084-952-5627',
          'shrhohKbn' => '2',
          'passWord' => 'UzhJlu8E'
        ]
      ];
      $response = $client->request('POST', $url, $option);
      $result = simplexml_load_string($response->getBody()->getContents());
      // $result = json_decode($response->getBody()->getContents(), true);
      dd($result);

  }

  // 繰り返し使う納品予定日
  function nouhin_yoteibi(){
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');
    // 19時より前の処理
    if (strtotime($currentTime) < strtotime('17:00:00')) {
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 1; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
        // dd($today_plus2);
        $key = array_search($today_plus,(array)$holidays,true);
        if($key){
            // 休みでないので納品日を格納
        }else{
            // 休みなので次の日付を探す
            $nouhin_yoteibi = $today_plus;
            break;
        }
      }
    }else{
    // 19時より後の処理
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 2; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
        // dd($today_plus2);
        $key = array_search($today_plus,(array)$holidays,true);
        if($key){
            // 休みでないので納品日を格納
        }else{
            // 休みなので次の日付を探す
            $nouhin_yoteibi = $today_plus;
            break;
        }
      }
    }
    return $nouhin_yoteibi;
  }



  public function favoriteitem(){
    $user_id = Auth::guard('user')->user()->id;
    $categories = Category::get()->groupBy('bu_ka_name');
    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

    $favorite_items= favorite::where('user_id',$user_id)->get();

    $data=[
      'categories' => $categories,
      'favorite_categories' => $favorite_categories,
      'favorite_items' => $favorite_items,
    ];
    return view('user.auth.favoriteitem', $data);

  }


  public function addfavoriteitem(Request $request){

    $user_id = Auth::guard('user')->user()->id;
    $item_id = $request->item_id;

    // dd($item_id);

    $favorite_in = Favorite::where(['user_id'=> $user_id , 'item_id'=> $item_id])->first();
    if($favorite_in){
      return response()->json([
      'message' => 'favorite_in',
      ]);
    }
    // $cart_in=Cart::where(['user_id'=> $user_id , 'item_id'=> $item->id , 'deal_id'=> null])->first();
    // if($cart_in){
    //   return response()->json([
    //   'message' => 'cart_in',
    //   ]);
    // }
    $favorite_item = Favorite::firstOrNew(['user_id'=> $user_id , 'item_id'=> $item_id]);
    $favorite_item->save();

    $data = "sucsess";
    return redirect()->route('confirm',$data);
  }


  public function removefavoriteitem(Request $request){

    $user_id = Auth::guard('user')->user()->id;
    $item_id = $request->item_id;

    $favorite_in = Favorite::where(['user_id'=> $user_id , 'item_id'=> $item_id])->first()->delete();;

    $data = "sucsess";
    return redirect()->route('confirm',$data);

  }



}
