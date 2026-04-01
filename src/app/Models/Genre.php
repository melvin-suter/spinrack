<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
     protected $fillable = [
        'tmdbid',
        'name',
    ];


    public function dvds() {
        return $this->belongsToMany(Dvd::class);
    }
}
