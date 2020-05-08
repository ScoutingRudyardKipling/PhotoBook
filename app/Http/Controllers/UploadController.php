<?php

namespace App\Http\Controllers;

use App\Facades\Clearance;
use App\Models\Content;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig;

class UploadController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function storeHandler(Request $request)
    {
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
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAjax(Request $request)
    {
        Clearance::hasAllPermissionsOrAbort(['Add Content']);

        $request['parent_id'] = $request->header('parent_id');

        $request->validate(
            [
                'content'   => 'required|file|max:20000',
                'parent_id' => 'required|integer',
            ]
        );
        try {
            $this->storeHandler($request);
            return response()->json(
                [
                    'successful' => true,
                    'message'    => 'file uploaded',
                ]
            );
        } catch (FileNotFoundException $e) {
            return response()->json(
                [
                    'successful' => false,
                    'message'    => 'FileNotFoundException',
                ],
                422
            );
        } catch (DiskDoesNotExist $e) {
            return response()->json(
                [
                    'successful' => false,
                    'message'    => 'DiskDoesNotExist',
                ],
                500
            );
        } catch (FileDoesNotExist $e) {
            return response()->json(
                [
                    'successful' => false,
                    'message'    => 'FileDoesNotExist',
                ],
                500
            );
        } catch (FileIsTooBig $e) {
            return response()->json(
                [
                    'successful' => false,
                    'message'    => 'FileIsTooBig',
                ],
                413
            );
        }
    }
}
