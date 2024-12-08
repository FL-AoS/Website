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

        return RunHistory::query()
            ->select([
                DB::raw('player_id'),
                DB::raw('MIN(map_id) as map_id'),
                DB::raw('MIN(time) as run'),
            ])
            ->where('map_id', '=', $map->id)
            ->orderBy('time')
            ->groupBy('player_id')
            ->get();
    }
}
