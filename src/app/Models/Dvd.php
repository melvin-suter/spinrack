<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dvd extends Model
{
    protected $fillable = [
        'tmdbid',
        'title',
        'poster_path',
        'backdrop_path',
        'overview',
        'release',
        'amount',
        'season',
        'disc_type',
        'media_type',
        'series_min',
        'series_max',
        'collection_id',
        'collection_title',
    ];
}
