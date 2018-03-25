<?php

namespace App\Events\Game\Drawonary;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SelectingWord implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $id;

	public $user;

	public $selectionEndsAt;

	/**
	 * Create a new event instance.
	 *
	 * @param mixed $id
	 * @param mixed $user
	 * @param mixed $selectionEndsAt
	 */
	public function __construct($id, $user, $selectionEndsAt)
	{
		$this->id = $id;
		$this->user = $user;
		$this->selectionEndsAt = $selectionEndsAt;
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
