<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

Class ArchivedRetailer extends Model{
    
    protected $appends = ['room_id'];
    
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
    
    public function getRoomIdAttribute()
    {
        try {
            $user_id = JWTAuth::parseToken()->authenticate()->id;
            $room_name1 = $this->id . "_" . $user_id;
            $room_name2 = $user_id . "_" . $this->id;

            $room = ChatRoom::where('name', $room_name1)
                ->orWhere('name', $room_name2)
                ->first();
            if ($room) {
                return $room->id;
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }

    }
}