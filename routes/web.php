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

Route::group(['middleware' => 'locale'], function () {
  
    if (Auth::check()){
    Route::get('/', 'HomeController@welcome');
} 
else {
    Route::get('/', 'HomeController@index');
}
   Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/hyanalt/{id}', ['as' => 'home.hyanalt', 'uses' => 'HomeController@hyanalt']);
Route::post('/search', 'HomeController@search')->name('search');
Route::get('/search', 'HomeController@search')->name('search');
Route::get('/freightfill/{id?}',function($id = 0){	
				$dt=App\Freight::where('bid','=',$id)->get();
				
				return $dt;
			});
Route::get('/billfill/{id?}',function($id = 0){	
				$dt=App\Bill::where('bid','=',$id)->get();
				
				return $dt;
			});
Route::get('/transportersfill/{id?}',function($id = 0){	
				$dt=App\Transporters::where('bid','=',$id)->get();
				return $dt;
			});
Route::get('/wagonsfill/{id?}',function($id = 0){	
				$dt=App\Wagon::where('bid','=',$id)->get();
				return $dt;
			});
Route::get('/zuuchfill/{id?}',function($id = 0){	
				$dt=App\Zuuch::where('bid','=',$id)->get();
				return $dt;
			});
Route::get('setlocale/{locale}',function($locale){	
			Session::put('locale', $locale);
			return redirect()->route('home');
			});
});
