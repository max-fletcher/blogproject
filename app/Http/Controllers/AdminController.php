<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
use App\Photo;
use App\Comment;
use Auth;
use App\Mail\WarningMail;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function __construct(){

        $this->middleware(['auth','verified']);
    }

    public function adminpanel(){

      if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin' ){
        $profiles = User::all();
        $posts = Post::all();
        return view('admin.adminpanel')->with('profiles',$profiles)->with('posts', $posts);
      }
      else{
        return redirect()->route('home')->with('toast_error', 'Unauthorized Page !!');
      }
    }


    public function assignadmin($id){

        if(Auth::user()->role == 'superadmin'){
          $profile = User::find($id);
          $profile->role = 'admin';
          $profile->save();
          $allprofiles = User::all();
          $posts = Post::all();
          return view('admin.adminpanel')->with('profiles', $allprofiles)->with('posts', $posts);
        }
        else{
          return redirect()->route('home')->with('toast_error', 'Unauthorized Page !!');
        }
    }

    public function assignuser($id){

        if(Auth::user()->role == 'superadmin'){
          $profile = User::find($id);
          $profile->role = 'user';
          $profile->save();
          $allprofiles = User::all();
          $posts = Post::all();
          return view('admin.adminpanel')->with('profiles', $allprofiles)->with('posts', $posts);
        }
        else{
          return redirect()->route('home')->with('toast_error', 'Unauthorized Page !!');
        }
    }

    public function flagpost($post_id){

        if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin'){
          $post = Post::find($post_id);
          $post->flagged = '1';
          $post->save();
          //$allposts = Post::all();
          return redirect()->route('posts.show', $post_id)->with('toast_success', 'Post Has Been Flagged !!'); //->with('posts', $allposts);
        }

        else{
          return redirect()->route('home')->with('toast_error', 'Unauthorized Page !!');
        }

    }

    public function unflagpost($post_id){

        if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin'){
          $post = Post::find($post_id);
          $post->flagged = '0';
          $post->save();
          //$allposts = Post::all();
          return redirect()->route('posts.show', $post_id)->with('toast_success', 'Post Has Been Unflagged !!'); //->with('posts', $allposts);
        }

        else{
          return redirect()->route('home')->with('toast_error', 'Unauthorized Page !!');
        }

    }

    public function flagphoto($photo_id){


        if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin'){
          $photo = Photo::find($photo_id);
          $photo->flagged = '1';
          $photo->save();
          $allphoto = Photo::all();
          return redirect()->route('photo.display', $photo->userid)->with('allphoto' , $allphoto);
        }
        else{
          return redirect()->route('home')->with('toast_error', 'Unauthorized Page !!');
        }

    }

    public function unflagphoto($photo_id){

        if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin'){
          $photo = Photo::find($photo_id);
          $photo->flagged = '0';
          $photo->save();
          $allphoto = Photo::all();
          return redirect()->route('photo.display', $photo->userid)->with('allphoto' , $allphoto);
        }
        else{
          return redirect()->route('home')->with('toast_error', 'Unauthorized Page !!');
        }

    }

    public function flagcomment($comment_id){
    
        if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin'){
          $comment = Comment::find($comment_id);
          $comment->flagged = '1';
          $comment->save();
          //$allposts = Post::all();
          return redirect()->route('posts.show', $comment->post_id)->with('toast_success', 'Comment Has Been Flagged !!'); //->with('posts', $allposts);
        }

        else{
          return redirect()->route('home')->with('toast_error', 'Unauthorized Page !!');
        }
    }

    public function unflagcomment($comment_id){

        if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin'){
          $comment = Comment::find($comment_id);
          $comment->flagged = '0';
          $comment->save();
          //$allposts = Post::all();
          return redirect()->route('posts.show', $comment->post_id)->with('toast_success', 'Comment Has Been Unflagged !!'); //->with('posts', $allposts);
        }

        else{
          return redirect()->route('home')->with('toast_error', 'Unauthorized Page !!');
        }

    }

    public function sendwarningemail($user_id){

        $user = User::find($user_id);
        if($user->role != 'superadmin'){
            if($user->id != Auth::user()->id){
              $user_warnings = $user->warnings;
              $user_warnings = $user_warnings + 1;
              $user->warnings = $user_warnings;
              $user->save();
              Mail::to($user)->send(new WarningMail($user));
            }
            else{
              return back()->with('toast_error', 'Admin Cannot Warn Himself !!');
            }
        }
        else{
            return redirect()->route('home')->with('toast_error', 'Superadmin Cannot Be Warned !!');
        }
        return back()->with('toast_success', 'Warning Email Sent Successfully !!');
    }

    public function lockuser($user_id){

        $user = User::find($user_id);
        if($user->role != 'superadmin'){
            if($user->id != Auth::user()->id){
              if($user->locked == 'unlocked'){
                $user->locked = "locked";
                $user->save();
              }
              else{
                return back()->with('toast_error', 'User Is Already Locked !!');
              }
            }
            else{
              return back()->with('toast_error', 'Admin Cannot Lock Himself !!');
            }
        }
        else{
            return redirect()->route('home')->with('toast_error', 'Superadmin Cannot Be Locked/Unlocked !!');
        }
        return back()->with('toast_success', 'User Locked Successfully !!');
    }

    public function unlockuser($user_id){

        $user = User::find($user_id);
        if($user->role != 'superadmin'){
          if($user->id != Auth::user()->id){
            if($user->locked == 'locked'){
              $user->locked = "unlocked";
              $user->save();
            }
            else{
              return back()->with('toast_error', 'User Is Already Unlocked !!');
            }
          }
          else{
            return back()->with('toast_error', 'Admin Cannot Unlock Himself !!');
          }
        }
        else{
            return redirect()->route('home')->with('toast_error', 'Superadmin Cannot Be Locked/Unlocked !!');
        }
        return back()->with('toast_success', 'User Unlocked Successfully !!');
    }

}
