<?php

namespace App\Tokens;

use App\ConfirmationToken;

trait HasConfirmationTokens
{
	public function generateConfirmationToken()
	{
		$token = new ConfirmationToken;
		$token->user_id = $this->id;
		$token->token = str_random(255);
		$token->expires_at = $this->getConfirmationTokenExpiry();
		$token->save();

		return $token->token;
	}

	public function confirmationToken()
	{
		return $this->hasOne(ConfirmationToken::class);
	}

	protected function getConfirmationTokenExpiry()
	{
		return $this->freshTimestamp()->addMinutes(30);
	}
}
