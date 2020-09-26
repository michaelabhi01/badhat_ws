<?php

namespace App;

use Exception;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'mobile', 'otp',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'otp', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getCreatedAtAttribute($value)
    {
        return date(DATE_FORMAT, strtotime($value));
    }

    /**
     * The attributes are appended at runtime.
     *
     * @var array
     */
    protected $appends = ['room_id'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    public function isSuperAdmin()
    {
        return $this->id == 1;
    }

    public function products()
    {
        return $this->hasMany('App\Product', 'user_id', 'id');
    }

    public function getImageAttribute($value)
    {
        return $value == null ? null : asset(Storage::url($value));
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

    public function getBusinessCategoryAttribute($value)
    {
        $cat = explode(',', $value);
        $cat1 = array();
        $category = "";
        foreach ($cat as $item) {
            array_push($cat1, Category::where('id', $item)->first()->name);
        }
        return implode(',', $cat1);
    }

}
