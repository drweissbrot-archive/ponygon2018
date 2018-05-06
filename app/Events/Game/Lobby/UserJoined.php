<?php

namespace App\Events\Game\Lobby;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserJoined implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $lobbyId;

	public $name;

	public $id;

	/**
	 * Create a new event instance.
	 *
	 * @param mixed $lobbyId
	 * @param mixed $name
	 * @param mixed $id
	 */
	public function __construct($lobbyId, $name, $id)
	{
		$this->lobbyId = $lobbyId;
		$this->name = $name;
		$this->id = $id;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return array|\Illuminate\Broadcasting\Channel
	 */
	public function broadcastOn()
	{
		return new PresenceChannel('lobby:' . $this->lobbyId);
	}
}
