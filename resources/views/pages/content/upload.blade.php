@extends('layouts.page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card">
                <div class="card-header">
                    @include('components.breadcrumbs', ['album' => $album, 'showCurrent' => true])
                    <h1 class="card-title">{{__('app.action.Upload')}} {{__('app.to the')}} {{__('app.album')}}: {{$album->name}}</h1>
                </div>
                <div class="card-body">
                    <div id="drag-drop-area">{{__('app.enable-javascript')}}</div>
                    <div class="form-group float-right pt-3">
                        <a id="submit" class="btn btn-primary disabled" href="{{route('album.show', ['album' => $album->id])}}" role="button">{{__('app.action.Submit')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('css')
    <link href="https://transloadit.edgly.net/releases/uppy/v1.13.0/uppy.min.css" rel="stylesheet">
@endsection
@section('scripts')
    <script src="https://transloadit.edgly.net/releases/uppy/v1.13.0/uppy.min.js"></script>
    <script>
    var uppy = Uppy.Core({
      debug: false,
      autoProceed: false,
      restrictions: {
        maxFileSize: {{config('medialibrary.max_file_size')}},
        // minNumberOfFiles: 1,
        // maxNumberOfFiles: 1,
        //'image/*', 'video/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf'
        allowedFileTypes: ['image/*',]
      }
    })
      .use(Uppy.Dashboard, {
        inline: true,
        target: '#drag-drop-area',
        browserBackButtonClose: false,
        replaceTargetContent: true,
        showProgressDetails: true,
        waitForThumbnailsBeforeUpload: true,
        proudlyDisplayPoweredByUppy: false,
        theme: 'auto',
      })
      .use(Uppy.XHRUpload, {
        limit: 10,
        endpoint: '/content/upload/action',
        formData: true,
        bundle: false,
        fieldName: 'content',
        headers: {
          //'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // from <meta name="csrf-token" content="{{ csrf_token() }}">
          'X-CSRF-TOKEN': "{{ csrf_token() }}",
          'Parent-Id': {{$album->id}},
        },
        getResponseError(responseText, response) {
          return new Error(JSON.parse(responseText).message)
        }
      });
    uppy.on('complete', (result) => {
      if (result.failed[0] === undefined) {
        document.getElementById("submit").classList.remove('disabled');
      }
    })
    </script>
@endsection
