@extends('layouts.app')

@section('content')
    <div class="container">
        @empty($albums)
            <h2 class="mt-3">Albums</h2>
            <div class="row">
                @foreach( $albums as $album)
                    @include('components.album', ['album' => $album])
                @endforeach
            </div>
        @endif
        @empty($contents)
            <h2 class="mt-3">Content</h2>
            <div class="row">
                @foreach( $contents as $content)
                    @include('components.content', ['content' => $content])
                @endforeach
            </div>
        @endif
    </div>
@endsection
