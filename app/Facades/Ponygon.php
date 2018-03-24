<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Ponygon extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'ponygon';
	}
}
