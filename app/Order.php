<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function getCreatedAtAttribute($value)
    {
        return date(DATE_FORMAT, strtotime($value));
    }
    
    public function items()
    {
        return $this->hasMany('App\OrderItem', 'order_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\User', 'vendor_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function products()
    {
        return $this->hasManyThrough('App\Product', 'App\OrderItem','product_id','id');
    }
}
