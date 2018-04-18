<?php

namespace App\Http\Controllers;

use App\Events\Game\Lobby\LeaderChanged;
use Illuminate\Http\Request;
use Lobby;
use Player;
use Ponygon;
use Redis;

class LobbyController extends Controller
{
	public function create(Request $request)
	{
		$user = $request->user;
		$auth = $request->auth;

		return Lobby::createLobby($user, $auth);
	}

	public function registerAsPlayer(Request $request)
	{
		return Player::register($request->name);
	}

	public function heartbeat($id)
	{
		abort_unless(Redis::exists('lobby:' . $id), 404, 'lobbyDoesntExist');

		$names_in_use = Lobby::getNamesInUse($id);

		return compact('id', 'names_in_use');
	}

	public function join(Request $request, $id)
	{
		$user = $request->user;
		$auth = $request->auth;

		// TODO uncomment
		// Player::authenticate($user, $auth);

		// if (Lobby::verifyPlayerIsLobbyMember($id, $user, $auth, false)) {
		// 	abort(403, 'You are already a member of this lobby.');
		// }

		Lobby::join($id, $user);
	}

	public function status(Request $request, $id)
	{
		// TODO uncomment
		// Lobby::verifyPlayerIsLobbyMember($id, $request->user, $request->auth);

		$lobby = Redis::hgetall('lobby:' . $id);

		return [
			'invite_link' => route('app') . '#/join/' . $id,
			'players' => Lobby::getUsers($id, true),
			'leader' => $lobby['leader'],
			'game' => $lobby['game'],
			'game_id' => $lobby['game_id'],
		];
	}

	public function changeLeader(Request $request, $id)
	{
		$user = $request->user;
		$auth = $request->auth;
		$newLeader = $request->newLeader;

		Lobby::verifyPlayerIsLobbyLeader($id, $user, $auth);

		Redis::hset('lobby:' . $id, 'leader', $newLeader);

		event(new LeaderChanged($id, $newLeader));
	}

	public function startGame(Request $request, $id)
	{
		$user = $request->user;
		$auth = $request->auth;
		$game = $request->game;

		Lobby::verifyPlayerIsLobbyLeader($id, $user, $auth);

		Ponygon::startGame($game, $id);
	}

	public function postChatMessage(Request $request, $id)
	{
		$user = $request->user;
		$auth = $request->auth;
		$message = $request->message;

		if (mb_strlen($message) < 1) {
			return;
		}

		Lobby::verifyPlayerIsLobbyMember($id, $user, $auth);

		return Lobby::sendChatMessage($id, $user, $message);
	}
}
