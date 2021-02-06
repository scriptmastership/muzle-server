<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Background extends Model
{
    public function games()
    {
        return $this->belongsToMany(Background::class, 'game_background');
    }
}
