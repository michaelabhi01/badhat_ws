<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    //
	public function getCreatedAtAttribute($value)
    {
        return date(DATE_FORMAT, strtotime($value));
    }
    
    public function vendor(){
        return $this->belongsTo('App\User','vendor_id','id')
        ->select('id','name','business_name','image');
    }
}
