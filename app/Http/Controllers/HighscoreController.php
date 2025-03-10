<?php

namespace App\Http\Controllers;

use App\Models\Map;
use App\Models\RunHistory;
use Illuminate\Database\Query\JoinClause;
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
                DB::raw('MIN(map_id) AS map_id'),
                DB::raw('MIN(time) AS run'),
                DB::raw("DENSE_RANK() OVER (PARTITION BY map_id ORDER BY time ASC) AS rank")
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
        $subquery = RunHistory::query()
            ->select([
                "*",
                DB::raw("DENSE_RANK() OVER (PARTITION BY map_id ORDER BY time ASC) AS rank"),
            ]);

        $query = RunHistory::query()->fromSub($subquery, "sub")
            ->with("map:id,name,creator,description,type")
            ->with("player:id,login")
            ->where("sub.player_id", "=", $player_id)
            ->orderBy("sub.created_at", "desc")
            ->get();

        return $query->makeHidden(["player_id","map_id"]);
    }
}
