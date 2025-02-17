<?php

namespace App\Http\Controllers;

use App\Models\Map;
use App\Models\RunHistory;
use Illuminate\Support\Facades\DB;

class HighscoreController
{
    public function getScoreByMapName(string $map_name)
    {
        $map = Map::query()
            ->where('name', '=', $map_name)
            ->firstOrFail();

        $query = RunHistory::query()
            ->select([
                DB::raw('player_id'),
                DB::raw('MIN(map_id) as map_id'),
                DB::raw('MIN(time) as run'),
                DB::raw("dense_rank() OVER (partition by map_id order by time asc) as rank")
            ])
            ->with("map:id,name,creator,description,type")
            ->with("player:id,login")
            ->where('map_id', '=', $map->id)
            ->orderBy('time')
            ->groupBy('player_id')
            ->get();

        return $query->makeHidden(["player_id","map_id"]);
    }

    public function getPlayerScoresByPlayerId(int $player_id) {
        $query = RunHistory::query()
            ->where("player_id", "=", $player_id)
            ->with("map:id,name,creator,description,type")
            ->orderBy("created_at", "desc")
            ->get();

        return $query->makeHidden(["map_id"]);
    }
}
