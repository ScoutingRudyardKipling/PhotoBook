<?php

namespace App\Http\Controllers;

use App\Facades\Clearance;
use App\Models\Album;
use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Clearance::hasAllPermissionsOrAbort(['Add Content']);

        return view('pages.content.create');
    }



    /**
     *
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function store(Request $request)
    {
        Clearance::hasAllPermissionsOrAbort(['Add Content']);

        $request->validate(
            [
                'content'   => 'required|file|max:20000',
                'parent_id' => 'required|integer',
            ]
        );
        (new UploadController())->storeHandler($request);

        return redirect()->route(
            'album.show',
            [
                'album' => $request->get('parent_id'),
            ]
        );
    }

    /**
     * @param \App\Models\Album $album
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadView(Album $album)
    {
        Clearance::hasAllPermissionsOrAbort(['Add Content']);

        return view('pages.content.upload', ['album' => $album]);
    }



    /**
     * Display the specified resource.
     *
     * @param \App\Models\Content $content
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Content $content)
    {
        return view('pages.content.show', ['content' => $content]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Content $content
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Content $content)
    {
        Clearance::hasAllPermissionsOrAbort(['Edit Content']);

        return view('pages.content.edit', ['content' => $content]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Content      $content
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Content $content)
    {
        Clearance::hasAllPermissionsOrAbort(['Edit Content']);

        $data = $request->validate(
            [
                'name'      => 'required|string|max:190',
                'parent_id' => 'required|integer',
            ]
        );
        $content->update($data);
        return redirect()->route('content.show', ['content' => $content->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Content $content
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Content $content)
    {
        Clearance::hasAllPermissionsOrAbort(['Delete Content']);

        $content->delete();

        return redirect()->route('home');
    }
}
