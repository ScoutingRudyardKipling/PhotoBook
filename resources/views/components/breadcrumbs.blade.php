<div>
    @php
        $breadBrumbArray = collect();
        $i = $album->parent;
        $iterator = 1;
        while (!empty($i)) {
            $breadBrumbArray->prepend(['id' => $i->id, 'name' => $i->name]);
            $i = $i->parent;
            $iterator++;
        }
    @endphp
    <a href="{{route('home')}}">Home</a>
    @if ($breadBrumbArray->isNotEmpty())
        >
    @endif
    @foreach($breadBrumbArray as $crumb)
        <a href="{{route("album.show", [$crumb['id']])}}"> {{$crumb['name']}}</a>
        @if (! $loop->last)
            >
        @endif
    @endforeach
</div>
