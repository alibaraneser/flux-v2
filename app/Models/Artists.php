<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artists extends Model
{
    protected $fillable = [
      'artist_id', 'name', 'link'
    ];

    public function color(){
        $result = $this->id % 4;
        switch ($result){
            case 0:
                return "red";
            case 1:
                return "orange";
            case 2:
                return "blue";
            case 3:
                return "green";
            default:
                return "blue";
        }
    }
}
