<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//
// Route::get('/', function () {
//     return view('welcome');
// });



  Route::post('/form/import-csv', 'CsvImportController@store');


  // 顧客側ページ
  // Route::get('/', 'LoginPageController@index')->name('home');
  Route::get('/', 'LoginPageController@setonagi');

  Route::get('/setonagi', 'LoginPageController@setonagi')->name('setonagi');
  Route::get('/special_price', 'LoginPageController@index')->name('home');
  Route::get('/recommend', 'LoginPageController@index')->name('recommend');

  Route::get('/category/{id}', 'LoginPageController@category');
  Route::get('/confirm', 'LoginPageController@confirm')->name('confirm');
  Route::get('/approval', 'LoginPageController@approval')->name('approval');
  Route::get('/cart', 'LoginPageController@cart');
  Route::get('/deal', 'LoginPageController@deal')->name('deal');
  Route::get('/line', 'LoginPageController@line')->name('line');
  Route::get('/favorite', 'LoginPageController@favorite')->name('favorite');
  Route::get('/favoriteitem', 'LoginPageController@favoriteitem')->name('favoriteitem');
  Route::get('/user/deal/{id}', 'LoginPageController@dealdetail')->name('dealdetail');
  Route::get('/repeatorder', 'LoginPageController@repeatorder')->name('repeatorder');
  Route::get('/contact', 'LoginPageController@contact')->name('contact');
  Route::post('/postcontact', 'LoginPageController@postcontact');

  // Route::get('/saiji', 'LoginPageController@saiji')->name('setonagi');
  Route::get('/guide', 'LoginPageController@guide')->name('guide');
  Route::get('/firstguide', 'LoginPageController@firstguide')->name('firstguide');
  Route::get('/law', 'LoginPageController@law')->name('law');
  Route::get('/privacypolicy', 'LoginPageController@privacypolicy')->name('privacypolicy');

  Route::get('/user/test', 'LoginPageController@test');

  // register
  Route::get('/getzipcode', 'LoginPageController@getzipcode');
  Route::post('/postzipcode', 'LoginPageController@postzipcode');

  // Ajax GET
  Route::get('/cart', 'LoginPageController@cart');
  Route::get('/order', 'LoginPageController@order');

  // Ajax POST&GET（ユーザー側）
  Route::post('/dealorder', 'LoginPageController@dealorder');
  Route::get('/search', 'LoginPageController@search');
  Route::post('/search', 'LoginPageController@search');

  // Ajax POST&GET（管理者側）
  Route::post('/admin/dealorder', 'AdminPageController@dealorder');

  // Ajax POST
  Route::post('/addcart', 'LoginPageController@addcart');
  Route::post('/removecart', 'LoginPageController@removecart');

  Route::post('/addfavoriteitem', 'LoginPageController@addfavoriteitem');
  Route::post('/removefavoriteitem', 'LoginPageController@removefavoriteitem');

  Route::post('/removeorder', 'BothController@removeorder');
  Route::post('/removeordernini', 'BothController@removeordernini');
  Route::post('/change_quantity', 'BothController@change_quantity');
  Route::post('/nini_change_item_name', 'BothController@nini_change_item_name');
  Route::post('/nini_change_tantou', 'BothController@nini_change_tantou');
  Route::post('/nini_change_quantity', 'BothController@nini_change_quantity');
  Route::post('/change_nouhin_yoteibi', 'BothController@change_nouhin_yoteibi');
  Route::post('/nini_change_nouhin_yoteibi', 'BothController@nini_change_nouhin_yoteibi');
  Route::post('/change_store', 'BothController@change_store');
  Route::post('/add_all_store', 'BothController@add_all_store');
  Route::post('/nini_change_store', 'BothController@nini_change_store');
  Route::post('/nini_add_all_store', 'BothController@nini_add_all_store');
  Route::post('/clonecart', 'BothController@clonecart');
  Route::post('/addordernini', 'BothController@addordernini');
  Route::post('/addniniorder', 'BothController@addniniorder');

  // カートの内容を自動更新
  Route::post('/updatecart', 'LoginPageController@updatecart');
  // 注文画面で内容を自動更新
  Route::post('/updateorder', 'LoginPageController@updateorder');




  // Ajax POST テスト
  Route::post('/updateordergtest', 'LoginPageController@updateordergtest');

  // POST
  Route::post('/adddeal', 'LoginPageController@adddeal');
  Route::post('/addsuscess', 'LoginPageController@addsuscess');
  Route::post('/dealcancel', 'LoginPageController@dealcancel');

  Route::post('/dealcart', 'LoginPageController@dealcart');
  Route::get('/showdealcart', 'LoginPageController@showdealcart');

  Route::post('/user/post/favoritecategory','LoginPageController@PostFavoriteCategory');
  Route::post('/user/edit/favoritecategory','LoginPageController@EditFavoriteCategory');

  // アンケート画面
  Route::get('/user/questionnaire', 'LoginPageController@questionnaire');


  // 管理側ページ
  Route::get('/admin/home', 'AdminPageController@index')->name('admin.home');
  Route::get('/admin/deal/{id}', 'AdminPageController@dealdetail')->name('admin.dealdetail');
  Route::get('/admin/user', 'AdminPageController@user')->name('admin.user');
  Route::get('/admin/setonagiuser', 'AdminPageController@setonagiuser')->name('admin.setonagiuser');
  Route::get('/admin/item', 'AdminPageController@item')->name('admin.item');
  Route::get('/admin/sales', 'AdminPageController@sales')->name('admin.sales');
  Route::get('/admin/csv', 'AdminPageController@csv')->name('admin.csv');
  Route::get('/admin/download', 'AdminPageController@download')->name('admin.download');

  // 管理側セトナギユーザーの承認などのポスト
  Route::post('/admin/riyoukyoka', 'AdminPageController@riyoukyoka');
  Route::post('/admin/card_riyoukyoka', 'AdminPageController@card_riyoukyoka');
  Route::post('/admin/riyouteisi', 'AdminPageController@riyouteisi');

  // 取引検索
  Route::get('/admin/search', 'AdminPageController@search');
  Route::post('/admin/search', 'AdminPageController@search');

  // 商品画像アップロード
  Route::get('/admin/imgupload', 'AdminPageController@imgupload')->name('imgupload');
  Route::post('/admin/imgsave', 'AdminPageController@imgsave');

  // ユーザーごとの取引一覧画面
  Route::get('/admin/user/deal/{id}', 'AdminPageController@userdeal')->name('admin.user.deal');

  // カテゴリのおすすめ編集
  Route::get('/admin/recommendcategory', 'AdminPageController@recommendcategory')->name('recommendcategory');
  Route::get('/admin/recommendcategory/{id}', 'AdminPageController@recommendcategorydetail')->name('recommendcategorydetail');
  Route::post('/admin/addrecommendcategory', 'AdminPageController@addrecommendcategory');
  Route::post('/admin/saverecommendcategory', 'AdminPageController@saverecommendcategory');
  Route::post('/admin/removerecommendcategory', 'AdminPageController@removerecommendcategory');

  // 担当のおすすめポスト
  Route::get('/admin/user/recommend/{id}', 'AdminPageController@userrecommend')->name('recommend');
  Route::post('/admin/user/addrecommend', 'AdminPageController@addrecommend');
  Route::post('/admin/user/saverecommend', 'AdminPageController@saverecommend');
  Route::post('/admin/user/removercommend', 'AdminPageController@removercommend');

  // リピートオーダーポスト
  Route::get('/admin/user/repeatorder/{id}', 'AdminPageController@userrepeatorder')->name('repeatorder');
  Route::post('/admin/user/addrepeatorder', 'AdminPageController@addrepeatorder');
  Route::post('/admin/user/saverepeatorder', 'AdminPageController@saverepeatorder');
  Route::post('/admin/user/removerepeatorder', 'AdminPageController@removerepeatorder');
  Route::post('/admin/user/clonerepeatorder', 'AdminPageController@clonerepeatorder');

  // CSVインポート
  Route::post('/admin/userimport', 'AdminPageController@userimport');
  Route::post('/admin/Adminimport', 'AdminPageController@adminimport');
  Route::post('/admin/itemimport', 'AdminPageController@itemimport');
  Route::post('/admin/CategoryItemImport', 'AdminPageController@CategoryItemImport');
  Route::post('/admin/CategoryImport', 'AdminPageController@CategoryImport');
  Route::post('/admin/TagImport', 'AdminPageController@TagImport');
  Route::post('/admin/HolidayImport', 'AdminPageController@HolidayImport');
  Route::post('/admin/StoreImport', 'AdminPageController@StoreImport');
  Route::post('/admin/StoreUserImport', 'AdminPageController@StoreUserImport');
  Route::post('/admin/PriceGroupeImport', 'AdminPageController@PriceGroupeImport');
  Route::post('/admin/PriceImport', 'AdminPageController@PriceImport');
  Route::post('/admin/SpecialPriceImport', 'AdminPageController@SpecialPriceImport');
  Route::post('/admin/SetonagiImport', 'AdminPageController@SetonagiImport');
  Route::post('/admin/SetonagiItemImport', 'AdminPageController@SetonagiItemImport');

  // CSVエクスポート
  Route::post('/admin/export', 'AdminPageController@Export');

  // 割引修正
  Route::post('/admin/discount', 'AdminPageController@discount');

  // カート画面自動更新
  Route::post('/admin/intervalupdatecart', 'AdminPageController@intervalupdatecart');






Route::group(['prefix' => 'admin'], function () {
  Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'AdminAuth\LoginController@login');
  Route::post('/logout', 'AdminAuth\LoginController@logout')->name('logout');

  // Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm')->name('register');
  // Route::post('/register', 'AdminAuth\RegisterController@register');

  Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'user'], function () {
  Route::get('/login', 'UserAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'UserAuth\LoginController@login');
  Route::post('/logout', 'UserAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'UserAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'UserAuth\RegisterController@register');

  Route::post('/password/email', 'UserAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'UserAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'UserAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'UserAuth\ResetPasswordController@showResetForm');
});
