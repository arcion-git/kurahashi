<?php

namespace App\Http\Controllers;

use App\User;
use App\Cart;
use App\Deal;
use App\Item;
use App\Category;
use App\category_item;
use App\Tag;

// 時間に関する処理
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

// CSVインポート
use App\Imports\ItemImport;
use App\Imports\UserImport;
use App\Imports\CategoryImport;
use App\Imports\TagImport;
use App\Imports\CategoryItemImport;
use Maatwebsite\Excel\Facades\Excel;

class AdminPageController extends Controller
{
  public function __construct(){
		$this->middleware('admin');
	}

  public function index()
  {
    $deals =  Deal::get();
    return view('admin/home', ['deals' => $deals]);
  }

  public function dealdetail($id){
    $deal = Deal::where('id',$id)->first();

    $carts = Cart::where(['user_id'=>$deal->user_id, 'deal_id'=> $id])->get();
    $data=[
      'carts'=>$carts,
      'deal'=>$deal
    ];
    return view('dealdetail', $data);
  }

  public function updatecart(Request $request){

    $cart_id = $request->cart_id;

    $cart=Cart::where('id',$cart_id)->first();
    $cart->discount = $request->discount;
    $cart->quantity = $request->quantity;
    $cart->save();

    $id = $cart->user_id;
    return redirect()->route('admin.dealdetail',$id);
  }


  public function intervalupdatecart(Request $request){

    $cart_id = $request->cart_id;

    $cart=Cart::where('id',$cart_id)->first();
    $cart->discount = $request->discount;
    $cart->quantity = $request->quantity;
    $cart->save();

    $data=[
      'discount'=>$cart->discount,
      'quantity'=>$cart->quantity,
    ];

    return view('dealdetail', $data);
  }




  public function user(){

    $users = User::get();

    return view('admin.auth.user', ['users' => $users]);
  }

  public function item(){

    $items = Item::get();

    return view('admin.auth.item', ['items' => $items]);
  }

  public function csv(){
    return view('admin.auth.csv');
  }

  public function userimport(){
  Excel::import(new UserImport, request()->file('file'));
  return back();
  }

  public function itemimport(){
  Excel::import(new ItemImport, request()->file('file'));
  return back();
  }

  public function CategoryItemimport(){
  category_item::truncate();
  Excel::import(new CategoryItemImport, request()->file('file'));
  return back();
  }

  public function categoryimport(){
  Category::truncate();
  Excel::import(new CategoryImport, request()->file('file'));
  return back();
  }

  public function tagimport(){
  Tag::truncate();
  Excel::import(new TagImport, request()->file('file'));
  return back();
  }



  public function download(){
    return view('admin.auth.download');
  }

  public function userdeal($id){

    $deals = Deal::where('user_id',$id)->get();

    return view('admin.home', ['deals' => $deals]);
  }

}
