<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;
use App\Comment;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
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

     public function myposts($id)
     {
         $postdata = Post::where('user_id', $id)->orderBy('created_at','desc')->paginate(10);
         $userdata = User::find($id);
         return view('posts.myposts')->with('posts', $postdata)->with('userdata',$userdata);
     }

    public function index()
    {
        $postdata = Post::orderBy('created_at','desc')->paginate(10);
        return view('posts.index')->with('posts', $postdata);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      if( Auth::user()->locked == 'unlocked' ){
        return view('posts.create');
      }
      elseif( Auth::user()->locked == 'locked' ){
        return redirect()->route('home')->with('toast_error','Your Account Is Locked ! You Are Not Allowed To Post !!');
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    /*    $this->validate($request,
        [
            'title' => 'required',
            'body' => 'required',
            //most apache servers will not allow upload more than 2MB so maxsize=1999
            'cover_image' => 'image|nullable|max:1999'
        ]);
    */

/*
        $validator = Validator::make($request->all(), [
          'title' => 'required',
          'body' => 'required',
          //most apache servers will not allow upload more than 2MB so maxsize=1999
          'cover_image' => 'image|nullable|max:1999'
        ]);

        if($validator->fails()){
          $errors = $validator->errors();
          //dd($validator->messages()->all());
          return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        }

*/

      if( Auth::user()->locked == 'unlocked' ){

              //validation
              $request->validate([
                'title' => 'required',
                'body' => 'required',
                //most apache servers will not allow upload more than 2MB so maxsize=1999
                'cover_image' => 'image|nullable|max:1999'
              ]);

          //handle file upload(will create a folder named cover_images inside "storage\app\public\"
          // and upload images or files there) but you have to use php artisan storage:link in cmd
            if($request->hasFile('cover_image')) {
              //get filename with extension
              $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
              //get just file name (using standard php function)
              $filename = pathinfo('$filenameWithExt', PATHINFO_FILENAME);
              //get just extension
              $extension = $request->file('cover_image')->getClientOriginalExtension();
              //filename to store(uses a time php function to get current time)
              //this string is a unique name so that file with duplicate name do not get uploaded and
              //cause problems when viewing(same problem that occured in CISV photo gallery)
              $filenameToStore= $filename.'_'.time().'_'.$extension;
              //upload image
              $path = $request->file('cover_image')->storeAs('public/cover_images', $filenameToStore);
            }
            else{
              $filenameToStore = 'noimage.jpg';
            }

          $post = new Post();
          $post->title = $request->input('title');
          $post->body = $request->input('body');
          $post->user_id = auth()->user()->id;
          $post->cover_image = $filenameToStore;
          $post->save();

          return redirect()->route('posts.index')->with('toast_success', 'Post Created Successfully !!');

      }
      elseif( Auth::user()->locked == 'locked' ){
        return redirect()->route('home')->with('toast_error','Your Account Is Locked ! You Are Not Allowed To Post !!');
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
          $postdata = Post::find($id);
          $comments = Comment::where('post_id', $id)->orderBy('created_at','desc')->get();

          //temporarily store previous URL
          $url =  url()->previous();

          //store previous URL in session to pass to views to generate back button for 3 access points
          //3 possible access point urls. myposts, all posts, and delete
          //condition to store previous url for each case(use standard PHP searvh string)
          if( url()->previous() != url()->current() ){
            if (strpos($url, 'myposts') !== false) {
              session(['let_url' => $url]);
            }
            elseif(strpos($url, 'delete') !== false) {

            }
            elseif(strpos($url, 'posts') !== false) {
                session(['let_url' => $url]);
            }
          }

          if( $postdata->flagged == '0' ){
            return view('posts.show')->with('posts', $postdata)->with('comments', $comments);
          }
          if( $postdata->flagged == '1' ) {
            if( Auth::user()->id == $postdata->user_id ||  Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin' ){
              return view('posts.show')->with('posts', $postdata)->with('comments', $comments);
            }
          }
          return redirect()->route('posts.index')>with('toast_error', 'Unauthorized Page !! This Post Has Been Flagged !!');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $postdata = Post::find($id);

      if(auth()->user()->id !== $postdata->user_id){
        return redirect()->route('posts.index')->with('error', 'Unauthorized Page !!');
      }

      return view('posts.edit')->with('posts', $postdata);
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
        $this->validate($request,
        [
            'title' => 'required',
            'body' => 'required'
        ]);

        //handle file upload(will create a folder named cover_images inside "storage\app\public\"
        // and upload images or files there) but you have to use php artisan storage:link in cmd
        //works only if there is an image upload

        if($request->hasFile('cover_image')) {
            //get filename with extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            //get just file name (using standard php function)
            $filename = pathinfo('$filenameWithExt', PATHINFO_FILENAME);
            //get just extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store(uses a time php function to get current time)
            //this string is a unique name so that file with duplicate name do not get uploaded and
            //cause problems when viewing(same problem that occured in CISV photo gallery)
            $filenameToStore= $filename.'_'.time().'_'.$extension;
            //upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $filenameToStore);
        }

        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if($request->hasFile('cover_image')){     //works if there is a new image uploaded
          Storage::delete('public/cover_images/'.$post->cover_image);  //deletes previous image
                                                    //needs to use Illuminate\Support\Facades\Storage;
          $post->cover_image = $filenameToStore;
        }
        $post->save();

        return redirect()->route('posts.index')->with('toast_success', 'Post Updated successfully !!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $post = Post::find($id);

      if(auth()->user()->id !== $post->user_id){
        return redirect()->route('posts.index')->with('error', 'Unauthorized Page !!');
      }

      if($post->cover_image != 'noimage.jpg'){
        Storage::delete('public/cover_images/'.$post->cover_image);
      }

      $post->delete();
      return redirect()->route('posts.index')->with('toast_success', 'Post Deleted !!');
    }

    public function delete($id)
    {
      $postdata = Post::find($id);

      if(auth()->user()->id !== $postdata->user_id){
        return redirect()->route('posts.index')->with('error', 'Unauthorized Page !!');
      }
      return view('posts.delete')->with('posts', $postdata);
    }

}
