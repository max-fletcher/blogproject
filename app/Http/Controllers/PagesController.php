<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
      return view('pages.index');
    }

    public function about(){
      return view('pages.about');
    }

    public function services(){
      $data = [
        "title" => "Services Page",
        "subheading" => "We provide the following services:",
        "services" => ["User Profile", "Blogging", "Image Posting", "Photo Gallery"]
      ];
      return view('pages.services')->with($data);
    }
}
