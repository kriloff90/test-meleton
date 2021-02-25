<?php

namespace App\Models;

use Hash;
use DateTime;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function checkPassword(string $password)
    {
        return Hash::check($password, $this->password);
    }

    public function createAuthToken()
    {
        $token = $this->createToken('api');

        return [
            'token_type' => 'Bearer',
            'expires_in' => $token->token->expires_at->getTimestamp() - (new DateTime())->getTimestamp(),
            'access_token' => $token->accessToken,
        ];
    }

    public function converts() : HasMany
    {
        return $this->hasMany(Convert::class);
    }
}
