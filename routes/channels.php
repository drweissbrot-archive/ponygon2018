<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
 */

// Broadcast::channel('App.User.{id}', function ($user, $id) {
// 	return (int) $user->id === (int) $id;
// });

Broadcast::channel('game:draw:{id}', function ($user, $id) {
	return true;
});

Broadcast::channel('lobby:{id}', function ($user, $id) {
	$user = request()->header('X-PONYGON-USER');
	$auth = request()->header('X-PONYGON-AUTH');

	if (! Lobby::verifyPlayerIsLobbyMember($id, $user, $auth, false)) {
		return false;
	}

	return [
		'id' => $user,
		'name' => Redis::hget('player:' . $user, 'name'),
	];
});
