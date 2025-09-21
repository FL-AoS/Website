<?php

namespace App\Http\Controllers;

use App\Models\CheckpointHistory;
use App\Models\Map;
use App\Models\Player;
use App\Models\RunHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class GameServerController
{
    public function validate_login(Request $request)
    {
        $data = $request->json()->all();

        $validated = Validator::make($data, [
            'login' => ['required', 'regex:/^[\w\-_|<>.@!#:]+$/',  'max:255'], // allow special symbols for clan names or whatever
            'password' => ['required', 'max:255', 'min:5'],
            'ip' => ['required', 'ipv4'],
        ]);
        $validated = $validated->validated();

        $player = Player::where('login', $validated['login'])->First();

        if (is_null($player)) {
            return 401;
        }

        if (! Hash::check($validated['password'], $player->password)) {
            return 401;
        }

        $player->last_ip = $validated['ip'];
        $player->save();

        return $player->toJson();
    }

    /**
     * {
     * mode: <>,
     * player_id: <>,
     * map_id: <>,
     * demo_name: <>,
     * client_info: <>,
     * time: <>,
     * death_count: <>,
     * checkpoints: [
     *   {checkpoint_number: <>, time: <>},
     *   {checkpoint_number: <>, time: <>},
     *   ...
     * ]
     * }
     */
    public function validate_highscore(Request $request)
    {
        $data = $request->json()->all();

        // for now we only support parkour for highscores
        if ($data['mode'] != 'parkour') {
            return 404;
        }

        $player = Player::where('id', $data['player_id'])->First();
        if (is_null($player)) {
            return 401;
        }

        $map = Map::where('id', $data['map_id'])->First();
        if (is_null($map)) {
            return 404;
        }

        $run_h = new RunHistory;
        $run_h->player_id = $data['player_id'];
        $run_h->map_id = $data['map_id'];
        $run_h->demo_name = $data['demo_name'];
        $run_h->client_info = $data['client_info'];
        $run_h->time = $data['time'];
        $run_h->death_count = $data['death_count'];
        $run_h->save();

        foreach ($data['checkpoints'] as $obj) {
            $cp_h = new CheckpointHistory;
            $cp_h->run_id = $run_h->id;
            $cp_h->checkpoint = $obj['checkpoint_number'];
            $cp_h->time = $obj['time'];
            $cp_h->save();
        }

        return 201;
    }
}
