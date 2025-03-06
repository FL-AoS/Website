<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RunHistory;

class RunController
{
    public function getRunInfo(int $run_id) {
        return RunHistory::where("id","=",$run_id)
        ->with("player:id,login")
        ->with("map:id,name,creator,description,type")
        ->with("checkpoint_history:run_id,checkpoint,time")
        ->firstOrFail();
    }
}
