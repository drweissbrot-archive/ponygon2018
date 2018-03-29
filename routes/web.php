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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::view('/app', 'app')->name('app');

Route::group([
	'middleware' => 'guest',
], function () {
	Route::get('/activation/resend', 'Auth\ActivationController@resendForm')->name('activation.resend');
	Route::post('/activation/resend', 'Auth\ActivationController@resend');

	Route::get('/activate/{email}/{confirmation_token}', 'Auth\ActivationController@activate')->name('activate');
});

Route::group([
	'middleware' => 'auth',
], function () {
	Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

	// Account Settings
	Route::group([
		'prefix' => 'account',
		'namespace' => 'Account',
		'as' => 'account.',
	], function () {
		Route::get('/', 'AccountController@index')->name('index');

		// Profile
		Route::get('/profile', 'ProfileController@index')->name('profile');
		Route::post('/profile', 'ProfileController@update');

		// Password
		Route::get('/password', 'PasswordController@index')->name('password');
		Route::post('/password', 'PasswordController@update');

		// Delete Account
		Route::get('/delete', 'DeleteController@index')->name('delete');
		Route::post('/delete', 'DeleteController@delete');
	});
});

Route::group([
	'prefix' => 'meta',
	'namespace' => 'Meta',
	'as' => 'meta.',
], function () {
	Route::view('/terms-and-conditions', 'meta.tos')->name('tos');
});

Route::group([
	'prefix' => 'lobby',
], function () {
	Route::post('/register', 'LobbyController@registerAsPlayer');

	Route::get('/heartbeat/{id}', 'LobbyController@heartbeat');

	Route::post('/create', 'LobbyController@create');

	Route::post('/join/{id}', 'LobbyController@join');

	Route::post('/status/{id}', 'LobbyController@status');

	Route::post('/change-leader/{id}', 'LobbyController@changeLeader');

	Route::post('/start/{id}', 'LobbyController@startGame');

	Route::post('/chat/{id}', 'LobbyController@postChatMessage');
});

Route::group([
	'prefix' => 'play',
	'namespace' => 'Play',
	'as' => 'play.',
], function () {
	Route::group([
		'prefix' => 'tic-tac-toe',
		'as' => 'tic-tac-toe.',
	], function () {
		Route::get('/', 'TicTacToeController@index')->name('index');

		Route::get('/start', 'TicTacToeController@startGame')->name('start');

		Route::post('/start-new/{id}', 'TicTacToeController@startNewGame')->name('start-new');

		Route::get('/register/{id}/{player}', 'TicTacToeController@registerForGame')->name('register');

		Route::post('/move/{id}', 'TicTacToeController@makeMove')->name('move');
	});

	Route::group([
		'prefix' => 'draw',
		'as' => 'draw.',
	], function () {
		Route::post('/status/{id}', 'DrawonaryController@status')->name('status');

		Route::post('/words/{id}', 'DrawonaryController@getWords')->name('words');

		Route::post('/select/{id}', 'DrawonaryController@selectWord')->name('select');

		Route::post('/get-word/{id}', 'DrawonaryController@getWord');
	});
});
