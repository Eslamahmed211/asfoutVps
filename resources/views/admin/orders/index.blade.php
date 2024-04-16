@extends('admin.layout')


@section('css')
    <style>
        .swal2-html-container {
            margin: 10px 10px !important;
        }

        .swal2-title {
            line-height: 32px;
            font-size: 22px !important;
            margin-bottom: 0px !important;
        }
    </style>
@endsection

@section('content')
    <x-searchForm withExel action="{{ url('admin/orders/search') }}">

        @php
            !empty($_GET['reference']) ? ($reference = $_GET['reference']) : ($reference = '');
            !empty($_GET['trackingNumber']) ? ($trackingNumber = $_GET['trackingNumber']) : ($trackingNumber = '');
            !empty($_GET['name']) ? ($name = $_GET['name']) : ($name = '');
            !empty($_GET['mobile']) ? ($mobile = $_GET['mobile']) : ($mobile = '');
            !empty($_GET['city_name']) ? ($city_name = $_GET['city_name']) : ($city_name = []);
            !empty($_GET['status']) ? ($status = $_GET['status']) : ($status = '');
            !empty($_GET['date']) ? ($date = $_GET['date']) : ($date = '');
            !empty($_GET['withModerators']) ? ($withModerators = $_GET['withModerators']) : ($withModerators = '');
            !empty($_GET['track']) ? ($track = $_GET['track']) : ($track = '');
            !empty($_GET['has_track']) ? ($has_track = $_GET['has_track']) : ($has_track = '');
        @endphp

        <div data-title="كود الاوردر">
            <x-admin.forms.input name="reference" value="{{ $reference }}" lable_title="كود الاوردر">
            </x-admin.forms.input>
        </div>


    <div data-title="رقم الشحنة">
            <x-admin.forms.input name="trackingNumber" value="{{ $trackingNumber }}" lable_title="رقم الشحنة">
            </x-admin.forms.input>
        </div>

        <div data-title="اسم العميل">
            <x-admin.forms.input name="name" value="{{ $name }}" lable_title="اسم العميل">
            </x-admin.forms.input>
        </div>

        <div data-title="رقم العميل">
            <x-admin.forms.input name="mobile" value="{{ $mobile }}" lable_title="رقم العميل">
            </x-admin.forms.input>
        </div>




        <div data-title="المحافظة">
            <label for="reference" class="mb-2"> المحافظة </label>
            <select multiple name="city_name[]" class="js-example-basic-single">

                <option @selected($city_name == []) value="كل">كل المحافظات</option>

                @foreach ($cities as $city)
                    <option @selected(in_array($city->name, $city_name)) value="{{ $city->name }}">
                        {{ $city->name }} </option>
                @endforeach

            </select>
        </div>



        <div>
            <label for="reference" class="mb-2"> حالة الطلب </label>

            <select name="status" class="js-example-basic-single">

                <option value="">كل الحالات</option>

                {{-- @foreach (ALL_STATUS() as $state)
                    <option @selected($status == $state) value="{{ $state }}">{{ $state }}</option>
                @endforeach --}}

                @foreach (ALL_STATUS() as $state)
                    <option  value="{{ $state }}">{{ $state }}</option>
                @endforeach

            </select>
        </div>



        <div data-title="المسوق">

            <label for="reference" class="mb-2"> المسوق </label>

            <select name="user_id" class="js-example-tags w-100">
                <option value> كل المسوقين</option>

                @if (isset($user))
                    <option selected value="{{ $user->id }}"> {{ $user->name }}</option>
                @endif

            </select>


        </div>



        <div dir="ltr " class="mb-3">

            <label for="date" class="mb-2"> تاريخ محدد </label>


            <input name="date" class="date" type="text" value="{{ $date }}" lable_title="اختار التاريخ">
        </div>

        <div data-title="تتبع">

            <label for="track" class="mb-2"> نوع التاريخ </label>


            <select name="track">

                <option @selected($track == 'الطلبات') value="الطلبات">انشاء الطلب</option>
                <option @selected($track == 'تتبع') value="تتبع">شحن الطلب</option>

            </select>

        </div>


        <div data-title="يحتوي علي كود الشحنة ">

            <label for="has_track" class="mb-2"> يحتوي علي كود الشحنة </label>

            <select name="has_track">


                <option @selected($has_track == 'جميع الطلبات') value="">جميع الطلبات</option>
                <option @selected($has_track == 'نعم') value="نعم">نعم</option>
                <option @selected($has_track == 'لا') value="لا"> لا</option>

            </select>

        </div>





    </x-searchForm>




    <div class="actions">

        <div class="contnet-title"> {{ $status ? $status : ' جميع الطلبات  ' }} ( {{ $count }} ) </div>

        <div class="d-flex align-items-center gap-2">

            <x-searchBtn></x-searchBtn>

            @can('has', 'order_action')
                <x-dropdown title="الأوامر">

                    <x-item onclick="change_bulk_orders_status()" path='#' title="تغير حالة مجموعة ايدهات">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="#667085" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                        </svg>

                    </x-item>

                    @if ($status == 'تم المراجعة')
                        <x-item onclick="to_turbo()" path='#' title="رفع علي شركة الشحن">


                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="#667085" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>

                        </x-item>
                    @endif

                    @if ($status == 'قيد الانتظار')
                        <x-item onclick="check_still_witting()" path='#' title="فحص طلبات الانتظار">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>

                        </x-item>
                    @endif

                    {{-- @if (in_array($status, zero_orders())) --}}
                    <x-item onclick="chnage_order_status()" path='#' title="تغير حالة الطلبات المحددة">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="#667085" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                        </svg>
                    </x-item>
                    {{-- @endif --}}

                    <x-item onclick="print()" path='#' title="طباعة بوليصة الشحن">
                        <svg data-v-24dee194="" xmlns="http://www.w3.org/2000/svg" style="width: 16px !important"
                            viewBox="0 0 24 24" fill="none" stroke="#667085" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-file h-4 w-4">
                            <path data-v-24dee194="" d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                            <polyline data-v-24dee194="" points="13 2 13 9 20 9"></polyline>
                        </svg>
                    </x-item>

                    <x-item onclick="required_producst()" path='#' title="عرض المنتجات المطلوبة">
                        <svg data-v-24dee194="" xmlns="http://www.w3.org/2000/svg" style="width: 16px !important"
                            viewBox="0 0 24 24" fill="none" stroke="#667085" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-file h-4 w-4">
                            <path data-v-24dee194="" d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                            <polyline data-v-24dee194="" points="13 2 13 9 20 9"></polyline>
                        </svg>
                    </x-item>



                </x-dropdown>
            @endcan



        </div>

    </div>

    <div class="status">
        <x-link @class(['active' => $status == '']) path="/admin/orders" title="كل الطلبات"></x-link>

        @foreach (ALL_STATUS() as $state)
            <x-link @class(['active' => $status == $state]) path="/admin/orders/search?status={{ $state }}"
                title="{{ $state }}">
                @if (in_array($state, [
                        'قيد المراجعة',
                        'تم المراجعة',
                        'جاهز للتغليف',
                        'جاري التجهيز للشحن',
                        'محاولة تانية',
                        'طلب استرجاع',
                    ]))
                    ({{ count_by_status([$state]) }})
                @endif
            </x-link>
        @endforeach
    </div>

    <div class="contnet-title mt-2" id="selected_count_parant" style="display: none"> تم تحديد ( <span
            id="selected_count">0</span> ) </div>


    @if ($status == 'جاهز للتغليف')
        <input type="hidden" id="order_id">
        <div class="actions justify-content-start">

            <div class="contnet-title"> اختبار الطلبات </div>

            <div id="search">
                <input type="text" id="reference" style="background: white;min-width: 330px;">
            </div>

        </div>
    @endif

    @if ($status == 'طلب استرجاع')
        <input type="hidden" id="order_id">
        <div class="actions justify-content-start">

            <div class="contnet-title">استرجاع الطلبات</div>

            <div id="search">
                <input type="text" id="reference" style="background: white;min-width: 330px;">
            </div>

        </div>
    @endif


    <div class="position-relative ">


        <div id="loader">
            <i class="fa-solid fa-spinner fa-spin"></i>
        </div>

        <div class="tableSpace" style="min-height:60vh">
            <table>
                <thead>
                    <tr>

                        <th>#</th>
                        {{-- @if ($status != '') --}}
                        <th><input onchange="allUsers(this)" type="checkbox" class="not"></th>
                        {{-- @endif --}}

                        <th>الكود</th>
                        <th>بواسطة</th>
                        <th>الطلبات</th>
                        <th>الاسم</th>
                        <th>المحافظة</th>
                        <th>الحالة</th>
                        <th>الاجمالي</th>
                        <th>تاريخ الانشاء</th>
                        <th>ملاحظات</th>
                        <th>الاجراءات</th>
                    </tr>
                </thead>
                <tbody class="clickable">

                    @forelse ($orders as $order)
                        @php
                            $OrderData = getOrderData($order->details);
                        @endphp
                        {{-- @if ($status != '') @endif --}}
                        <tr style="cursor: pointer">
                            <td> {{ ($orders->currentpage() - 1) * $orders->perpage() + $loop->index + 1 }} </td>

                            {{-- @if ($status != '') --}}
                            <td>
                                <input type="checkbox" onchange="check(this)" class="not UsersCheckBox"
                                    value="{{ $order->reference }}">
                            </td>
                            {{-- @endif --}}
                            <td>
                                <a target="_blank" href="/admin/orders/{{ $order->id }}/show">{{ $order->reference }}
                                </a> <br>
                                {{ $order->trackingNumber }}

                            </td>


                            <td>{{ $order->user->name }}</td>

                            <td><a
                                    href="/admin/orders/search?mobile={{ $order->clientPhone }}">{{ count_order($order->clientPhone) }}</a>
                            </td>

                            <td>{{ $order->clientName }} <br> {{ $order->clientPhone }}</td>

                            <td>{{ $order->city }}</td>

                            <td class="text-center"><span
                                    class="orderStatus {{ StatusClass($order->status) }}">{{ $order->status }} </span>
                                <br>

                            </td>

                            <td> <svg width="32" height="24" viewBox="0 0 32 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">

                                    <path
                                        d="M11.5469 11.1784V14.0951M20.5469 9.72005V12.6367M19.7969 6.07422C21.6334 6.07422 22.6267 6.34748 23.121 6.55944C23.1868 6.58766 23.2197 6.60178 23.3147 6.68991C23.3716 6.74273 23.4755 6.89773 23.5023 6.9697C23.5469 7.08976 23.5469 7.15538 23.5469 7.28664V15.124C23.5469 15.7867 23.5469 16.118 23.4447 16.2883C23.3407 16.4615 23.2404 16.5421 23.0458 16.6086C22.8545 16.6741 22.4683 16.6019 21.696 16.4577C21.1554 16.3567 20.5143 16.2826 19.7969 16.2826C17.5469 16.2826 15.2969 17.7409 12.2969 17.7409C10.4603 17.7409 9.46702 17.4676 8.97279 17.2557C8.90697 17.2274 8.87406 17.2133 8.77907 17.1252C8.72214 17.0724 8.61821 16.9174 8.59148 16.8454C8.54687 16.7253 8.54687 16.6597 8.54688 16.5285L8.54688 8.69109C8.54688 8.02845 8.54688 7.69713 8.64908 7.52682C8.75305 7.35359 8.85332 7.27305 9.04792 7.20648C9.23923 7.14103 9.6254 7.21317 10.3977 7.35744C10.9383 7.45843 11.5795 7.53255 12.2969 7.53255C14.5469 7.53255 16.7969 6.07422 19.7969 6.07422ZM17.9219 11.9076C17.9219 12.9143 17.0824 13.7305 16.0469 13.7305C15.0113 13.7305 14.1719 12.9143 14.1719 11.9076C14.1719 10.9008 15.0113 10.0846 16.0469 10.0846C17.0824 10.0846 17.9219 10.9008 17.9219 11.9076Z"
                                        stroke="#039855" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                    </path>
                                    <rect x="0.1" y="0.2" width="31" height="23" rx="3.5" stroke="none">
                                    </rect>
                                </svg> {{ $OrderData['total'] + $order->delivery_price }} </td>


                            <td class="text-center">
                                <small>{{ fixdata($order->created_at) }} </small><br>
                                <small> من {{ timeFormat($order->created_at) }}</small>
                            </td>

                            <td style="color: red"><small>{{ $order->notes }}</small></td>


                            <td>
                                <div class="d-flex">
                                    <div onclick="GetOrderDetails({{ $order->id }} , 'admin')"
                                        data-tippy-content="عرض الاوردر" class="square-btn ltr has-tip"><i
                                            class="fa-regular fa-eye mr-2  icon fa-fw"></i>
                                    </div>


                                    @can('has', 'orders_confrim')
                                        <div class="dropdown" id="myDropdown">

                                            <div class=" square-btn ltr has-tipdropdown-toggle" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false" data-tippy-content="تعديل"><i
                                                    class="far fa-edit mr-2  icon fa-fw"></i></div>

                                            <ul class="dropdown-menu" style="text-align: right">
                                                <li> <a target="_blank" class="dropdown-item"
                                                        href="/admin/orders/{{ $order->id }}/show">
                                                        <i class="fa-regular fa-eye fa-fw"></i>
                                                        <span>عرض الاوردر</span></a>
                                                </li>

                                                <li> <a target="_blank" class="dropdown-item"
                                                        href="/admin/orders/{{ $order->id }}/statusLogs">
                                                        <i class="fa-solid fa-clock-rotate-left fa-fw" aria-hidden="true"></i>
                                                        <span>تتبع حالات الطلب</span></a>
                                                </li>


                                            </ul>





                                        </div>
                                    @endcan
                                </div>
                            </td>


                        </tr>


                    @empty
                        <tr>
                            <td colspan="13">لا يوجد اوردرات </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>
    </div>

    <x-orderFrame admin></x-orderFrame>

    <div class="pagnate" class="السابق والتالي">
        {{ $orders->appends(request()->query())->links() }}
    </div>


    <form action="/admin/orders/actions/to_return" id="retrun_form" method="post">
        @csrf
        <input type="hidden" name="ids" value="">
        <input type="hidden" name="reference" value="">
    </form>

@endsection

@section('js')
    <script>
        $('aside .orders').addClass('active');
        tippy('[data-tippy-content]');

        $('.js-example-basic-single').select2();

        flatpickr('input.date', {
            enableTime: false,
            mode: "range",

            dateFormat: "Y-m-d"
        });

        function get_ids() {
            let ids = [];
            let inputs = $('input.UsersCheckBox[type="checkbox"]:checked ');

            for (const input of inputs) {
                ids.push(input.value);
            }

            return ids;
        }
    </script>

    <script>
        $(".js-example-tags").select2({
            tags: true,
        });

        $('.js-example-tags').on('select2:select', function(e) {


            if (e.params.data.id == "") {
                $('.js-example-tags').html('');

            }



            if (e.params.data.element || e.params.data.id.length < 4) {
                return;
            }


            var data = e.params.data;

            $.ajax({
                url: '/admin/users/searchAjax',
                type: 'GET',
                dataType: 'json',
                data: {
                    query: data.id
                },
                success: function(response) {
                    if (response.status == "success") {


                        let cartona = ``;


                        for (const user of response.users) {
                            cartona += `<option value="${user.id}">${user.name}</option>`

                        }

                        $('.js-example-tags').html(cartona);

                    } else {

                        $('.js-example-tags').html('');


                    }
                }
            });

        });
    </script>


    {{-- @if ($status != '') --}}
    <script>
        function allUsers(e) {
            $("input.UsersCheckBox").not(e).prop("checked", e.checked);

            $("tr").toggleClass("active", e.checked);
            check()
        }

        function check(e) {

            if ($(e).prop("checked")) {
                $(e).parent().parent().addClass("active")
            } else {
                $(e).parent().parent().removeClass("active")
            }


            var count = $('input[type="checkbox"].UsersCheckBox:checked').length;

            if (count == 0) {
                $("#active").hide();
                $("#selected_count").html(0);
                $("#selected_count_parant").hide();
            } else {
                $("#active").show();
                $("#selected_count").html(count);
                $("#selected_count_parant").show();

            }

        }


        $("tr").on("click", function(event) {
            if ($(event.target).is("td:not(:first-child)")) {
                $(this).toggleClass("active");

                var checkbox = $(this).find("input[type='checkbox']");
                checkbox.prop("checked", !checkbox.prop("checked"));
            }

            check()
        });

        function chnage_order_status() {

            let html = `<form id="statusChangeForm" action="/admin/tools/change_order_status" method="post">
                            @csrf
                            <input type="hidden" name="ids" id="ids">
                            <select name="status" id="status" required  class="js-example-basic-single " >


                            <option value="قيد الانتظار">قيد الانتظار</option>
                            <option value="قيد المراجعة">قيد المراجعة</option>
                            <option value="محاولة تانية">محاولة تانية</option>
                            <option value="تم الالغاء">تم الالغاء</option>
                            <option value="تم المراجعة">تم المراجعة</option>
                            <option value="جاهز للتغليف">جاهز للتغليف</option>
                            <option value="جاري التجهيز للشحن">جاري التجهيز للشحن</option>
                            <option value="تم ارسال الشحن">تم ارسال الشحن</option>
                            <option value="تم التوصيل">تم التوصيل</option>
                            <option value="مكتمل">مكتمل</option>
                            <option value="طلب استرجاع">طلب استرجاع</option>
                            <option value="فشل التوصيل">فشل التوصيل</option>


                            </select>
                        </form>
                        `

            Swal.fire({
                title: "تغير حالة الطلبات الي",
                html: html,
                focusConfirm: false,
                showConfirmButton: true,
                inputAutoFocus: false,

                confirmButtonText: "تغير",

                preConfirm: () => {
                    let ids = get_ids();
                    if (ids.length == 0) {
                        toastr["error"]("يرجي اختيار طلبات");
                    } else {

                        $("input#ids").val(ids);
                        $("#statusChangeForm").submit();

                    }

                },
                didOpen: () => {
                    $('.js-example-basic-single').select2();
                },


            })


        }
    </script>
    {{-- @endif --}}

    {{-- رفع في تيربو --}}
    @if ($status == 'تم المراجعة')
        <script>
            function to_turbo() {


                let html = `<form id="to_turbo" action="/admin/orders/actions/to_turbo" method="post">
            @csrf
            <input type="hidden" name="ids" id="ids">
            <select class="mt-2" name="type" required>
        <option disabled selected>اختار شركة الشحن</option>
        <option value="turbo">turbo</option>
        <option value="speed">speed</option>
        <option value="adm">adm</option>
    </select>


        </form>`


                Swal.fire({
                    title: "هل انت متاكد من شحن الاوردرات ؟",

                    html: html,
                    focusConfirm: false,
                    showConfirmButton: true,
                    inputAutoFocus: false,

                    confirmButtonText: 'شحن',

                    preConfirm: () => {
                        let ids = [];
                        let inputs = $('input.UsersCheckBox[type="checkbox"]:checked ');

                        for (const input of inputs) {
                            ids.push(input.value);
                        }

                        $("input#ids").val(ids);
                        $("#to_turbo").submit();


                    }
                })

            }
        </script>
    @endif

    {{-- testing --}}


    @if ($status == 'جاهز للتغليف')
        <script>
            $("#reference").keypress(function(event) {
                if (event.which === 13) {
                    event.preventDefault();
                    let inputText = $(this).val();
                    if (inputText.trim() != "") {

                        $.ajax({
                            url: `/admin/orders/GetOrderDetailsAjaxReference/${inputText.trim()}`,
                            type: "GET",
                            dataType: "json",

                            beforeSend() {
                                $("#loader").addClass("d-flex");
                                $("#loader").removeClass("d-none");
                            },

                            success: function(response) {
                                $("#loader").addClass("d-none");
                                $("#loader").removeClass("d-flex");


                                if (response.status == "success" && response.data != undefined) {
                                    let data = response.data;


                                    $("#order_id").val(data.reference);

                                    $(".frameContent #reference").text(data.reference);
                                    $(".frameContent #name").text(data.name);
                                    $(".frameContent #mobile").text(data.phone);
                                    $(".frameContent #city").text(data.city);


                                    $(".frameContent #status").text(data.status);


                                    $(".frameContent #status").removeClass();
                                    $(".frameContent #status")
                                        .addClass("orderStatus")
                                        .addClass(data.class);

                                    let cartona = ``;

                                    for (const detail of data.details) {
                                        cartona += `
                                            <tr id="${detail.sku}">
                                            <td><img class="prodcut_img"
                                                    src="${detail.img}"
                                                    alt="${detail.discription}"></td>

                                            <td> ${detail.discription} </td>
                                            <td class="test_qnt">${detail.qnt}</td>`

                                        let box = ``;

                                        for (let x = 0; x < detail.qnt; x++) {
                                            box +=
                                                `<input class="not check_test" style='margin:0px 5px; pointer-events: none;' type="checkbox" readonly >`;
                                        }


                                        cartona += `<td style="white-space:normal;"> ${box} </td>`;




                                        cartona += `</tr>`;

                                        $("#frameTable2").html(cartona);
                                    }

                                    $("#TestingFream").removeClass();
                                    $("#TestingFream").addClass("frame").addClass("d-flex");
                                    document.getElementById("testingInput").focus();

                                } else {
                                    toastr["error"]("هناك خطأ ما");
                                }
                            },
                            error: function() {
                                $("#loader").addClass("d-none");
                                $("#loader").removeClass("d-flex");

                                toastr["error"]("هناك خطأ ما");

                            },
                        });

                    }
                }
            });

            $("#testingInput").keypress(function(event, e) {
                if (event.which === 13) {
                    event.preventDefault();
                    let inputText = $(this).val();
                    if (inputText.trim() != "") {
                        let sku = inputText.trim();

                        let tr = $("#" + sku);

                        if (tr.length == 0) {
                            error();
                        } else {

                            let qnt = parseInt($(tr).find(".test_qnt").eq(0).text());

                            let checkes = $(tr).find(".check_test");

                            let cheched = $(tr).find(".check_test:checked");

                            if (cheched.length == qnt) {
                                error();
                            } else {
                                if (qnt == 1) {
                                    checkes[0].setAttribute("checked", "checked");
                                } else {
                                    for (let z = 0; z < checkes.length; z++) {
                                        if (!checkes[z].checked) {
                                            checkes[z].setAttribute("checked", "checked");
                                            break;
                                        }
                                    }
                                }
                                toastr["success"]("تمام");

                                $(this).val("")
                                document.getElementById("testingInput").focus();

                                // // هشوف هغير ولا لا

                                let All = document.getElementsByClassName("check_test");
                                let checkResult = true;

                                for (let i = 0; i < All.length; i++) {
                                    if (!All[i].checked) {
                                        checkResult = false;
                                    }
                                }

                                if (checkResult == true) {
                                    let reference = $("#order_id").val();
                                    window.location.href = `/admin/orders/actions/${reference}/toReady`
                                }
                            }

                        }
                    }
                }
            });

            function error() {

                toastr["error"]("لا يوجد منتج بهذا الايدي");


                $("#testingInput").val("")
                document.getElementById("testingInput").focus();
            }

            document.getElementById("reference").focus();
        </script>
    @endif

    {{-- check_still_witting  --}}

    @if ($status == 'قيد الانتظار')
        <script>
            function check_still_witting() {

                Swal.fire({
                    title: 'سيتم فحص طلبات الانتظار والتحقق من وجود استوك لها وتحولها الي قيد المراجعة',
                    focusConfirm: false,
                    showConfirmButton: true,
                    inputAutoFocus: false,

                    confirmButtonText: 'فحص طلبات الانتظار',

                    preConfirm: () => {
                        window.location.href = "/admin/tools/waitingOrders"
                    }
                })


            }
        </script>
    @endif

    <script>
        function print() {

            let ids = get_ids();


            var popupWindow = null;


            var url = `/admin/orders/print/${ids.length != 0 ? ids : 0}`;
            var width = 800;
            var height = 600;

            // Position the pop-up in the center of the screen
            var left = (screen.width - width) / 2;
            var top = (screen.height - height) / 2;

            // Define the features for the pop-up window
            var features = "width=" + width + ",height=" + height + ",left=" + left + ",top=" + top +
                ",toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes";

            // Open the pop-up window
            popupWindow = window.open(url, "Page2Window", features);

        }

        function change_bulk_orders_status() {
            let html = `<form  id="change_bulk_orders_status"  action="/admin/orders/actions/change_bulk_orders_status" method="post">
                        @csrf
                        <textarea name="ids" cols="30" rows="5" placeholder=" id 1 , id 2 , id 3 ...."></textarea>

                        <select name="status" class="js-example-basic-single ">
                            <option disabled>اختار الحالة</option>
                            <option value="قيد الانتظار">قيد الانتظار</option>
                            <option value="قيد المراجعة">قيد المراجعة</option>
                            <option value="محاولة تانية">محاولة تانية</option>
                            <option value="تم الالغاء">تم الالغاء</option>
                            <option value="تم المراجعة">تم المراجعة</option>
                            <option value="جاهز للتغليف">جاهز للتغليف</option>
                            <option value="جاري التجهيز للشحن">جاري التجهيز للشحن</option>
                            <option value="تم ارسال الشحن">تم ارسال الشحن</option>
                            <option value="تم التوصيل">تم التوصيل</option>
                            <option value="مكتمل">مكتمل</option>
                            <option value="طلب استرجاع">طلب استرجاع</option>
                            <option value="فشل التوصيل">فشل التوصيل</option>
                        </select>
                    </form>
                `;

            Swal.fire({
                title: "تغير حالة الطلبات الي",
                html: html,
                focusConfirm: false,
                showConfirmButton: true,
                inputAutoFocus: false,

                confirmButtonText: "تغير",

                preConfirm: () => {
                    $("#change_bulk_orders_status").submit();
                },
                didOpen: () => {
                    $('.js-example-basic-single').select2();
                },


            })


        }

        function required_producst() {
            let ids = get_ids();
            if (ids.length == 0) {
                toastr["error"]("يرجي اختيار طلبات");
            } else {
                window.location.href = `/admin/orders/actions/${ids}/required_producst`

            }

        }
    </script>



    {{-- return --}}


    @if ($status == 'طلب استرجاع')
        <script>
            $("#reference").keypress(function(event) {
                if (event.which === 13) {
                    event.preventDefault();
                    $("#returnBtn").prop("disabled", true);

                    let inputText = $(this).val();
                    if (inputText.trim() != "") {

                        $.ajax({
                            url: `/admin/orders/GetOrderDetailsAjaxReference/${inputText.trim()}`,
                            type: "GET",
                            dataType: "json",

                            beforeSend() {
                                $("#loader").addClass("d-flex");
                                $("#loader").removeClass("d-none");
                            },

                            success: function(response) {
                                $("#loader").addClass("d-none");
                                $("#loader").removeClass("d-flex");


                                if (response.status == "success" && response.data != undefined) {
                                    let data = response.data;


                                    $("#order_id").val(data.reference);

                                    $(".frameContent #reference").text(data.reference);
                                    $(".frameContent #name").text(data.name);
                                    $(".frameContent #mobile").text(data.phone);
                                    $(".frameContent #city").text(data.city);


                                    $(".frameContent #status").text(data.status);


                                    $(".frameContent #status").removeClass();
                                    $(".frameContent #status")
                                        .addClass("orderStatus")
                                        .addClass(data.class);

                                    let cartona = ``;

                                    for (const detail of data.details) {
                                        console.log(detail);
                                        cartona += `
                                            <tr id="${detail.sku}">
                                            <td><img class="prodcut_img"
                                                    src="${detail.img}"
                                                    alt="${detail.discription}"></td>

                                            <td> ${detail.discription} </td>
                                            <td class="test_qnt">${detail.qnt}</td>`

                                        let box = ``;

                                        for (let x = 0; x < detail.qnt; x++) {
                                            box +=
                                                `<input data-id="${detail.id}" class="not check_test" style='margin:0px 5px; pointer-events: none;' type="checkbox" readonly >`;
                                        }


                                        cartona += `<td style="white-space:normal;"> ${box} </td>`;


                                        cartona += `</tr>`;




                                        $("#frameTable2").html(cartona);
                                    }





                                    $("#TestingFream").removeClass();
                                    $("#TestingFream").addClass("frame").addClass("d-flex");


                                    document.getElementById("return_input").focus();

                                } else {
                                    toastr["error"]("هناك خطأ ما");
                                }
                            },
                            error: function() {
                                $("#loader").addClass("d-none");
                                $("#loader").removeClass("d-flex");

                                toastr["error"]("هناك خطأ ما");

                            },
                        });

                    }
                }
            });

            $("#return_input").keypress(function(event, e) {
                if (event.which === 13) {
                    event.preventDefault();
                    let inputText = $(this).val();
                    if (inputText.trim() != "") {
                        let sku = inputText.trim();

                        let tr = $("#" + sku);

                        if (tr.length == 0) {
                            error();
                        } else {

                            let qnt = parseInt($(tr).find(".test_qnt").eq(0).text());

                            let checkes = $(tr).find(".check_test");

                            let cheched = $(tr).find(".check_test:checked");

                            if (cheched.length == qnt) {
                                error();
                            } else {
                                if (qnt == 1) {
                                    checkes[0].setAttribute("checked", "checked");
                                } else {
                                    for (let z = 0; z < checkes.length; z++) {
                                        if (!checkes[z].checked) {
                                            checkes[z].setAttribute("checked", "checked");
                                            break;
                                        }
                                    }
                                }
                                toastr["success"]("تمام");

                                $(this).val("")
                                document.getElementById("return_input").focus();

                                // // هشوف هغير ولا لا

                                let All = document.getElementsByClassName("check_test");
                                let checkResult = true;

                                for (let i = 0; i < All.length; i++) {
                                    if (!All[i].checked) {
                                        checkResult = false;
                                    }
                                }

                                if (checkResult == true) {
                                    let reference = $("#order_id").val();
                                    window.location.href = `/admin/orders/actions/${reference}/to_failed`
                                }
                            }



                            cheched = $(tr).find(".check_test:checked");


                            if (cheched.length > 0) {
                                $("#returnBtn").prop("disabled", false);

                            }




                        }





                    }
                }
            });

            function error() {

                toastr["error"]("لا يوجد منتج بهذا الايدي");


                $("#return_input").val("")
                document.getElementById("return_input").focus();
            }

            document.getElementById("reference").focus();


            function partial_refund() {

                var ids = [];


                $(".check_test:checked").each(function() {
                    ids.push($(this).data("id"));
                });

                let reference = $("#order_id").val();

                $("input[name=ids]").val(ids)
                $("input[name=reference]").val(reference)



                $("#retrun_form").submit();




            }
        </script>
    @endif
@endsection
