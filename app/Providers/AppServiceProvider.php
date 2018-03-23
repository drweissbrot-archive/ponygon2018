<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
	}

	/**
	 * Register any application services.
	 */
	public function register()
	{
		$facades = [
			'ticTacToe' => \App\Games\TicTacToe\TicTacToe::class,
			'lobby' => \App\Games\Lobby\Lobby::class,
			'player' => \App\Games\Lobby\Player::class,
		];

		foreach ($facades as $facade => $class) {
			$this->app->singleton($facade, function () use ($class) {
				return new $class;
			});
		}
	}
}
