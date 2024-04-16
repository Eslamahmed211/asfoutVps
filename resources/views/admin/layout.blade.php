<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    @yield('title')


    @include('admin.inc.head')

    @yield('css')



</head>

<body>

    <div class="layout"></div>

    <x-layout.nav></x-layout.nav>




    @if (auth()->user()->role == 'admin')
        <x-admin.aside></x-admin.aside>
    @elseif(auth()->user()->role == 'user' || auth()->user()->role == 'moderator')
        <x-users.aside></x-users.aside>
    @elseif(auth()->user()->role == 'trader')
        <x-trader.aside></x-trader.layout.aside>
    @endif


    <x-colors> </x-colors>


    <div class="content mt-0">

        <div class="path">
            @yield('path')
        </div>


        @yield('content')
    </div>


    <div>

        <footer>
            <div>
                @php
                    $pages = App\Models\page::get();
                @endphp
                @foreach ($pages as $page)
                    <a target="_blank" href="/pages/{{ $page->slug }}">{{ $page->title }}</a>
                @endforeach

            </div>
            <div class="brands">
                @if(!empty(settings('facebook')))
                <a target="_blank" href="{{ settings('facebook') }}"><i class="fa-brands fa-facebook fa-fw"></i></a>
            @endif

            @if(!empty(settings('youtube')))
                <a target="_blank" href="{{ settings('youtube') }}"><i class="fa-brands fa-youtube fa-fw"></i></a>
            @endif

            @if(!empty(settings('instagram')))
                <a target="_blank" href="{{ settings('instagram') }}"><i class="fa-brands fa-instagram fa-fw"></i></a>
            @endif

            @if(!empty(settings('whatsapp')))
                <a target="_blank" href="https://api.whatsapp.com/send?phone=2{{ settings('whatsapp') }}"><i class="fa-brands fa-whatsapp fa-fw"></i></a>
            @endif

            @if(!empty(settings('phone')))
                <a href="tel:{{ settings('phone') }}"><i class="fa-solid fa-phone fa-fw"></i></a>
            @endif

            @if(!empty(settings('email')))
                <a href="mailto:{{ settings('email') }}"><i class="fa-regular fa-envelope fa-fw"></i></a>
            @endif

            </div>
        </footer>
    </div>

    @include('admin.inc.scripts')
    @include('admin/inc/errors')





    @yield('js')

</body>

</html>
