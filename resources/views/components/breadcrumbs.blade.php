<div>
    @php
        $breadCrumbs = collect();
        $i = $album->parent;
        $iterator = 1;
        while (!empty($i)) {
            $breadCrumbs->prepend(['id' => $i->id, 'name' => $i->name]);
            $i = $i->parent;
            $iterator++;
        }
    @endphp
    <a href="{{route('home')}}">Home</a>
    @if ($breadCrumbs->isNotEmpty())
        >
    @endif
    @foreach($breadCrumbs as $crumb)
        <a href="{{route("album.show", [$crumb['id']])}}"> {{$crumb['name']}}</a>
        @if (! $loop->last)
            >
        @endif
    @endforeach
</div>
