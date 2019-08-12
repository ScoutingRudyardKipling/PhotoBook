@extends('layouts.page')

@section('content')
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
        <div class="row">
            @foreach( $contents as $content)
                @include('components.content', ['content' => $content])
            @endforeach
        </div>
    @endif
@endsection
