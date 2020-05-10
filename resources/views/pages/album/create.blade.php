@extends('layouts.page')

@section('content')
    {{--    @dd($__data)--}}
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-6">
            <div class="card">
                <div class="card-header">
                    @if($parent_id != 0)
                        @include('components.breadcrumbs', ['album' => \App\Models\Album::find($parent_id), 'showCurrent' => true])
                    @else
                        <a href="{{route('home')}}">{{__('app.Dashboard')}}</a>
                    @endif
                    <h1 class="card-title">{{__('app.action.Create')}} {{__('app.album')}}</h1>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('album.store') }}">
                        @include('pages.album.fields', ['parent_id' => $parent_id])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
