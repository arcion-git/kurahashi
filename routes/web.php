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



  Route::get('/', 'LoginPageController@index')->name('home');
  Route::get('/category/{id}', 'LoginPageController@category');

  Route::get('/confirm', 'LoginPageController@confirm');
  Route::get('/deal', 'LoginPageController@deal')->name('deal');
  Route::get('/user/deal/{id}', 'LoginPageController@dealdetail')->name('dealdetail');

  Route::post('/addcart', 'LoginPageController@addcart');
  Route::post('/removecart', 'LoginPageController@removecart');
  Route::post('/updatecart', 'LoginPageController@updatecart');

  Route::post('/adddeal', 'LoginPageController@adddeal');
  Route::post('/addsuscess', 'LoginPageController@addsuscess');

  Route::get('/cart', 'LoginPageController@cart');
  Route::post('/dealcart', 'LoginPageController@dealcart');
  Route::get('/showdealcart', 'LoginPageController@showdealcart');

// アンケート画面
  Route::get('/user/questionnaire', 'LoginPageController@questionnaire');


  Route::get('/admin/home', 'AdminPageController@index')->name('admin.home');
  Route::get('/admin/deal/{id}', 'AdminPageController@dealdetail')->name('admin.dealdetail');
  Route::get('/admin/user', 'AdminPageController@user')->name('admin.user');
  Route::get('/admin/item', 'AdminPageController@item')->name('admin.item');
  Route::get('/admin/sales', 'AdminPageController@sales')->name('admin.sales');

  Route::get('/admin/csv', 'AdminPageController@csv')->name('admin.csv');

  Route::post('/admin/userimport', 'AdminPageController@userimport');
  Route::post('/admin/itemimport', 'AdminPageController@itemimport');
  Route::post('/admin/CategoryItemImport', 'AdminPageController@CategoryItemImport');
  Route::post('/admin/CategoryImport', 'AdminPageController@CategoryImport');
  Route::post('/admin/TagImport', 'AdminPageController@TagImport');

  Route::get('/admin/download', 'AdminPageController@download')->name('admin.download');

  Route::get('/admin/user/deal/{id}', 'AdminPageController@userdeal')->name('admin.user.deal');

  Route::post('/admin/updatecart', 'AdminPageController@updatecart');
  Route::post('/admin/intervalupdatecart', 'AdminPageController@intervalupdatecart');




Route::group(['prefix' => 'admin'], function () {
  Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'AdminAuth\LoginController@login');
  Route::post('/logout', 'AdminAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'AdminAuth\RegisterController@register');

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
