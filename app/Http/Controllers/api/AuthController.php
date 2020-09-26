<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function logout()
    {
        User::where('id', $this->getAuthenticatedUser()->id)->update(['fcm_token' => null]);
        return $this->success(null, 'Logout successful');
    }
    
    public function sendOtp(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'mobile' => 'required|digits:10']
        );

        if ($validatedData->fails()) {
            return $this->error($validatedData->errors()->first());
        }
        
        $otp = rand(1000, 9999);
        $user = User::where('mobile', $request->mobile)->first();
        $status = '';
        if(!empty($user )){
            User::where('mobile', $request->mobile)->update(['otp' => $otp]);
        }
        else{
            $user = new User();
            $user->mobile = $request->mobile;
            $user->otp = $otp;
            $user->save();
        }
        $error = $this->sendMessage($request->mobile, $otp);
        if ($error == null) {
            return $this->success(['status' => 'success', 'otp' => $otp], "OTP has been sent to your mobile number as SMS.");
        } else {
            return $this->error($error);
        }
    }
    
    public function resendOtp(Request $request){
        
        $mobile = $request->mobile;
        $otp = DB::table('users')->where('mobile', $mobile)->pluck('otp')->toArray();
        
        $error = $this->sendMessage($mobile, $otp[0]);
        if ($error == null) {
            return $this->success(['status' => 'success', 'otp' => $otp], "OTP has been sent to your mobile number as SMS.");
        } else {
            return $this->error($error);
        }
    }

    public function login(Request $request)
    {
        // echo json_encode($request->all()); die;
        $validatedData = Validator::make($request->all(), [
            'mobile' => 'required',
            'otp' => 'required',
        ]);

        // echo json_encode($validatedData->errors()); die;

        if ($validatedData->fails()) {
            return $this->error($validatedData->errors()->first(), 401);
        }

        $data = $this->repository->apiLogin($request, $this->apiGuard());

        if ($request->fcm_token!=null) {
            User::where('mobile', $request->mobile)->update(['fcm_token' => $request->fcm_token]);
        }

        if ($data != null) {
            return $this->respondWithToken($data[0], $data[1]);
        }

        return $this->error('Your OTP is wrong. Please enter correct OTP.', 401);
    }
    
    public function sendMessage($phone, $otp)
    {
        $curl = curl_init();
        $message= "Your OTP for Badhat app is : $otp";
        $message = str_replace(" ", '%20', $message);
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.fast2sms.com/dev/bulk?authorization=MWwq74b8PSYxTVjvUpslDu2iZn5yo1FNh9td6OLRgIrzKaA0ekEAFu8KqmS4aGvd5ZfCVPXw3bJBURnz&message=$message&route=p&numbers=$phone&sender_id=FSTSMS&language=english",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic YmFkaGF0X2IyYjpCYWRoYXRAMjAyMA==",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err; die;
        } else {
            // echo $response;die;
            $rr;
            $x = json_decode(json_encode($response),true);
            if(strpos($x, 'true') !== false){
                $rr= null;            
            }
            else{
                $rr= "Something went wrong";
            }
            return $rr;
        }
    }
}
