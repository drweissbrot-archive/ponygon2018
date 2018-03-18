<?php

namespace App;

use App\Tokens\HasConfirmationTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasConfirmationTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'address_as',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	public function addressAs()
	{
		return $this->address_as ?? $this->name;
	}

	public function hasActivated()
	{
		return $this->activated;
	}
}
