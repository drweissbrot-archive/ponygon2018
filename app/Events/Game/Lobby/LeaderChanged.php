<?php

namespace App\Events\Game\Lobby;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaderChanged implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $lobbyId;

	public $user;

	/**
	 * Create a new event instance.
	 *
	 * @param mixed $lobbyId
	 * @param mixed $user
	 */
	public function __construct($lobbyId, $user)
	{
		$this->lobbyId = $lobbyId;
		$this->user = $user;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return array|\Illuminate\Broadcasting\Channel
	 */
	public function broadcastOn()
	{
		return new Channel('lobby:' . $this->lobbyId);
	}
}
