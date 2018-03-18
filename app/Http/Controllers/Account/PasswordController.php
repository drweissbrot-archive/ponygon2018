<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Mail\Account\PasswordUpdated;
use App\Rules\CurrentPassword;
use Mail;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
	public function index()
	{
		return view('account.password');
	}

	public function update(Request $request)
	{
		$this->validate($request, [
			'password' => ['required', new CurrentPassword],
			'new' => 'required|string|min:6|confirmed',
		]);

		$request->user()->update([
			'password' => bcrypt($request->new),
		]);

		Mail::to($request->user())->send(new PasswordUpdated($request->user()));

		return back();
	}
}
