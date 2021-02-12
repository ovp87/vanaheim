<?php declare(strict_types=1);

namespace Vanaheim\Core\Models;

use App\Models\User as BaseUser;
use Laravel\Sanctum\HasApiTokens;

class User extends BaseUser
{
    use HasApiTokens;

    protected $hidden = [
        'password',
        'remember_token',
        'id',
        'email_verified_at'
    ];
}
