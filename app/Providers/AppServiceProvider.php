<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 */
	public function boot()
	{
		define('ONE_DAY', 86400);

		Blade::component('components.input-group', 'inputgroup');
		Blade::component('components.panel', 'panel');

		Schema::defaultStringLength(191);
	}

	/**
	 * Register any application services.
	 */
	public function register()
	{
		$facades = [
			'ponygon' => \App\Games\Ponygon::class,
			'lobby' => \App\Games\Lobby\Lobby::class,
			'player' => \App\Games\Lobby\Player::class,
			'ticTacToe' => \App\Games\TicTacToe\TicTacToe::class,
			'drawonary' => \App\Games\Drawonary\Drawonary::class,
		];

		foreach ($facades as $facade => $class) {
			$this->app->singleton($facade, function () use ($class) {
				return new $class;
			});
		}
	}
}
