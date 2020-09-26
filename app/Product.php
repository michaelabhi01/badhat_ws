<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{

    protected $appends = ["can_delete"];

    public function getImageAttribute($value)
    {
        return asset(Storage::url($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date(DATE_FORMAT, strtotime($value));
    }

    /**
     * Check if a product can be deleted by the owner or not
     * if the product is not in cart or an already placed order.
     * @return boolean
     */
    public function getCanDeleteAttribute()
    {
        return (CartItem::where('product_id', $this->id)->get()->count() == 0
            && OrderItem::where('product_id', $this->id)->get()->count() == 0);
    }

    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }
    public function subcategory()
    {
        return $this->hasOne('App\Subcategory', 'id', 'sub_category_id');
    }
    public function vertical()
    {
        return $this->hasOne('App\Vertical', 'id', 'vertical_id');
    }

    public function vendor()
    {
        return $this->hasOne('App\User', 'id', 'user_id')->select('id', 'name', 'business_name');
    }

}
