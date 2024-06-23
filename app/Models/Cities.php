<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $fillable = [
        'code','genre'
    ];
    public function genres(){
        return $this->belongsTo('App\Genre','genre');
    }
}
