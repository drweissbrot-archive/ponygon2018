<?php

namespace App\Logging;

use Monolog\Logger;

class CreateDiscordErrorLogger extends CreateDiscordLogger
{
	protected $level = 'error';

	protected $levelConst = Logger::ERROR;
}
