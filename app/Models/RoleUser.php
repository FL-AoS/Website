<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    public function role() {
        return $this->belongsTo(Role::class);
    }
}
