<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\ConfirmationToken;
use App\Events\Auth\UserRequestedActivationEmail;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivationController extends Controller
{
	protected $redirectTo = '/dashboard';

	public function __construct()
	{
		$this->middleware('checksExpiredConfirmationToken')->only('activate');
	}

	public function activate(Request $request, $email, ConfirmationToken $token)
	{
		$user = $token->user;

		abort_unless($email === $user->email, 404);

		$user->activated = true;
		$user->save();

		$token->delete();

		Auth::loginUsingId($user->id);

		return redirect()->intended($this->redirectPath())->withSuccess('Your account was successfully activated. Have fun!');
	}

	public function resendForm()
	{
		return view('auth.activation.resend');
	}

	public function resend(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email',
		]);

		$user = User::where('email', $request->email)->first();

		if ($user !== null && ! $user->hasActivated()) {
			event(new UserRequestedActivationEmail($user));
		}

		return redirect()->route('login')->withSuccess('An activation email has been resend. Please check your inbox.');
	}

	protected function redirectPath()
	{
		return $this->redirectTo;
	}
}
