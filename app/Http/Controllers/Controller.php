<?php

namespace App\Http\Controllers;

use App\Category;
use App\Service\RepositoryServiceContract;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $repository;

    public function __construct(RepositoryServiceContract $repository)
    {
        $this->repository = $repository;

    }

    protected function success($data, $message)
    {
        return response()->json(['data' => $data, 'message' => $message], 200,[], JSON_NUMERIC_CHECK);
    }

    protected function error($message, $error_code = 400)
    {
        return response()->json(['message' => $message], $error_code,[], JSON_NUMERIC_CHECK);
    }

    protected function respondWithToken($token, $user)
    {
        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user,
            'expires_in' => $this->apiGuard()->factory()->getTTL() * 60,
        ], 'Success');
    }
    public function apiGuard()
    {
        return Auth::guard('api');
    }

    public function getAuthenticatedUser()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    public function getUserCategoryId()
    {
        return Category::where('name', $this->getAuthenticatedUser()->business_category)->first()->id;
    }

    protected function sendPush($title,$message, $to)
    {
        if($to==null || empty($to)) return;
        
        $url = 'https://fcm.googleapis.com/fcm/send';
        $msg = array(
            'to' => $to,
            'notification' => array('title'=>$title, 'body'=>$message),
            'data' => array('click_action' => 'FLUTTER_NOTIFICATION_CLICK'),
        );
        // prx($message); die;

        $headers = array(
            'Authorization: key=AAAASSTVRGc:APA91bErVGhXzSdT8zDeNAVV33fCYKdXq8EZ-cPdtYGRee1RS4v4Lb_Iika6TmwCz-A768RALK0Bc3GqLWLAyu-CKqTGM7Nj9TokZE2pZjrMT-pbs3G8tY_pNQj-ALrOaW7eP7tAHmHR',
            'Content-Type: application/json',
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($msg));

        // Execute post
        $result = curl_exec($ch);
        //die($result);
        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        return $result;
    }
}
