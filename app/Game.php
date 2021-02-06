<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use App\Background;
// use App\Category;

class Game extends Model
{
    public function backgrounds()
    {
        return $this->belongsToMany(Background::class, 'game_background');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'game_category');
    }
}
