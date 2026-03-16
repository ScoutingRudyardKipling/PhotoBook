@extends('layouts.page')

@section('content')
    @include('components.breadcrumbs', ['album' => $album])
    <h1 class="page-title">{{ $album->name }}</h1>
    @include('components.actions', ['id' => $album->id])

    @if (count($albums) > 0)
        <p class="section-label">{{ __('app.Albums') }}</p>
        <div class="row">
            @foreach ($albums as $album)
                @include('components.album', ['album' => $album])
            @endforeach
        </div>
    @endif

    @if (count($contents) > 0)
        <p class="section-label">{{ __('app.Content') }}</p>
        <div class="row js-gallery gutters-tiny">
            @foreach ($contents as $content)
                @include('components.content', ['content' => $content])
            @endforeach
        </div>
    @endif

    @if (count($albums) === 0 && count($contents) === 0)
        <div class="empty-state">
            <span class="empty-icon">📷</span>
            <p>{{ __('app.Empty') }} {{ __('app.album') }}</p>
        </div>
    @endif
@endsection
