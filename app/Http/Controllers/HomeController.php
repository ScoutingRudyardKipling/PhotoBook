<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Content;
use Illuminate\Http\Request;

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
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $albums   = Album::where('parent_id', null)->get();
        $contents = Content::all();

        return view('pages.home', ['albums' => $albums, 'contents' => $contents]);
    }
}
