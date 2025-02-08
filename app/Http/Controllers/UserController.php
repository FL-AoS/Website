<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController
{
    public function getUserInfosById(int $id) {
        $q = User::query()->where("id", "=", $id)->firstOrFail()
            ->select("id", "player_id", "discord_user_id")
            ->with("player:id,login")
            ->with("discord_user:id,username,global_name,discord_id,avatar_hash")
            ->get();

        return $q->makeHidden(["player_id", "discord_user_id"]);
    }
}
