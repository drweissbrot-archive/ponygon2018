<?php

namespace App\Logging;

use Monolog\Logger;

class CreateDiscordDebugLogger extends CreateDiscordLogger
{
	protected $level = 'debug';

	protected $levelConst = Logger::DEBUG;
}
