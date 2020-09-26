<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vertical extends Model
{
    //
    public function getCreatedAtAttribute($value)
    {
        return date(DATE_FORMAT, strtotime($value));
    }
}
