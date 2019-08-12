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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $albums   = Album::where('parent_id', null)->get();
        $contents = Content::all();
//        dd(Album::all());
//        if ($request->filled('album')) {
//            $album = $request->get('album');
//            dd($album);
//        }
//        $items = [
//            '',
//            '',
//            '',
//            '',
//            '',
//        ];
        return view('home', ['albums' => $albums, 'contents' => $contents]);
    }
}
