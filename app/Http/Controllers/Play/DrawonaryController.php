<?php

namespace App\Http\Controllers\Play;

use App\Facades\Games\Drawonary;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lobby;
use Redis;

class DrawonaryController extends Controller
{
	public function status(Request $request, $id)
	{
		$lobby = Drawonary::getLobbyFromGameId($id);
		$user = $request->user;
		$auth = $request->auth;

		Lobby::verifyPlayerIsLobbyMember($lobby, $user, $auth);

		return Redis::hgetall('game:' . $id);
	}
}
