<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfirmationToken extends Model
{
	protected $dates = [
		'expires_at',
	];

	public static function boot()
	{
		static::creating(function ($token) {
			optional($token->user->confirmationToken)->delete();
		});
	}

	public function getRouteKeyName()
	{
		return 'token';
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function hasExpired()
	{
		return $this->freshTimestamp()->gt($this->expires_at);
	}
}
