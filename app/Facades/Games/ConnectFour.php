<?php

namespace App\Facades\Games;

use Illuminate\Support\Facades\Facade;

class ConnectFour extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'connectfour';
	}
}
