<?php

namespace App\Providers;

use App\Games\Ponygon\PonygonRedisBroadcaster;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 */
	public function boot(BroadcastManager $manager)
	{
		$manager->extend('ponygon_redis', function (Application $app, $config) {
			return new PonygonRedisBroadcaster($app->make('redis'), $config['connection'] ?? null);
		});

		Broadcast::routes([
			// TODO default middleware group is web -- CSRF tokens?
			'middleware' => 'guest',
		]);

		require base_path('routes/channels.php');
	}
}
