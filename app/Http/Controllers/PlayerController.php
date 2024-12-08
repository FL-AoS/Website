<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{
    public function getinfos() {
        return Player::where("id", 1)->get();
    }
}
