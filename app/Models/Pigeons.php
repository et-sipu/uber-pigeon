<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pigeons extends Model
{
    use HasFactory;

    public static function getPigeon($name){
        return Self::where('name',$name)->first();
    }
}
