<?php

namespace App\Providers;

use App\Games\TicTacToe\TicTacToe;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 */
	public function boot()
	{
		Blade::component('components.input-group', 'inputgroup');
		Blade::component('components.panel', 'panel');
	}

	/**
	 * Register any application services.
	 */
	public function register()
	{
		$this->app->singleton('ticTacToe', function () {
			return new TicTacToe;
		});
	}
}
