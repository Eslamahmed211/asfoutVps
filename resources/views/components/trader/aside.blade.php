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

        <li class="product_percent">
            <a href="/trader/product_percent"> <i style="font-size: 18px ; margin-left: 8px"
                    class="fa-solid fa-percent"></i> تسليمات المنتجات </a>
        </li>

        <li class="products">
            <a href="/trader/products"> <svg width="22" height="22" fill="currentColor"
                    class="Icon__StyledIcon-sc-178dh1b-0 denEXe" viewBox="0 0 24 24"
                    preserveAspectRatio="xMidYMid meet">
                    <path
                        d="M11.965 2.127L7.092 7H5.148l1.07-1.957L8.265 3H5.75a1 1 0 00-.863.496l-1.75 3A1.003 1.003 0 003 7v12c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V7c0-.177-.048-.35-.137-.504l-1.75-3A1 1 0 0018.25 3h-2.584l2 2h.01l.013.023L18.842 7h-2.006l-4.871-4.873zm0 2.828L14.008 7H9.922l2.043-2.045zM5 9h14v10H5V9zm4 2v2h6v-2H9z">
                    </path>
                </svg>
                المنتجات </a>
        </li>

        <li class="orders">
            <a href="/trader/orders"> <svg width="22" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg> الطلبات </a>
        </li>

        <li class="invoices">
            <a href="/trader/invoices"> <svg width="20" xmlns="http://www.w3.org/2000/svg" width="24px"
                    height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text h-5 w-5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg> الفواتير </a>

        </li>


        <li class="wallet">
            <a href="{{ asset('trader/wallet') }}"> <svg width="22" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                </svg>
                المحفظة <small id="cart"> &nbsp ( {{ auth()->user()->wallet }} ) </small> </a>

        </li>




    </ul>
</aside>
