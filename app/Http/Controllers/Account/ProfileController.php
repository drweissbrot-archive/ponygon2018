<?php

namespace App\Http\Controllers\Account;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
	public function index()
	{
		$user = Auth::user();

		return view('account.profile', compact('user'));
	}

	public function update(Request $request)
	{
		$data = $this->validate($request, [
			'name' => 'string|required|max:255',
			'address_as' => 'string|nullable|max:255',
			'email' => 'string|required|email|max:255|unique:users,email,' . $request->user()->id,
		]);

		$request->user()->update($data);

		return back()->withSuccess('Your account details were successfully updated!');
	}
}
