<?php

namespace App\Events\Game\Drawonary;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TurnEnded implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $id;

	public $addedPoints;

	public $scoreboard;

	/**
	 * Create a new event instance.
	 *
	 * @param mixed $id
	 * @param mixed $addedPoints
	 * @param mixed $scoreboard
	 */
	public function __construct($id, $addedPoints, $scoreboard)
	{
		$this->id = $id;
		$this->addedPoints = $addedPoints;
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
