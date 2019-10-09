<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Storage;

class ContentController extends Controller
{
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
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'content'   => 'required|file|max:20000',
                'parent_id' => 'required|integer',
            ]
        );
        $content = Content::create(
            [
                'name'      => $request->file('content')->getClientOriginalName(),
                'parent_id' => $request->get('parent_id'),
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
                'album' => $request->get('parent_id'),
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
        $content->delete();

        return redirect()->route('home');
    }
}
