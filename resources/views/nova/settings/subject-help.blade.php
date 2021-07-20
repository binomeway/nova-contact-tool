<p>{{ $help }}</p>

<p>Variables:</p>
<ul>
    @foreach($vas as $var)
        <li>{{ $var }}</li>
    @endforeach
</ul>
