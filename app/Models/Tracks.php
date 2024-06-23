<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracks extends Model
{
    protected  $fillable = [
        'track','artist_id','name','image','cdn'
    ];
}
