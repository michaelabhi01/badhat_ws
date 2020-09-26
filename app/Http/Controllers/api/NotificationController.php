<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $user_id = $this->getAuthenticatedUser()->id;
        $data = Notification::where('user_id', $user_id)
            ->latest()
            ->get();

        return $this->success($data, "Notifications");

    }
    
    public function markRead(){
        $user_id = $this->getAuthenticatedUser()->id;
        Notification::where('user_id',$user_id)->update(['is_read'=>1]);
        
        return $this->success([],"Success");
    }
}
