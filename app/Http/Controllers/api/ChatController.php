<?php

namespace App\Http\Controllers\api;

use App\Chat;
use App\ChatRoom;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    /**
     * Return list of all chat rooms for the user
     *
     * @return
     */
    public function index()
    {
        $user_id = $this->getAuthenticatedUser()->id;
        $data = ChatRoom::where('user_id', $user_id)
            ->orWhere('vendor_id', $user_id)
            ->latest()
            ->get()->map(function ($chat) use ($user_id) {
            $isFirstUser = $chat->vendor_id == $user_id;
            if ($isFirstUser) {
                $id = $chat->user_id;
            } else {
                $id = $chat->vendor_id;
            }

            $chat->vendor = User::select('id', 'name', 'business_name', 'image')->where('id', $id)->first();
            return $chat;
        });

        return $this->success($data, "Chat Rooms");
    }

    /**
     * Add chat message
     *
     * @param Request $request
     * @return void
     */
    public function addChatMessage(Request $request)
    {
        $room = null;

        $user_id = $this->getAuthenticatedUser()->id;
        if ($request->room_id == null) {
            $name = $user_id . "_" . $request->vendor_id;
            $room = ChatRoom::where('name', $name)->first();
            if($room==null){
                $room = new ChatRoom();
                $room->name = $name;
                $room->vendor_id = $request->vendor_id;
                $room->user_id = $user_id;
                $room->save();
                $room->refresh();
                $error = $this->sendMessage($room->user_id, $room->vendor_id);
                if ($error == null) {
                    return $this->success([], "Message sent on mobile too.");
                } else {
                    return $this->error($error);
                }
            }

        } else {
            $room = ChatRoom::where('id', $request->room_id)->first();
        }

        // insert messages to chat room
        $chat = new Chat();
        $chat->sender_id = $user_id;
        $chat->room_id = $room->id;
        $chat->message = $request->message;
        $chat->save();

        $chat->refresh();
        
        $vendor = User::find($room->vendor_id);
        $user = User::find($room->user_id);
        if($user_id == $room->vendor_id)
            $this->sendPush('New Message', $chat->message, $user->fcm_token);
        if($user_id == $room->user_id)
            $this->sendPush('New Message',$chat->message, $vendor->fcm_token);

        return $this->success($chat, "Success");
    }

    /**
     * Get Chat History
     *
     * @param [int $room_id
     * @return void
     */
    public function chats(int $room_id)
    {
        $chats = Chat::where('room_id', $room_id)->get();

        return $this->success($chats, "Chats");
    }

    public function checkOrCreateAdminRoom()
    {
        $user_id = $this->getAuthenticatedUser()->id;
        $room_name = $user_id . "_1";
        $room = ChatRoom::where('name', $room_name)->first();

        if ($room) {
            return $this->success($room->id, "Admi Chat room");
        } else {
            $room = new ChatRoom();
            $room->user_id = $user_id;
            $room->vendor_id = 1;
            $room->name = $room_name;

            $room->save();
            $room->refresh();

            return $this->success($room->id, "Admi Chat room");
        }
    }
    
    public function sendMessage($user_id, $vendor_id)
    {
        
        $data_user = User::select('name', 'business_name')->where('id', $user_id)->first();
        $data_vendor = User::select('name', 'mobile', 'business_name')->where('id', $vendor_id)->first();
        $phone = $data_vendor->mobile;
        $business_name_receiver = $data_vendor->business_name;
        $business_name_sender = $data_user->business_name;
        $curl = curl_init();
        $message= "Hi $business_name_receiver you have received message from $business_name_sender on Badhat App. Click to see --link--.";
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
