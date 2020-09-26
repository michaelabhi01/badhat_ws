<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

Class ArchivedRetailer extends Model{
    
    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }
    public function subcategory()
    {
        return $this->hasOne('App\Subcategory', 'id', 'subcategory_id');
    }
    public function vertical()
    {
        return $this->hasOne('App\Vertical', 'id', 'vertical_id');
    }
    public function products()
    {
        return $this->hasMany('App\Product', 'user_id', 'id');
    }
}