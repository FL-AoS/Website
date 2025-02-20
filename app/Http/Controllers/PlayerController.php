<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use Illuminate\Support\Facades\Auth;

class PlayerController
{
    public function getinfos()
    {
        return Player::where('id', 1)->get();
    }

    public function update_infos(Request $request) {
        $validated = $request->validate([
            "login" => ["required", 'regex:/^[\w\-_|<>.@!#:]+$/',  "max:255"], // allow special symbols for clan names or whatever
            "password" => ["required", "max:255", "min:5"]
        ]);

        $p_id = Auth::user()->player_id;
        if(is_null($p_id)) {
            $request->validate([
                "login" => "unique:players"
            ]);

            $player_m = new Player();
            $player_m->login = $validated["login"];
            $player_m->password = $validated["password"];

            $player_m->save();

            Auth::user()->player_id = $player_m->id;
            Auth::user()->save();
        } else {
            if(Auth::user()->player->login != $request->input("login")) {
                $request->validate([
                    "login" => "unique:players"
                ]);
            }

            Auth::user()->player->login = $validated["login"];
            Auth::user()->player->password = $validated["password"];
            Auth::user()->player->save();
        }


        error_log($validated["login"]);
        error_log($request->input("password"));

        return redirect("/profile/".Auth::user()->id);
    }
}
