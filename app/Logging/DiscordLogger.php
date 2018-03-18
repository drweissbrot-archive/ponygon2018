<?php

namespace App\Logging;

use Unirest\Request as Unirest;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class DiscordLogger extends AbstractProcessingHandler
{
	protected $webhookUrl;

	public function __construct($webhookUrl, $levelConst)
	{
		$this->webhookUrl = $webhookUrl;

		parent::__construct($levelConst, true);
	}

	/**
	 * @param array $record
	 */
	protected function write(array $record)
	{
		$body = [
			'username' => config('app.name'),
			'embeds' => [
				[
					'color' => $this->getColor($record['level_name']),
					'description' => $record['message'],
					'title' => $record['level_name'],
					'timestamp' => $record['datetime']->format('c'),
					'footer' => [
						'text' => $record['level_name'],
					],
					'author' => [
						'name' => config('app.name'),
						'url' => config('app.url'),
					],
				],
			],
		];

		Unirest::post($this->webhookUrl . '?wait=true', [], Unirest\Body::json($body));
	}

	protected function getColor($level)
	{
		// see https://github.com/izy521/discord.io/blob/master/docs/colors.md

		switch (strtolower($level)) {
			case 'emergency':
			case 'alert':
				return 15158332; // red

			case 'critical':
				return 15105570; // orange

			case 'error':
				return 12370112; // light gray

			case 'warning':
				return 15844367; // gold

			case 'notice':
			case 'info':
			case 'debug':
			default:
				return 3447003; // blue
		}
	}
}
