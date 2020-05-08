<?php

namespace App\Http\Controllers;

use App\Facades\Clearance;
use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @param int|null $parent
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($parent)
    {
        Clearance::hasAllPermissionsOrAbort(['Add Album']);
        return view('pages.album.create', ['parent_id' => $parent]);
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
        Clearance::hasAllPermissionsOrAbort(['Add Album']);
        $data = $request->validate(
            [
                'name'      => 'required|string|max:190',
                'parent_id' => 'nullable|integer',
            ]
        );
        if ($data['parent_id'] === 0 or $data['parent_id'] === '0') {
            $data['parent_id'] = null;
        }
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
        Clearance::hasAllPermissionsOrAbort(['Edit Album']);
        return view('pages.album.edit', ['album' => $album]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Album        $album
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Album $album)
    {
        Clearance::hasAllPermissionsOrAbort(['Edit Album']);
        $data = $request->validate(
            [
                'name'      => 'required|string|max:190',
                'parent_id' => 'nullable|integer',
            ]
        );
        $album->update($data);
        return redirect()->route('album.show', ['album' => $album->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Album $album
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Album $album)
    {
        Clearance::hasAllPermissionsOrAbort(['Delete Album']);
        $album->delete();

        return redirect()->route('home');
    }
}
