<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PagesController@index' )->name('pages.index');
Route::get('/services', 'PagesController@services' )->name('services.index');
Route::get('/about', 'PagesController@about' )->name('about.index');

Auth::routes(['verify' => true]);

Route::resource('posts', 'PostsController')->middleware(['verified', 'auth']);
Route::get('/posts/myposts/{id}', 'PostsController@myposts' )->name('posts.myposts')->middleware(['verified', 'auth']);
Route::get('/posts/{post}/delete', 'PostsController@delete' )->name('posts.delete')->middleware(['verified', 'auth']);


Route::resource('comments', 'CommentController')->middleware(['verified', 'auth']);
Route::get('/comments/{comment_id}/delete', 'CommentController@delete' )->name('comments.delete')->middleware(['verified', 'auth']);

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('photo', 'PhotoController')->middleware('verified');
Route::get('photo/display/{userid}', 'PhotoController@display' )->name('photo.display')->middleware(['verified', 'auth']);
Route::get('/photo/{photoid}/delete', 'PhotoController@delete' )->name('photo.delete')->middleware(['verified', 'auth']);
Route::get('photo/showphoto/{photoid}/{userid}', 'PhotoController@showphoto' )->name('photo.showphoto')->middleware(['verified', 'auth']);

Route::get('friendslist/{id}', 'MessagesController@friendslist' )->name('messages.friendslist')->middleware(['verified', 'auth']);
Route::get('sendRequest/{from_id}/{to_id}', 'MessagesController@sendRequest' )->name('messages.sendRequest')->middleware(['verified', 'auth']);
Route::get('acceptRequest/{from_id}/{to_id}', 'MessagesController@acceptRequest' )->name('messages.acceptRequest')->middleware(['verified', 'auth']);
Route::get('rejectRequest/{from_id}/{to_id}', 'MessagesController@rejectRequest' )->name('messages.rejectRequest')->middleware(['verified', 'auth']);
Route::get('unfriend/{id1}/{id2}', 'MessagesController@unfriend' )->name('messages.unfriend')->middleware(['verified', 'auth']);
Route::get('messagespage/{my_id}', 'MessagesController@messagesPage' )->name('messages.messagesPage')->middleware(['verified', 'auth']);
Route::get('seeMessage/{id1}/{id2}', 'MessagesController@seeMessage' )->name('messages.seeMessage')->middleware(['verified', 'auth']);
Route::post('sendMessage', 'MessagesController@sendMessage' )->name('messages.sendMessage')->middleware(['verified', 'auth']);

Route::resource('profile', 'ProfileController')->middleware(['verified', 'auth']);
Route::get('profile/display/{userid}', 'ProfileController@display' )->name('profile.display')->middleware(['verified', 'auth']);

Route::get('/adminpanel/index', 'AdminController@adminpanel')->name('adminpanel.index')->middleware(['verified', 'auth']);
Route::get('/adminpanel/assignadmin/{id}', 'AdminController@assignadmin')->name('adminpanel.assignadmin')->middleware(['verified', 'auth']);
Route::get('/adminpanel/assignuser/{id}', 'AdminController@assignuser')->name('adminpanel.assignuser')->middleware(['verified', 'auth']);
Route::get('/adminpanel/flagpost/{post_id}', 'AdminController@flagpost')->name('adminpanel.flagpost')->middleware(['verified', 'auth']);
Route::get('/adminpanel/unflagpost/{post_id}', 'AdminController@unflagpost')->name('adminpanel.unflagpost')->middleware(['verified', 'auth']);
Route::get('/adminpanel/flagphoto/{photo_id}', 'AdminController@flagphoto')->name('adminpanel.flagphoto')->middleware(['verified', 'auth']);
Route::get('/adminpanel/unflagphoto/{photo_id}', 'AdminController@unflagphoto')->name('adminpanel.unflagphoto')->middleware(['verified', 'auth']);
Route::get('/adminpanel/sendwarningemail/{user_id}', 'AdminController@sendwarningemail')->name('adminpanel.sendwarningemail')->middleware(['verified', 'auth']);
Route::get('/adminpanel/lockuser/{user_id}', 'AdminController@lockuser')->name('adminpanel.lockuser')->middleware(['verified', 'auth']);
Route::get('/adminpanel/unlockuser/{user_id}', 'AdminController@unlockuser')->name('adminpanel.unlockuser')->middleware(['verified', 'auth']);

Route::get('/comments/flagcomment/{comment_id}', 'AdminController@flagComment' )->name('adminpanel.flagcomment')->middleware(['verified', 'auth']);
Route::get('/comments/unflagcomment/{comment_id}', 'AdminController@unflagComment' )->name('adminpanel.unflagcomment')->middleware(['verified', 'auth']);
