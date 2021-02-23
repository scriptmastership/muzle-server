<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function games()
    {
        return $this->belongsToMany(Category::class, 'game_category');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
