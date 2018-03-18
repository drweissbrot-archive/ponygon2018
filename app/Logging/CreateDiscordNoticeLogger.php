<?php

namespace App\Logging;

use Monolog\Logger;

class CreateDiscordNoticeLogger extends CreateDiscordLogger
{
	protected $level = 'notice';

	protected $levelConst = Logger::NOTICE;
}
