<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{	
	public function getCreatedAtAttribute($value)
    {
        return date(DATE_FORMAT, strtotime($value));
    }
    
    public function product()
    {
        return $this->belongsTo('App\Product')
            ->select('id', 'name', 'image', 'user_id');
    }

}
