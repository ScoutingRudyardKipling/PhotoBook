@extends('layouts.page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                     @include('components.breadcrumbs', ['album' => $album, 'showCurrent' => true])
                    <h1 class="card-title">{{__('app.action.Upload')}} {{__('app.to the')}} {{__('app.album')}}: {{$album->name}}</h1>
                </div>
                <div class="card-body">
                    <div id="drag-drop-area">{{__('app.enable-javascript')}}</div>
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
    var uppy = Uppy.Core()
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
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // from <meta name="csrf-token" content="{{ csrf_token() }}">
          'parent_id':{{$album->id}},
        },
      });
    uppy.on('complete', (result) => {
      console.log('Upload complete! Go back <a href="/">bla</a>We’ve uploaded these files:', result.successful)
    })
    </script>
@endsection
