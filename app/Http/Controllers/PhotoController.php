<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Photo;
use App\User;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
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

     public function display($userid)
     {
          $photodata = Photo::where('userid', $userid)->get();
          $profile_id = Auth::user()->id;
          $user = User::find($userid);
          return view('photo.index')->with( 'photodata' , $photodata )->with( 'profile_id', $profile_id )->with( 'user_id', $userid )->with('user', $user);
     }

     public function index()
     {

     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      if( Auth::user()->locked == 'unlocked' ){

              $request->validate([
                'upload_photo' => 'required|image|nullable|max:1999'
              ]);

           if($request->hasFile('upload_photo')) {
                  //get filename with extension
                  $filenameWithExt = $request->file('upload_photo')->getClientOriginalName();
                  //get just file name (using standard php function)
                  $filename = pathinfo('$filenameWithExt', PATHINFO_FILENAME);
                  //get just extension
                  $extension = $request->file('upload_photo')->getClientOriginalExtension();
                  //filename to store(uses a time php function to get current time)
                  //this string is a unique name so that file with duplicate name do not get uploaded and
                  //cause problems when viewing(same problem that occured in CISV photo gallery)
                  $filenameToStore= $filename.'_'.time().'_'.$extension;
                  //upload image
                  $path = $request->file('upload_photo')->storeAs('public/upload_photo', $filenameToStore);
              }
              else{
                 return redirect()->route('photo.display',)->with('error','No File Chosen !!');
              }

              $photo = new Photo();
              $photo->description = $request->input('description');
              $photo->userid = auth()->user()->id;
              $photo->filename = $filenameToStore;
              $photo->save();

              $photodata = Photo::all();

              $data = array(
                "toast_success" => "Photo Uploaded Successfully !!",
                "photodata" => [$photodata]
              );

            return redirect()->route('photo.display', auth()->user()->id)->with($data);
          }
      elseif( Auth::user()->locked == 'unlocked' ){

        return redirect()->route('home')->with('toast_error','Your Account Is Locked ! You Are Not Allowed To Upload Photos !!');
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


    }

    public function showphoto($id, $userid)
    {
        $photodata = Photo::find($id);
        if( $photodata->flagged == '0' || Auth::user()->id == $photodata->userid || Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin' ){
          $user = User::find($userid);
          return view('photo.show')->with('photodata', $photodata)->with('user', $user);
        }
        else{
          return redirect()->route('photo.display', $id)->with('toast_error' , 'Unauthorized Page !! This Photo Has Been Flagged !!');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $photodata = Photo::find($id);

        if(auth()->user()->id != $photodata->userid){
            return redirect()->route('home')->with('error', 'Unauthorized Page !!');
        }
        else{

          Storage::delete('public/upload_photo/'.$photodata->filename);

          $photodata->delete();
          return redirect()->route('photo.display', $photodata->userid)->with('toast_success', 'Photo Deleted !!');
        }
    }

    public function delete($photoid)
    {
        $photodata = Photo::find($photoid);
        if(auth()->user()->id == $photodata->userid){
          return view('photo.delete')->with('photodata', $photodata);
        }
        else{
          return redirect()->route('photo.display', $photodata->userid )->with('toast_error','Unauthorized Page !!');
        }
    }
}
