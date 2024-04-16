<div>
    <nav>
        <div class="right">

            <x-link class="mainLink" title="خدمة العملاء " target="_blank" path="{{ settings('facebook') }}"
                i="fa-brands fa-facebook-messenger"> </x-link>
        </div>

        <div class="center">
            <a href="/home"> <img src="{{ getLogo() }}" alt="logo" class="logo"></a>

        </div>


        <div class="left">

            @if (auth()->user()->role != 'trader')

                <div title="الرسائل" class="icon dropdown dropdown-toggle n" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">

                    <svg class="svg-sm" width="22" height="22" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z"></path>
                        <path
                            d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6">
                        </path>
                        <path d="M9 17v1a3 3 0 0 0 6 0v-1"></path>
                    </svg>

                    <ul class="dropdown-menu">

                        <div class="d-flex">
                            <li onclick="location.href = '/markAll';" class="mb-2" style="border: none"><a
                                    class="markAll" href="/markAll">تعيين الكل كمقروء</a>
                            </li>


                            @if (auth()->user()->role == 'admin')
                                <li onclick="location.href = '/admin/notifications';" class="mb-2 mx-1"
                                    style="border: none"><a class="markAll"> عرض الكل
                                    </a>
                                </li>
                            @else
                                <li onclick="location.href = '/users/notifications';" class="mb-2 mx-1"
                                    style="border: none"><a class="markAll"> عرض الكل
                                    </a>
                                </li>
                            @endif


                        </div>

                        <div id="notfi"></div>
                        @foreach (auth()->user()->unreadNotifications as $Notification)
                            @if ($Notification->data['type'] == 'orderStatus')
                                <li onclick="read(this , '{{ $Notification->id }}')"><a
                                        class="dropdown-item pindding d-flex align-items-center justify-content-between"
                                        href="/users/orders/{{ $Notification->data['order_id'] }}/show">{{ $Notification->data['message'] }}
                                        <span>{{ timeFormat($Notification->created_at) }}</span>
                                    </a>
                                </li>
                            @elseif($Notification->data['type'] == 'paid_withdrow')
                                <li onclick="read(this , '{{ $Notification->id }}')"><a
                                        class="dropdown-item pindding d-flex align-items-center justify-content-between"
                                        href="/users/wallet">{{ $Notification->data['message'] }}
                                        <span>{{ timeFormat($Notification->created_at) }}</span>
                                    </a>
                                </li>
                                @elseif($Notification->data['type'] == 'order_note')
                                <li onclick="read(this , '{{ $Notification->id }}')"><a
                                        class="dropdown-item pindding d-flex align-items-center justify-content-between"
                                        href="/users/orders/{{ $Notification->data['id'] }}/show">{{ $Notification->data['message'] }}
                                        <span>{{ timeFormat($Notification->created_at) }}</span>
                                    </a>
                                </li>
                            @elseif ($Notification->data['type'] == 'user_withdrow' && auth()->user()->role == 'admin')
                                <li onclick="read(this , '{{ $Notification->id }}' , 'admin')"><a
                                        class="dropdown-item pindding d-flex align-items-center justify-content-between"
                                        href="/admin/users/withdraws">{{ $Notification->data['message'] }}
                                        <span>{{ timeFormat($Notification->created_at) }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    <span id="NotificationCount" class="cart_icon_count"
                        style="font-family: Cairo">{{ auth()->user()->unreadNotifications->count() }}</span>
                </div>
            @endif
            @if (auth()->user()->role == 'user' || auth()->user()->role == 'moderator')
                <div class="icon position-relative" title="المفضلة">
                    <a style="color: inherit" href="/users/products/search/?fav=yes"> <svg class="svg-sm" width="22"
                            height="22" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </a>

                    <span id="NotificationCount" class="cart_icon_count"
                        style="font-family: Cairo">{{ favCount() }}</span>

                </div>
            @endif





            <div class="reverse d-flex align-items-end">

                <a class="user-profile dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                    <div class="avatar">{{ $firstTwoCharacters = substr(auth()->user()->name, 0, 2) }}</div>

                    <div class="mx-1 d-lg-block d-none">
                        <p class="user-name">{{ auth()->user()->name }}</p>
                        <p class="role"> @php
                            echo match (auth()->user()->role) {
                                'user' => 'مسوق',
                                'admin' => 'ادمن',
                                'super' => 'خدمة عملاء',
                                'trader' => 'تاجر',
                                'postman' => 'مندوب',
                                'moderator' => 'مودريتور',
                            };
                        @endphp <x-icons.mark></x-icons.mark></p>
                    </div>

                    <ul class="dropdown-menu ">
                        <x-item path="/profile" title=" اعدادات الحساب ">
                            <x-icons.edit></x-icons.edit> </x-item>
                        <x-item path="/logout" title=" تسجيل الخروج "> <x-icons.logout></x-icons.logout> </x-item>
                    </ul>

                </a>

                <div id="menu" class="icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M4 6C4 5.44772 4.44772 5 5 5H19C19.5523 5 20 5.44772 20 6C20 6.55228 19.5523 7 19 7H5C4.44772 7 4 6.55228 4 6Z"
                            fill="currentColor"></path>
                        <path
                            d="M4 18C4 17.4477 4.44772 17 5 17H19C19.5523 17 20 17.4477 20 18C20 18.5523 19.5523 19 19 19H5C4.44772 19 4 18.5523 4 18Z"
                            fill="currentColor"></path>
                        <path
                            d="M5 11C4.44772 11 4 11.4477 4 12C4 12.5523 4.44772 13 5 13H13C13.5523 13 14 12.5523 14 12C14 11.4477 13.5523 11 13 11H5Z"
                            fill="currentColor"></path>
                    </svg>

                </div>
            </div>




        </div>
    </nav>
</div>
