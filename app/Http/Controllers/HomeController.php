<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
	public function index()
	{
		return redirect()->route('app');

		return view('home');
	}
}
