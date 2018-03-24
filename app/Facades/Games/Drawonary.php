<?php

namespace App\Facades\Games;

use Illuminate\Support\Facades\Facade;

class Drawonary extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'drawonary';
	}
}
