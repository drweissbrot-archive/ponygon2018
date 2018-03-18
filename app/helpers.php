<?php

if (! function_exists('active_page')) {
	function active_page($route)
	{
		if (request()->route()->getName() === $route) {
			return '--active';
		}
	}
}
