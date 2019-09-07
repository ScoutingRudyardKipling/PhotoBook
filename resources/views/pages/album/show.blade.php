@extends('layouts.page')

@section('content')
    <h1 class="mt3">
        {{$album->name}}
    </h1>
    @if (count($albums) > 0)
        <h2 class="mt-3">Albums</h2>
        <div class="row">
            @foreach( $albums as $album)
                @include('components.album', ['album' => $album])
            @endforeach
        </div>
    @endif
    @if (count($contents) > 0)
        <h2 class="mt-3">Content</h2>
        <div class="row js-gallery gutters-tiny">
            @foreach( $contents as $content)
                @include('components.content', ['content' => $content])
            @endforeach
        </div>
    @endif
    @if(count($albums) === 0 && count($contents) === 0 )
        <h2 class="mt-3">
            Leeg album
        </h2>
    @endif
@endsection
