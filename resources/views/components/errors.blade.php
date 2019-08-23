@if ($errors->any())
    <div class="alert alert-danger">
        <p class="mb-1"><b>Een aantal velden voldoen niet aan onze validatie:</b></p>
        <ul class="mb-0 pl-4">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif