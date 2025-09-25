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

    public function checkpoint_history()
    {
        return $this->hasMany(CheckpointHistory::class, 'run_id', 'id');
    }

    // Too tricky, but works in eloquent, so thx(?)
    public function run_info()
    {
        return $this->belongsTo(RunHistory::class, 'id', 'id');
    }
}
