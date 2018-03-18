<?php

namespace App\Http\Controllers\Account;

use Auth;
use App\Http\Controllers\Controller;
use App\Rules\IsCurrentUsersEmail;
use App\Rules\CurrentPassword;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
	public function index()
	{
		return view('account.delete');
	}

	public function delete(Request $request)
	{
		$this->validate($request, [
			'email' => [
				'required', 'email', 'max:255', new IsCurrentUsersEmail,
			],
			'password' => [
				'required', new CurrentPassword,
			]
		]);

		Auth::user()->delete();

		return redirect()->route('home')->withSuccess('Your account has been deleted. We\'re sad to see you go.');
	}
}
