<?php

namespace App\Http\Controllers;

use App\Facades\Clearance;
use App\Models\Content;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
        if (Clearance::hasAllPermissions(['Add Content']) === false) {
            return response()->json(
                [
                    'successful' => false,
                    'message'    => 'you do not have the permissions to upload.',
                ],
                403
            );
        }

        $request['parent_id'] = $request->header('parent_id');

        $validator = Validator::make(
            $request->all(),
            [
                'content'   => 'required|file|max:' . (config('medialibrary.max_file_size') / 1024),
                'parent_id' => 'required|integer',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'successful' => false,
                    'message'    => $validator->errors()->first(),
                ],
                422
            );
        }

        try {
            $this->storeHandler($request);
            return response()->json(
                [
                    'successful' => true,
                    'message'    => 'file uploaded',
                ],
                201
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
        } catch (Exception $e) {
            return response()->json(
                [
                    'successful' => false,
                    'message'    => 'PHP threw an internal error.',
                ],
                500
            );
        }//end try
        return response()->json(
            [
                'successful' => false,
                'message'    => 'Something went wrong.',
            ],
            500
        );
    }
}
