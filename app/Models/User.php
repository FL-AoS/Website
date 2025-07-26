<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public function discord_user()
    {
        return $this->hasOne(DiscordUser::class, 'id', 'discord_user_id');
    }

    public function player()
    {
        return $this->hasOne(Player::class, 'id', 'player_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }
}
