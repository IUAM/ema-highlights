@if (count($items) > 0)
    <ul class="{{ $is_open ? 'active' : '' }}">
    @foreach ($items as $item)
        <li>
            <a href="{{ $item['href'] }}" class="{{ $item['is_current'] ? 'active' : '' }}">{{ $item['title'] }}</a>
            @if (isset($item['children']))
                @include('partials.sidebar', [
                    'items' => $item['children'],
                    'is_open' => $item['is_open'],
                ])
            @endif
        </li>
    @endforeach
    </ul>
@endif
