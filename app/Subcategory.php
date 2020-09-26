<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    //
	public function getCreatedAtAttribute($value)
    {
        return date(DATE_FORMAT, strtotime($value));
    }
    
    public function verticals()
    {
        return $this->hasMany('App\Vertical', 'subcategory_id', 'id');
    }
}
