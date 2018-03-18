<?php

namespace App\Logging;

use DiscordHandler\DiscordHandler;
use Monolog\Logger;

abstract class CreateDiscordLogger
{
	protected $level;
	protected $levelConst;

	/**
	 * Create a custom Monolog instance.
	 *
	 * @param  array  $config
	 * @return \Monolog\Logger
	 */
	public function __invoke(array $config)
	{
		$logger = new Logger('discord_' . $this->level);

		$logger->pushHandler(new DiscordLogger(
			config('logging.discord.' . $this->level),
			$this->level,
			$this->levelConst
		));

		return $logger;
	}
}
