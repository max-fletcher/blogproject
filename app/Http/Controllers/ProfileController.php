<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\FriendList;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
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

     public function display($id)
     {
            $profile_data = Auth::user()->find($id); //anyone's profile data
            $profile_id = Auth::user()->id;   //current logged in user id

            $sent_requests = FriendList::where('user_id_from', $profile_data->id )->where('user_id_to', $profile_id)->first();
            $received_requests = FriendList::where('user_id_from', $profile_id)->where('user_id_to', $profile_data->id )->first();
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
            return view('profiles.display')->with('profile_data', $profile_data)->with('profile_id', $profile_id)->with('friend_request_flag', $friend_request_flag);
     }

    public function index()
    {
      //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if( Auth::user()->id == $id ){
          $profile_data = Auth::user()->find($id);
          return view('profiles.edit')->with('profile_data',$profile_data);
        }
        else{
            return redirect()->route('profile.display', $id)->with('toast_error','Unauthorized Page !!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if( Auth::user()->id == $id ){
            $profile = User::find($id);

            if($request->hasFile('profile_image')) {
                //delete previous image
                Storage::delete('public/cover_images/'.Auth::user()->profile_image);
                //get filename with extension
                $filenameWithExt = $request->file('profile_image')->getClientOriginalName();
                //get just file name (using standard php function)
                $filename = pathinfo('$filenameWithExt', PATHINFO_FILENAME);
                //get just extension
                $extension = $request->file('profile_image')->getClientOriginalExtension();
                //filename to store(uses a time php function to get current time)
                //this string is a unique name so that file with duplicate name do not get uploaded and
                //cause problems when viewing(same problem that occured in CISV photo gallery)
                $filenameToStore= $filename.'_'.time().'_'.$extension;
                //upload image
                $path = $request->file('profile_image')->storeAs('public/profile_images', $filenameToStore);
            }
              else{
                $filenameToStore = Auth::user()->profile_image;
            }


            if ($request->input('interests') != null){
              $profile->interests = $request->input('interests');
            }
            else{
              $profile->interests = '';
            }

            if ($request->input('about_me') != null){
              $profile->about_me = $request->input('about_me');
            }
            else{
              $profile->about_me = '';
            }

            if ($request->input('favourite_shows') != null){
              $profile->favourite_shows = $request->input('favourite_shows');
            }
            else{
              $profile->favourite_shows = '';
            }

            if ($request->input('favourite_movies') != null){
              $profile->favourite_movies = $request->input('favourite_movies');
            }
            else{
              $profile->favourite_movies = '';
            }

            if ($request->input('favourite_books') != null){
                $profile->favourite_books = $request->input('favourite_books');
            }
            else{
              $profile->favourite_books = '';
            }
            $profile->profile_image = $filenameToStore;
            $profile->save();

            return redirect()->route('profile.display', $id)->with('toast_success', 'Profile Updated Successfully !!');
        }
        else{
          return redirect()->route()->with('toast_error', 'Unauthorized Page !!');
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
