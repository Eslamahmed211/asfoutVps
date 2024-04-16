@props(['path', 'title'])

<li {{$attributes->merge()}}>
    <a class="dropdown-item" href="{{ $path }}">
        @if (trim($slot) != '')
            {{ $slot }}
        @else
            <x-icons.dot></x-icons.dot>
        @endif
        {{ $title }}
    </a>
</li>
