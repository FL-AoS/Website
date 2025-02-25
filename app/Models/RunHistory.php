<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RunHistory extends Model
{
    protected $table = 'prk_run_history';

    public function map()
    {
        return $this->belongsTo(Map::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
