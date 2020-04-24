@extends('layouts.page')

@section('content')
    @include('components.breadcrumbs', ['album' => $album])
    <h1 class="mt-2">
        {{$album->name}}
    </h1>
    @include('components.actions', ['id' => $album->id])

    @if (count($albums) > 0)
        <h3 class="mt-3">{{__('app.Albums')}}</h3>
        <div class="row">
            @foreach( $albums as $album)
                @include('components.album', ['album' => $album])
            @endforeach
        </div>
    @endif
    @if (count($contents) > 0)
        <h3 class="mt-3">{{__('app.Content')}}</h3>
        <div class="row js-gallery gutters-tiny">
            @foreach( $contents as $content)
                @include('components.content', ['content' => $content])
            @endforeach
        </div>
    @endif
    @if(count($albums) === 0 && count($contents) === 0 )
        <div class="card mt-3">
            <div class="card-body">
                <div class="row justify-content-center">
                    <h4 class="mt-2">
                        {{__('app.Empty')}} {{__('app.album')}}
                    </h4>
                </div>
            </div>
        </div>

    @endif
@endsection
