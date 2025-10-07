<?php

namespace App\Http\Controllers;

use App\Models\Map;
use App\Models\RunHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserController;

class HighscoreController
{
    public function getScoreByMapName(string $map_name)
    {
        $map = Map::query()
            ->where('name', '=', $map_name)
            ->firstOrFail();

        $query = RunHistory::query()
            ->select([
                DB::raw('MIN(id) as id'),
                DB::raw('player_id'),
                DB::raw('MIN(map_id) AS map_id'),
                DB::raw('MIN(time) AS run'),
                DB::raw('DENSE_RANK() OVER (PARTITION BY map_id ORDER BY time ASC) AS rank'),
            ])
            ->with('run_info:id,death_count,client_info,time,created_at') // Too tricky, but works in eloquent, so thx(?)
            ->with('map:id,name,creator,description,type')
            ->with('player:id,login')
            ->where('map_id', '=', $map->id)
            ->orderBy('time')
            ->groupBy('player_id')
            ->get();

        foreach ($query as $entry) {
            $uc = new UserController();
            $user_obj = $uc->getUserInfosByPlayerId($entry["player"]["id"]);

            if (!is_null($user_obj))
                $entry["user"] = $user_obj;
        }

        return $query->makeHidden(['player_id', 'map_id']);
    }

    public function getPlayerScoresByPlayerId(int $player_id, ?string $map_name = null)
    {
        $map = null;
        if (! is_null($map_name)) {
            $map = Map::query()
                ->where('name', '=', $map_name)
                ->firstOrFail();
        }

        $subquery = RunHistory::query()
            ->select([
                '*',
                DB::raw('DENSE_RANK() OVER (PARTITION BY map_id ORDER BY time ASC) AS rank'),
            ]);

        $query = RunHistory::query()->fromSub($subquery, 'sub')
            ->with('map:id,name,creator,description,type')
            ->with('player:id,login')
            ->where('sub.player_id', '=', $player_id);

        if (! is_null($map)) {
            $query = $query->where('sub.map_id', $map->id);
        }

        $query = $query->orderBy('sub.created_at', 'desc')
            ->get();

        return $query->makeHidden(['player_id', 'map_id']);
    }
}
