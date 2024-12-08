<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Map;
use App\Models\RunHistory;
use Illuminate\Database\Query\JoinClause;

class HighscoreController
{
    function getScoreByMapName(string $map_name) {
        $map_id = Map::where("name", $map_name)->get("id");

        if (!count($map_id))
            return [];

        $map_id = $map_id[0]->id;
        $subHistory = RunHistory::select("player_id", "map_id", \DB::raw("min(time) as ts"))->where("map_id", $map_id)->groupBy("player_id")->groupBy("map_id");

        return RunHistory::select(\DB::raw("run.player_id, run.map_id, gh.ts as run"))->from("run_history as run")->where("run.map_id", $map_id)
               ->join("players as pl", "run.player_id", "=", "pl.id")
               ->joinSub($subHistory, "gh", function (JoinClause $join) {
                    $join->on("run.time", "=", "gh.ts")
                    ->on("run.player_id", "=", "gh.player_id")
                    ->on("run.map_id", "=", "gh.map_id");
               })->orderBy("time")->get();
    }
}
