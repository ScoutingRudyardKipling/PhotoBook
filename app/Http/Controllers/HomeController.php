<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Content;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $albums   = Album::where('parent_id', null)->orderBy('name', 'asc')->get();
        $contents = Content::all();

        return view('pages.home', ['albums' => $albums, 'contents' => $contents]);
    }
}
