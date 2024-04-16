<aside id="aside">
    <ul id="links">

        <a href="/home" class="p-0"> <img src="{{ getLogo() }}" alt="logo" class="logo d-lg-none"></a>

        <li class="home">
            <a href="/home"> <svg width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25">
                    </path>
                </svg> الرئيسية </a>

        </li>


        @if (auth()->user()->role == 'user')
            <li class="moderators ">
                <a class="dropdown" href="#" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div><svg width="22" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg> الموديتور <x-icons.mark></x-icons.mark> </div>
                </a>

                <ul class="dropdown-menu dr">

                    <x-item path="/users/moderators" title="عرض الموديتور"></x-item>
                    <x-item path="/users/moderators/withdraws" title="طلبات السحب"></x-item>


                </ul>
            </li>
        @endif

        <li class="products">
            <a href="/users/products"> <svg width="22" height="22" fill="currentColor"
                    class="Icon__StyledIcon-sc-178dh1b-0 denEXe" viewBox="0 0 24 24"
                    preserveAspectRatio="xMidYMid meet">
                    <path
                        d="M11.965 2.127L7.092 7H5.148l1.07-1.957L8.265 3H5.75a1 1 0 00-.863.496l-1.75 3A1.003 1.003 0 003 7v12c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V7c0-.177-.048-.35-.137-.504l-1.75-3A1 1 0 0018.25 3h-2.584l2 2h.01l.013.023L18.842 7h-2.006l-4.871-4.873zm0 2.828L14.008 7H9.922l2.043-2.045zM5 9h14v10H5V9zm4 2v2h6v-2H9z">
                    </path>
                </svg> كل المنتجات </a>

        </li>

        <li class="orders">
            <a href="/users/orders"> <svg width="22" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg> كل الطلبات </a>

        </li>


        <li class="order_commissions">
            <a href="/users/orders/order_commissions"> <svg width="22" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                </svg> عمولات الطلبات </a>

        </li>

        <li class="cart">
            <a href="{{ asset('users/cart') }}"> <svg width="22" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg> سلة التسوق  <span id="cart"> &nbsp ( {{ cartCount() }} ) </span> </a>

        </li>

        <li class="wallet">
            <a href="{{ asset('users/wallet') }}"> <svg width="22" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                </svg>
                المحفظة <small id="cart">  &nbsp ( {{ auth()->user()->wallet }} ) </small> </a>

        </li>




    </ul>
</aside>
