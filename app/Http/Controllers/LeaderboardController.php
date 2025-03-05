<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Map;

class LeaderboardController
{
    public function handleLeaderboard() {
        return view("leaderboard");
    }

    public function getLeaderboardCategories() {
        $categories = [
            "Parkour" => [
                "Maps" => []
            ]
        ];

        foreach (Map::all() as $map_obj) {
            array_push($categories["Parkour"]["Maps"], $map_obj["name"]);
        }

        return $categories;
    }
}
