<?php

namespace App\Http\Controllers;

use App\Facades\Clearance;
use App\Models\Album;
use App\Models\Content;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AlbumController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param int|null $parent
     *
     * @return Application|Factory|View
     */
    public function create($parent)
    {
        Clearance::hasAllPermissionsOrAbort(['Add Album']);
        return view('pages.album.create', ['parent_id' => $parent]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        Clearance::hasAllPermissionsOrAbort(['Add Album']);
        $request->validate(
            [
                'name'      => 'required|string|max:190',
                'parent_id' => 'nullable|integer',
            ]
        );
        $parentId = $request->input('parent_id');
        $album    = Album::create([
            'name'      => $request->input('name'),
            'parent_id' => ($parentId === 0 || $parentId === '0') ? null : $parentId,
        ]);
        return redirect()->route('album.show', ['album' => $album->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param Album $album
     *
     * @return Factory|View
     */
    public function show(Album $album)
    {
        $albums   = $album->childAlbums()->orderBy('name', 'asc')->get();
        $contents = $album->contents()->orderBy('name', 'asc')->get();

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
     * Get all photos for the slideshow (including sub-albums).
     *
     * @param Album $album
     *
     * @return JsonResponse
     */
    public function allPhotos(Album $album)
    {
        $data = $album->getAllContents()->map(
            function (Content $content) {
                return [
                    'href'  => $content->getUrl(),
                    'type'  => 'image',
                    'title' => $content->name,
                ];
            }
        );

        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Album $album
     *
     * @return Factory|View
     */
    public function edit(Album $album)
    {
        Clearance::hasAllPermissionsOrAbort(['Edit Album']);
        return view('pages.album.edit', ['album' => $album]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Album        $album
     *
     * @return RedirectResponse
     */
    public function update(Request $request, Album $album)
    {
        Clearance::hasAllPermissionsOrAbort(['Edit Album']);
        $request->validate(
            [
                'name'            => 'required|string|max:190',
                'parent_id'       => 'nullable|integer',
                'featured-select' => 'nullable|string|regex:/[a-zA-Z]{5,}\-\d{1,}/i',
            ]
        );
        $updateData     = [
            'name'      => $request->input('name'),
            'parent_id' => $request->input('parent_id'),
        ];
        $featuredSelect = $request->input('featured-select');
        if (is_string($featuredSelect)) {
            $featured                    = explode('-', $featuredSelect);
            $updateData['featured_type'] = 'App\\Models\\' . $featured[0];
            $updateData['featured_id']   = $featured[1];
        }
        $album->update($updateData);
        return redirect()->route('album.show', ['album' => $album->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Album $album
     *
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Album $album)
    {
        Clearance::hasAllPermissionsOrAbort(['Delete Album']);
        $album->delete();

        return redirect()->route('home');
    }
}
