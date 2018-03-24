<?php

namespace App\Events\Game\Lobby;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameStarted implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $lobbyId;

	public $id;

	public $game;

	/**
	 * Create a new event instance.
	 *
	 * @param mixed $lobbyId
	 * @param mixed $id
	 * @param mixed $game
	 */
	public function __construct($lobbyId, $id, $game)
	{
		$this->lobbyId = $lobbyId;
		$this->id = $id;
		$this->game = $game;
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
