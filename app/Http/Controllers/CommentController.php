<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;
use App\Comment;
use Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
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
      $this->validate($request,
      [
          'comment' => 'required'
      ]);

        $comment = new Comment();
        $comment->comment = $request->input('comment');
        $comment->post_id = $request->input('post_id');
        $comment->user_id = $request->input('user_id');
        $comment->username = $request->input('username');
        $comment->save();
        $post_id = $request->input('post_id');
        return redirect()->route('posts.show', $post_id)->with('toast_success', 'Comment Posted Successfully !!');
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
      $comment = Comment::find($id);
      $comment_user = User::find($comment->user_id);

      if(auth()->user()->id !== $comment->user_id){
        return redirect()->route('posts.index')->with('error', 'Unauthorized Page !!');
      }
      return view('comments.edit')->with('comment', $comment)->with('comment_user', $comment_user);
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
          'comment' => 'required'

      ]);

      $comment = Comment::find($id);
      $comment->comment = $request->input('comment');
      $comment->save();

      return redirect()->route('posts.show', $comment->post_id)->with('toast_success', 'Post Updated successfully !!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $comment = Comment::find($id);

      if(auth()->user()->id !== $comment->user_id){
        return redirect()->route('posts.index')->with('error', 'Unauthorized Page !!');
      }

      $comment->delete();
      return redirect()->route('posts.show', $comment->post_id)->with('toast_success', 'Post Deleted !!');
    }

    public function delete($id)
    {
      $comment = Comment::find($id);
      $comment_user = User::find($comment->user_id);

      if(auth()->user()->id !== $comment->user_id){
        return redirect()->route('posts.index')->with('error', 'Unauthorized Page !!');
      }
      return view('comments.delete')->with('comment', $comment)->with('comment_user', $comment_user);
    }
}
