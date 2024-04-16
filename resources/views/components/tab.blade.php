@props(['name', 'title'])

<button {{ $attributes->class(['nav-link']) }} onclick="change('nav-{{ $name }}')"
    id="nav-{{ $name }}-tab" data-bs-toggle="tab" data-bs-target="#nav-{{ $name }}" type="button"
    role="tab" aria-controls="nav-{{ $name }}" aria-selected="true">
    {{ $slot }}
    {{ $title }}
</button>
