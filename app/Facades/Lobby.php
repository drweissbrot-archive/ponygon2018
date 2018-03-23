<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Lobby extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'lobby';
	}
}
