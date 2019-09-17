<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Content;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('pages.album.show');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('pages.album.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data  = $request->validate(
            [
                'name'      => 'required|string|max:190',
                'parent_id' => 'nullable|integer',
            ]
        );
        $album = Album::create($data);
        return redirect()->route('album.show', ['album' => $album->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Album $album
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Album $album)
    {
        $albums   = $album->childAlbums()->get();
        $contents = $album->contents()->get();

        return view(
            'pages.album.show',
            [
                'album'    => $album,
                'albums'   => $albums,
                'contents' => $contents,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Album $album
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Album $album)
    {
        // TODO: Implement
        unset($album);
        return view('pages.album.show');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Album        $album
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request, Album $album)
    {
        // TODO: Implement
        unset($album);
        unset($request);
        return view('pages.album.show');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Album $album
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Album $album)
    {
        // TODO: Implement
        unset($album);
        return redirect()->route('album.show');
    }
}
