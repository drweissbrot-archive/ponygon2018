<?php

namespace App\Events\Game\Drawonary;

use App\Games\Drawonary\Scoreboard;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Redis;

class GameEnded implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $id;

	public $scoreboard;

	/**
	 * Create a new event instance.
	 *
	 * @param mixed $id
	 * @param mixed $scoreboard
	 */
	public function __construct($id)
	{
		$this->id = $id;

		$scoreboard = Redis::hget('game:' . $id, 'scoreboard');
		$scoreboard = (new Scoreboard)->fromJson($scoreboard)
			->updatePlacements()
			->toJson();

		$this->scoreboard = $scoreboard;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return array|\Illuminate\Broadcasting\Channel
	 */
	public function broadcastOn()
	{
		return new Channel('game:' . $this->id);
	}
}
