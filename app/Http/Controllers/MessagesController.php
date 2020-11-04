<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\UnseenMessage;
use App\User;
use App\FriendList;
use Auth;
use Illuminate\Support\Facades\Validator;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
     {
         $this->middleware(['auth','verified']);
     }

     public function messagesPage($my_id)
     {
       if($my_id == Auth::user()->id){
       $sent_requests = FriendList::where( 'user_id_from', $my_id )->where('pending', 2)->get();
       $received_requests = FriendList::where( 'user_id_to', $my_id )->where('pending', 2)->get();

       $all_friend_list = collect();
       if($sent_requests){
         foreach($sent_requests as $sent){
           $friend1_info = User::find($sent->user_id_to);
           $all_friend_list->push($friend1_info);
         }
       }

       if($received_requests){
         foreach($received_requests as $rec){
           $friend2_info = User::find($rec->user_id_from);
           $all_friend_list->push($friend2_info);
         }
       }
       //module for counting number of unread messages
        foreach ($all_friend_list as $friend){
            $unseen_msg_number = count( Message::where('user_id_from', $friend->id)
            ->where('user_id_to', Auth::user()->id)->where('read', '0')->get() );
            //proper way to append a fucking row in a fucking collection
            $friend->msg_number = $unseen_msg_number;
        }
        //end module

         return view('messages.index')->with('all_friend_list', $all_friend_list);
       }
     }

     public function friendslist( $id )
     {
       if( Auth::user()->id == $id ){
           $sent_requests = FriendList::where('user_id_from', $id)->where('pending', 1)->get();
           $received_requests = Friendlist::where('user_id_to', $id)->where('pending', 1)->get();

           //all friends who the user sent a request to and has been accepted
           $friend_list1 = FriendList::where('user_id_from', $id)->where('pending', 2)->get();
           //all friends who the user got a request from and has been accepted
           $friend_list2 = FriendList::where('user_id_to', $id)->where('pending', 2)->get();
           //an empty collection that contains all confirmed friends
           $all_friends_list = collect();
            //if friends_list1 is not empty
           if($friend_list1){
             //foreach friend user has sent a request to, get his id(user_id_from) and search for his info and store it in $user
             //Then push it to the $all_friends_list collection
             foreach ($friend_list1 as $friend) {
                 $users = User::find($friend->user_id_to);
                 //push the collection to $all_friends_list
                 $all_friends_list->push($users);
              }
           }

           //if friends_list2 is not empty
           if($friend_list2){
             //foreach friend user has received a request from, get his id(user_id_to) and search for his info and store it in $user
             //Then push it to the $all_friends_list collection
             foreach ($friend_list2 as $friend) {
                 $user = User::find($friend->user_id_from);
                 //push the collection to $all_friends_list
                 $all_friends_list->push($user);
              }
           }

           //module for counting number of unread messages
            foreach ($all_friends_list as $friend) {
                $unseen_msg_number = count( Message::where('user_id_from', $friend->id)
                ->where('user_id_to', Auth::user()->id)->where('read', '0')->get() );
                //proper way to append a fucking row in a fucking collection
                $friend->msg_number = $unseen_msg_number;
            }
            //end module

           //module for getting user data that have been sent friend request to
           //declare empty collection
           $sent_to_user = collect();
           foreach ($sent_requests as $sent) {
               $user = User::find($sent->user_id_to);
               $sent_to_user->push($user);
            }

            //module for getting user data that we have received friend request from
            //declare empty collection
            $received_from_user = collect();
            foreach ($received_requests as $rec) {
                $user = User::find($rec->user_id_from);
                $received_from_user->push($user);
             }

             return view('messages.friendslist')->with('sent_to_user', $sent_to_user)
             ->with('received_from_user', $received_from_user)
             ->with('friends', $all_friends_list)
             ->with('my_id', Auth::user()->id);
        }
        else{
          return redirect()->route('home')->with('toast_error', 'Unauthorized Page');
        }
     }

     public function sendRequest( $from_id, $to_id )
     {
       //current user's profile data and ID. Needed for checking if the edit button shows or not on display page
       $profile_data = User::find($from_id);

       if(Auth::user()->id == $from_id){

           // Save a friend request using ids'
           $friend_request = new FriendList;
           $friend_request->user_id_from = $from_id;
           $friend_request->user_id_to = $to_id;
           $friend_request->pending = '1';
           $friend_request->save();


           $sent_requests = FriendList::where('user_id_from', $from_id )->where('user_id_to', $to_id)->first();
           $received_requests = FriendList::where('user_id_from', $to_id)->where('user_id_to', $from_id )->first();
           //if collection $sent_request is empty(null), then move to else if and check if $received_request is empty(null)
           //if both are empty, then set flag = 0
           if($sent_requests){
             $friend_request_flag = 1;
           }
           else if($received_requests){
             $friend_request_flag = 1;
           }
           else{
             $friend_request_flag = 0;
           }
           //refresh page. If you redirect, the URL/route for sending friend request shows which is BS.
           return back()->with('toast_success','Friend Request Sent !!')
           ->with('profile_data', $profile_data)
           ->with('friend_request_flag', $friend_request_flag);
       }
       else{
           return redirect()->route('home')->with('toast_error', 'Unauthorized Page');
       }
     }

     public function acceptRequest($from_id, $to_id){

       if(Auth::user()->id == $to_id){
           $friend_request = FriendList::where('user_id_from', $from_id)
           ->where('user_id_to', $to_id)->first();
           $friend_request->pending = '2';
           $friend_request->save();

           $friend = User::find($from_id);

           return back()->with('toast_success', 'You Have Accepted '.$friend->name.'\'s Friend Request');
       }
       else{
         return back()->with('toast_error', 'Unauthorized Page');
       }
     }

     public function rejectRequest($from_id, $to_id){

       if(Auth::user()->id == $to_id){
           $friend_request = FriendList::where('user_id_from', $from_id)
           ->where('user_id_to', $to_id)->first();
           $friend_request->delete();

           $friend = User::find($from_id);

           return back()->with('toast_success', 'You Have Rejected '.$friend->name.'\'s Friend Request');
       }
       else{
         return back()->with('toast_error', 'Unauthorized Page');
       }
     }

     public function Unfriend($id1, $id2){

       if( Auth::user()->id == $id1 || Auth::user()->id == $id2 ){

          $friend_request1 = FriendList::where('user_id_from', $id1)->where('user_id_to', $id2)->where('pending', '2')->first();
          if($friend_request1){
            $friend_request1->delete();
          }

          $friend_request2 = FriendList::where('user_id_from', $id2)->where('user_id_to', $id1)->where('pending', '2')->first();
          if($friend_request2){
            $friend_request2->delete();
          }

        if(Auth::user()->id == $id1){
          $friend = User::find($id2);
        }
        elseif(Auth::user()->id == $id2){
          $friend = User::find($id1);
        }

           return back()->with('toast_warning', 'You Have Unfriended '.$friend->name);
       }
       else{
           return redirect()->route('home')->with('toast_error', 'Unauthorized Page');
       }

     }

     public function seeMessage( $id1, $id2 )
     {
       if( $id1 == Auth::user()->id){
       $user_data = User::find($id1);
       $friend_data = User::find($id2);

       //current logged in users messages
       $friend_message = Message::where('user_id_from', $id1)->where('user_id_to', $id2)->where('read', '0')->get();
       if($friend_message){
         //change/mark as read
         foreach($friend_message as $friend){
            $via_message = Message::find($friend->id);
            $via_message->user_id_from = $friend->user_id_from;
            $via_message->user_id_to = $friend->user_id_to;
            $via_message->body = $friend->body;
            $via_message->save();
         }
       }

       $friend_message = Message::where('user_id_from', $id2)->where('user_id_to', $id1)->where('read', '0')->get();
       if ($friend_message) {
         //change/mark as read
         foreach($friend_message as $friend){
            $via_message = Message::find($friend->id);
            $via_message->user_id_from = $friend->user_id_from;
            $via_message->user_id_to = $friend->user_id_to;
            $via_message->body = $friend->body;
            $via_message->read = '1';
            $via_message->save();
         }
       }

         $all_messages = collect();
         $get_friend_messages = Message::where('user_id_from', $id1)->where('user_id_to', $id2)->get();
         foreach($get_friend_messages as $friend_messages){
            $friend_message = Message::find($friend_messages->id);
            $all_messages->push($friend_messages);
         }
         $get_friend_messages = Message::where('user_id_from', $id2)->where('user_id_to', $id1)->get();
         foreach($get_friend_messages as $friend_messages){
            $friend_message = Message::find($friend_messages->id);
            $all_messages->push($friend_messages);
         }

         $all_messages = $all_messages->sortByDesc('created_at');

       return view('messages.seemessages')
       ->with('all_messages', $all_messages)
       ->with('user_data', $user_data)
       ->with('friend_data', $friend_data);
     }
     else{
       return redirect()->route('home')->with('toast_error', 'Unauthorized Page !!');
     }
    }

     public function sendMessage(Request $request){
       $this->validate($request,
       [
           'message' => 'required'
       ]);

         $message = new Message();
         $message->body = $request->input('message');
         $message->user_id_from = $request->input('user_id_from');
         $message->user_id_to = $request->input('user_id_to');
         $message->save();
         return back()->with('toast_success', 'Message Posted Successfully !!');
     }

}
