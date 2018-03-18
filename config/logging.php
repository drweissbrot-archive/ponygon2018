<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => [
				'single', 'discord_notice', 'discord_error',
			],
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 7,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

		'discord_debug' => [
			'driver' => 'custom',
			'via' => App\Logging\CreateDiscordDebugLogger::class,
			'level' => 'debug',
		],

		'discord_info' => [
			'driver' => 'custom',
			'via' => App\Logging\CreateDiscordInfoLogger::class,
			'level' => 'info',
		],

		'discord_notice' => [
			'driver' => 'custom',
			'via' => App\Logging\CreateDiscordNoticeLogger::class,
			'level' => 'notice',
		],

		'discord_warning' => [
			'driver' => 'custom',
			'via' => App\Logging\CreateDiscordWarningLogger::class,
			'level' => 'warning',
		],

		'discord_error' => [
			'driver' => 'custom',
			'via' => App\Logging\CreateDiscordErrorLogger::class,
			'level' => 'error',
		],

		'discord_critical' => [
			'driver' => 'custom',
			'via' => App\Logging\CreateDiscordCriticalLogger::class,
			'level' => 'critical',
		],

		'discord_alert' => [
			'driver' => 'custom',
			'via' => App\Logging\CreateDiscordAlertLogger::class,
			'level' => 'alert',
		],

		'discord_emergency' => [
			'driver' => 'custom',
			'via' => App\Logging\CreateDiscordEmergencyLogger::class,
			'level' => 'emergency',
		],
    ],

	'discord' => [
		'debug' => env('DISCORD_WEBHOOK_DEBUG'),
		'info' => env('DISCORD_WEBHOOK_INFO'),
		'notice' => env('DISCORD_WEBHOOK_NOTICE'),
		'warning' => env('DISCORD_WEBHOOK_WARNING'),
		'error' => env('DISCORD_WEBHOOK_ERROR'),
		'critical' => env('DISCORD_WEBHOOK_CRITICAL'),
		'alert' => env('DISCORD_WEBHOOK_ALERT'),
		'emergency' => env('DISCORD_WEBHOOK_EMERGENCY'),
	],
];
