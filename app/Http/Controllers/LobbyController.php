<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Lobby;
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
		return Lobby::registerPlayer($request->username);
	}

	public function heartbeat($id)
	{
		abort_unless(Redis::exists('lobby:' . $id), 404, 'lobbyDoesntExist');

		$names_in_use = Lobby::getUsernamesInUse($id);

		return compact('id', 'names_in_use');
	}

	public function join($id)
	{
		//
	}
}
