@props(['path', 'title', 'i'])




<a {{ $attributes->class('xbutton')->merge() }} href="{{ $path }}">
    {{ $title }} @isset($i)
        <i class="{{ $i }} mx-1"></i>
    @endisset {{ $slot }}
</a>
