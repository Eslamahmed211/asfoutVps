@props(['icon', 'title', 'dis', 'path', 'type' ])

<div class="tool">
    <div class="tool_icon">
        <img src="{{ asset("assets/admin/icons/$icon") }}" alt={{ $title }}>
    </div>
    <div class="title">{{ $title }}</div>

    <div class="dis">{{ $dis }}</div>

    @if (isset($type))
        <x-admin.forms.buttonLink style="padding: 0.4rem 1.2rem;" title="تعديل"
            path="/{{$type}}/settings/{{ $path }}"></x-admin.forms.buttonLink>
    @else
        <x-admin.forms.buttonLink style="padding: 0.4rem 1.2rem;" title="تعديل"
            path="/admin/settings/{{ $path }}"></x-admin.forms.buttonLink>
    @endif


</div>
