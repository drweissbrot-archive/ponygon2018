<?php

namespace App\Mail\Auth;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivationEmail extends Mailable
{
	use Queueable, SerializesModels;

	public $user;
	public $token;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct(User $user, $token)
	{
		$this->user = $user;
		$this->token = $token;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->subject('Welcome to ' . config('app.name') . '! Confirm your email address')
			->markdown('mail.auth.activation');
	}
}
