<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    //
    public function vendor()
    {
        return $this->hasOne('App\User', 'id', 'vendor_id');
    }
    public function vendorRetailer()
    {
        return $this->hasOne('App\ArchivedRetailer', 'id', 'vendor_id');
    }
}
