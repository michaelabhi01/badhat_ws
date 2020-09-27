<?php

namespace App\Http\Controllers\api;

use App\Favorite;
use App\Http\Controllers\Controller;
use App\User;
use App\Notification;
use App\Order;
use App\CartItem;
use App\ChatRoom;
use App\ArchivedRetailer;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use File;
use GuzzleHttp\Client;
use Tymon\JWTAuth\Http\Parser\QueryString;

class UserController extends Controller
{

    public function createUser(Request $request)
    {
        try{
            
            $validatedData = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'business_name' => 'required',
                'mobile' => 'required|digits:10',
//                'password' => 'required|min:4',
                'state' => 'required',
                'district' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'business_type' => 'required',
                'business_category' => 'required',
                'pincode' => 'required|digits:6',
            ]);
    
            if ($validatedData->fails()) {
                return $this->error($validatedData->errors()->first(), 401);
            }
    
            $user = new User();
            $user->name = $request->name;
            $user->business_name = $request->business_name;
            $user->mobile = $request->mobile;
//            $user->password = Hash::make($request->password);
            $user->state = $request->state;
            $user->district = $request->district;
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            $user->business_type = $request->business_type;
            $user->business_category = $request->business_category;
            $user->pincode = $request->pincode;
            $user->gstin = $request->gstin;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->city = $request->city;
            // $user->image = '';
            if ($request->image != null) {
                $path = Storage::put('public/avatars', $request->file('image'), 'public');
                $user->image = $path;
            }
            $updateResult = ArchivedRetailer::where('org_phone', $request->mobile)->update(['status' => '0']);
            User::where('mobile', $request->mobile)->update(['name' => $request->name, 'business_name' => $request->business_name, 'state' => $request->state, 'district' => $request->district, 'business_type' => $request->business_type, 'business_category' => $request->business_category, 'pincode' => $request->pincode, 'gstin' => $request->gstin,'email' => $request->email,'address' => $request->address,'city' => $request->city, 'latitude' => $request->latitude, 'longitude' => $request->longitude, 'image' => $user->image]);
            if(!empty($updateResult)){
                $ArchivedRetailerId = DB::table('archived_retailers')->where('org_phone', $request->mobile)->pluck('id')->toArray();
                $userId = DB::table('users')->where('mobile', $request->mobile)->pluck('id')->toArray();
                $error = $this->updateChat($userId[0], $ArchivedRetailerId[0]);
            }
//            if ($user->save()) {
                return $this->success([], "User Created Successfully");
//            } else {
//                return $this->error("User creation failed");
//            }
        }
        catch(Exception $e){
              return $this->error($e->getMessage());
        }

    }

    public function search(Request $request)
    {

        $validatedData = Validator::make($request->all(), [
            'search_key' => 'required',
        ]);

        if ($validatedData->fails()) {
            return $this->error($validatedData->errors()->first(), 401);
        }

            $users = User::select('users.id','users.name','mobile','business_name','state', 'district','business_type', 'business_category')
                    ->when($request->business_type, function ($query) use ($request) {
                    return $query->where("business_type", $request->business_type);
                    })
                    ->when($request->state, function ($query) use ($request) {
                      return  $query->where('state', $request->state);
                    })
                    ->when($request->district, function ($query) use ($request) {
                      return  $query->where('district', $request->district);
                    })
                    ->when($request->business_category, function ($query) use ($request) {
                      return  $query->where('business_category', $request->business_category);
                    })
                    ->where(function ($q) use ($request) {
                        return $q->where('name', 'LIKE', '%' . $request->search_key . '%')
                            ->orWhere('mobile', 'LIKE', '%' . $request->search_key . '%')
                            ->orWhere('business_name', 'LIKE', '%' . $request->search_key . '%');
                    })
                    ->where('status', 1)
                    ->get()->except($this->getAuthenticatedUser()->id);
            
            $archived = ArchivedRetailer::select('id','org_name AS business_name','org_phone AS mobile','state', 'district','business_type', 'category AS business_category')
                        ->when($request->business_type, function ($query) use ($request) {
                        return $query->where("business_type", $request->business_type);
                        })
                        ->when($request->state, function ($query) use ($request) {
                          return  $query->where('state', $request->state);
                        })
                        ->when($request->district, function ($query) use ($request) {
                          return  $query->where('district', $request->district);
                        })
                        ->when($request->business_category, function ($query) use ($request) {
                          return  $query->where('category', $request->business_category);
                        })
                        ->where(function ($q) use ($request) {
                            return $q->where('org_name', 'LIKE', '%' . $request->search_key . '%');
                            })
                        ->where('status', 1)
//                        ->groupBy('business_category')
                        ->get();

                        $result = $users->union($archived);
            
        return $this->success($result, "Search Results");
    }

    public function addFavorite(Request $request)
    {
        $user_id = $this->getAuthenticatedUser()->id;
        $favorite = Favorite::where('user_id', $user_id)
            ->where('vendor_id', $request->vendor_id)->first();
        if ($favorite) {
            if ($favorite->delete()) {
                return $this->success(false, "Successful");
            } else {
                return $this->error("Failed");
            }

        } else {
            $favorite = new Favorite();
            $favorite->user_id = $user_id;
            $favorite->vendor_id = $request->vendor_id;
            if ($favorite->save()) {
                return $this->success(true, "Successful");
            } else {
                return $this->error("Failed");
            }
        }
    }

    /**
     * List of all vendors starred by user
     *
     * @param Request $request
     * @return void
     */
    public function starredVendors(Request $request)
    {
        $data = Favorite::where('user_id', $this->getAuthenticatedUser()->id)->with('vendor')->get();
        
        return $this->success($data, "List");
    }

    /**
     * Fetch detail of user
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function userDetail(Request $request, $id)
    {
        $data = User::where('id', $id)
                ->with('products')
                ->first();
        
        if(empty($data)){
            $data = ArchivedRetailer::where('id', $id)
                    ->with('products')
                    ->select('id','org_name AS business_name', 'org_phone AS mobile', 'state','district','business_type','category AS business_category')
                    ->first();
        }

        $data->favorite = Favorite::where('user_id', $this->getAuthenticatedUser()->id)
            ->where('vendor_id', $id)->first() != null;

        return $this->success($data, "User detail");
    }

    /**
     * Get profile details of logged in user
     *
     * @return void
     */
    public function getUserProfile()
    {
        $data = $this->getAuthenticatedUser();

        return $this->success($data, "User Profile");
    }

    /**
     * Update Profile
     *
     * @param Request $request
     * @return void
     */
    public function updateProfile(Request $request)
    {
        try {
            User::where('id', $request->id)->update(['name' => $request->name,
                'business_name' => $request->business_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'state' => $request->state,
                'district' => $request->district,
                'pincode' => $request->pincode,
                'address' => $request->address,
                'city' => $request->city,
                'gstin' => $request->gstin]);

            return $this->success([], "Profile updated");

        } catch (Exception $e) {
            return $this->error("Failed to update profile");
        }

    }

    public function changePassword(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        $validatedData = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:4',
            'confirm_password' => 'required|same:new_password']);

        if ($validatedData->fails()) {
            return $this->error($validatedData->errors()->first());
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->error('Current password is invalid');
        }

        User::where('id', $user->id)->update(['password' => Hash::make($request->new_password)]);

        return $this->success([], "Password changed successfully");
    }

    public function doForgotPassword(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'mobile' => 'required|digits:10']
        );

        if ($validatedData->fails()) {
            return $this->error($validatedData->errors()->first());
        }

        $user = User::where('mobile', $request->mobile)->first();
        if ($user) {
            $pass = rand(1000, 9999);
            $user->password = Hash::make($pass);
            $user->save();
            $error = $this->sendMessage($user->mobile, $pass);
            if ($error == null) {
                return $this->success([], "Your new password has been sent to your mobile number as SMS.");
            } else {
                return $this->error($error);
            }

        } else {
            return $this->error("This user is not registered with us.");
        }
    }

    public function sendMessage($phone, $password)
    {
        $curl = curl_init();
        $message= "Your new password for Badhat app: $password";
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
//        print_r($response); die;
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
    
    public function getAppStateData(){
        $user_id = $this->getAuthenticatedUser()->id;
        
        $notification = Notification::where('user_id',$user_id)->where('is_read',0)->get()->count();
        $cart = CartItem::where('user_id',$user_id)->get()->count();
        $order = Order::where('user_id',$user_id)->where('status','Placed')->get()->count();
        
        $data = array("notification"=>$notification,"cart"=>$cart,"order"=>$order);
        return $this->success($data,"App State Data");
        
    }
    
    public function updateChat($userId, $ArchivedRetailerId){
        
        ChatRoom::where('vendor_id', $ArchivedRetailerId)->update(['name' => DB::raw("REPLACE(name,  $ArchivedRetailerId, $userId)")]);
        ChatRoom::where('vendor_id', $ArchivedRetailerId)->update(['vendor_id' => $userId]);
    }
}
