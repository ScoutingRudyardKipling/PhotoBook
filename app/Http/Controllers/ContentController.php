<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Storage;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('pages.content.show');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('pages.content.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'content'  => 'required|file|max:20000',
                'album_id' => 'nullable|integer',
            ]
        );
        $content = Content::create(
            [
                'name'      => $request->file('content')->getClientOriginalName(),
                'parent_id' => $request->get('album_id'),
            ]
        );
        Storage::disk('temp')
            ->put(
                $request->file('content')->getClientOriginalName(),
                File::get($request->file('content'))
            );
        $content->addMedia(
            Storage::disk('temp')->path(
                $request->file('content')->getClientOriginalName()
            )
        )->toMediaCollection();

        return redirect()->route(
            'album.show',
            [
                'album' => $request->get('album_id'),
            ]
        );
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
        // TODO: Implement
        unset($content);
        return view('pages.content.show');
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
        // TODO: Implement
        unset($content);
        return view('pages.content.show');
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
        // TODO: Implement
        unset($content);
        unset($request);
        return redirect()->route('content.show');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Content $content
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Content $content)
    {
        // TODO: Implement
        unset($content);
        return redirect()->route('content.show');
    }
}
