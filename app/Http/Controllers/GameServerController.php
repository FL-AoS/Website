<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class GameServerController
{
    public function validate_login(Request $request) {
        $data = $request->json()->all();

        $validated = Validator::make($data, [
            'login' => ['required', 'regex:/^[\w\-_|<>.@!#:]+$/',  'max:255'], // allow special symbols for clan names or whatever
            'password' => ['required', 'max:255', 'min:5'],
            'ip' => ['required', 'ipv4']
        ]);
        $validated = $validated->validated();

        $player = Player::where("login", $validated["login"])->First();

        if (is_null($player))
            return 401;

        if (!Hash::check($validated["password"], $player->password))
            return 401;

        $player->last_ip = $validated["ip"];
        $player->save();

        return $player->toJson();
    }
}
