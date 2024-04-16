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

        @can('has', 'users_show')
            <li class="users ">
                <a class="dropdown" href="#" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div><svg width="22" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg> المستخدمين <x-icons.mark></x-icons.mark> </div>
                </a>

                <ul class="dropdown-menu dr">

                    <x-item path="/admin/users" title="عرض المستخدمين"></x-item>
                    @can('has', 'users_withdraws')
                        <x-item path="/admin/users/withdraws" title="طلبات السحب"></x-item>
                    @endcan
                </ul>
            </li>
        @endcan


        @can('has', 'products_show')
            <li class="products">
                <a class="dropdown" href="#" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div> <svg width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z">
                            </path>
                        </svg> المنتجات <x-icons.mark></x-icons.mark> </div>
                </a>

                <ul class="dropdown-menu dr">

                    <x-item path="/admin/categories" title="التصنيفات "></x-item>
                    <x-item path="/admin/products" title="المنتجات"></x-item>


                </ul>
            </li>
        @endcan

        @can('has', 'orders_show')
            <li class="orders ">
                <a class="dropdown" href="#" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div> <svg width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z">
                            </path>
                        </svg> الطلبات <x-icons.mark></x-icons.mark> </div>
                </a>

                <ul class="dropdown-menu dr">

                    @can('has', 'orders_show')
                        <x-item path="/admin/orders" title=" كل الطلبات "></x-item>
                    @endcan

                    @can('has', 'order_commissions')
                        <x-item path="/admin/orders/order_commissions" title=" عمولات الطلبات "></x-item>
                        <x-item path="/admin/orders/ExpensesAndCommissionsHistory" title=" خصم واضافة عمولات  "></x-item>
                    @endcan


                </ul>
            </li>
        @endcan
        @can('has', 'invoices')

        <li class="invoices">
            <a href="/admin/invoices"> <svg width="20" xmlns="http://www.w3.org/2000/svg" width="24px"
                    height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text h-5 w-5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg> الفواتير </a>

        </li>
        @endcan

        @can('has', 'ads')
            <li class="ads ">
                <a href="/admin/ads"> <svg width="20" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z">
                        </path>
                    </svg> البنرات الاعلانية </a>

            </li>
        @endcan

        @can('has', 'pages')
            <li class="pages">
                <a href="/admin/pages"> <svg width="20" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 8.25V18a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 18V8.25m-18 0V6a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 6v2.25m-18 0h18M5.25 6h.008v.008H5.25V6zM7.5 6h.008v.008H7.5V6zm2.25 0h.008v.008H9.75V6z">
                        </path>
                    </svg> الصفحات الثابتة </a>

            </li>
        @endcan

        @can('has', 'reports')
            <li class="reports">
                <a class="dropdown" href="#" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div> <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-calendar ">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg> التقارير <x-icons.mark></x-icons.mark> </div>
                </a>

                <ul class="dropdown-menu dr">
                    <x-item path="/admin/reports/user_commissions" title="عمولات المستخدمين"></x-item>
                    <x-item path="/admin/reports/products_stock_in_orders" title="توزيع استوك المنتجات"></x-item>
                    <x-item path="/admin/reports/marketer_profits_losses" title="ارباح وخسائر المسوقين"></x-item>
                </ul>
            </li>
        @endcan


        @can('has', 'settings')
            <li class="settings ">
                <a href="/admin/settings"> <svg width="20" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg> الاعدادات </a>

            </li>
        @endcan



    </ul>
</aside>
