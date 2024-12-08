<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RunHistory extends Model
{
    protected $table = 'run_history';

    public function map()
    {
        return $this->belongsTo('Map');
    }

    public function player()
    {
        return $this->belongsTo('Player');
    }
}
