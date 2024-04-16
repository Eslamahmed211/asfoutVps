@props(['title', 'count', 'color' , 'notClick' => false])

@php
    if (auth()->user()->role == 'user' || auth()->user()->role == 'moderator') {
        $type = 'users';
    } elseif (auth()->user()->role == 'trader') {
        $type = 'trader';
    } elseif (auth()->user()->role == 'admin') {
        $type = 'admin';
    }
@endphp
<div class="item">


    @if ($notClick == false)
        <a href="/{{ $type }}/orders/search?status={{ $title }}" style="color:#626262;">
    @endif


    <div class="count">
        <div class="first">
            <div class="title">{{ $title }}</div>
            <div class="number">{{ $count }}</div>
        </div>

        <div class="icone" style="background-color:{{ $color }}">
            {{ $slot }}
        </div>
    </div>

    @if ($notClick == false)
        </a>
    @endif
</div>
