<?php

namespace App\Http\Controllers;

use App\Models\Player;

class PlayerController extends Controller
{
    public function getinfos()
    {
        return Player::where('id', 1)->get();
    }
}
