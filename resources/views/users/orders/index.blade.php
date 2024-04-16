@extends('admin.layout')


@section('content')
    <x-searchForm action="{{ url('users/orders/search') }}">
        @php
            !empty($_GET['reference']) ? ($reference = $_GET['reference']) : ($reference = '');
            !empty($_GET['track']) ? ($track = $_GET['track']) : ($track = '');
            !empty($_GET['name']) ? ($name = $_GET['name']) : ($name = '');
            !empty($_GET['mobile']) ? ($mobile = $_GET['mobile']) : ($mobile = '');
            !empty($_GET['city_name']) ? ($city_name = $_GET['city_name']) : ($city_name = '');
            !empty($_GET['status']) ? ($status = $_GET['status']) : ($status = '');
            !empty($_GET['date']) ? ($date = $_GET['date']) : ($date = '');
            !empty($_GET['withModerators']) ? ($withModerators = $_GET['withModerators']) : ($withModerators = '');
        @endphp


        <x-admin.forms.input name="reference" value="{{ $reference }}" lable_title="كود الاوردر">
        </x-admin.forms.input>

        <x-admin.forms.input name="track" value="{{ $track }}" lable_title="رقم الشحنة">
        </x-admin.forms.input>

        <x-admin.forms.input name="clientName" value="{{ $name }}" lable_title="اسم العميل">
        </x-admin.forms.input>

        <x-admin.forms.input name="mobile" value="{{ $mobile }}" lable_title="رقم العميل">
        </x-admin.forms.input>

        <div>
            <label for="reference" class="mb-2"> المحافظة </label>
            <select name="city_name" class="js-example-basic-single">

                <option value="">كل المحافظات</option>

                @foreach ($cities as $city)
                    <option @selected($city_name == $city->name) value="{{ $city->name }}">
                        {{ $city->name }} </option>
                @endforeach

            </select>
        </div>

        <div>
            <label for="reference" class="mb-2"> حالة الطلب </label>

            <select name="status" class="js-example-basic-single">

                <option value="">كل الحالات</option>

                @foreach (ALL_STATUS() as $state)
                    <option @selected($status == $state) value="{{ $state }}">{{ $state }}</option>
                @endforeach



            </select>
        </div>


        @if (auth()->user()->role == 'user')
            <div>
                <label for="reference" class="mb-2"> نوع الطلب </label>

                <div data-title="كل الطلبات">

                    <select name="withModerators" class="js-example-basic-single">

                        <option @selected($withModerators == 'yes') value="yes">كل الطلبات</option>
                        <option @selected($withModerators == 'no') value="no">طلباتي فقط</option>
                        <option @selected($withModerators == 'only') value="only">الموديتور فقط</option>

                        @foreach (auth()->user()->moderators as $moderator)
                            <option @selected($withModerators == $moderator->id) value="{{ $moderator->id }}">{{ $moderator->name }}
                                <small>
                                    {{ $moderator->deleted_at ? ' ( محذوف ) ' : '' }} </small>
                            </option>
                        @endforeach

                    </select>

                </div>
            </div>
        @endif

        <div>
            <label for="reference" class="mb-2"> تاريخ انشاء الاوردر</label>

            <div dir="ltr" class=" mb-3 ">
                <input name="date" class="date" type="text" value="{{ $date }}"
                    placeholder="اختار التاريخ">
            </div>

        </div>


    </x-searchForm>

    <div class="actions">


        <div class="contnet-title"> جميع الطلبات </div>

        <div class="d-flex align-items-center gap-2">

            <x-searchBtn></x-searchBtn>

            <x-link path="/users/products" title="اضافة طلب"><svg width="22" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg></x-link>

        </div>



    </div>

    <div class=" position-relative ">


        <div id="loader">
            <i class="fa-solid fa-spinner fa-spin"></i>
        </div>

        <div class="tableSpace  " style="min-height:60vh">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الكود</th>
                        <th>بواسطة</th>
                        <th>الطلبات <br> الحالية</th>
                        <th>اجمالي <br> الاوردر</th>
                        <th>العمولة</th>
                        {{-- <th>البونص</th>
                        <th>اجمالي <br>العمولة</th> --}}
                        <th>الاسم</th>
                        <th>المحافظة</th>
                        <th>الحالة</th>
                        <th>تاريخ الانشاء</th>
                        <th>ملاحظات</th>
                        <th>الاجراءات</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($orders as $order)
                        @php
                            $OrderData = getOrderData($order->details);
                        @endphp
                        <tr>
                            <td> {{ ($orders->currentpage() - 1) * $orders->perpage() + $loop->index + 1 }} </td>

                            <td>
                                <a target="_blank" href="/users/orders/{{ $order->id }}/show">{{ $order->reference }}
                                </a> <br> {{ $order->trackingNumber }}
                            </td>

                            <td>{{ $order->user->name }}</td>

                            <td>{{ count_order($order->clientPhone) }}</td>

                            <td> <svg width="32" height="24" viewBox="0 0 32 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">

                                    <path
                                        d="M11.5469 11.1784V14.0951M20.5469 9.72005V12.6367M19.7969 6.07422C21.6334 6.07422 22.6267 6.34748 23.121 6.55944C23.1868 6.58766 23.2197 6.60178 23.3147 6.68991C23.3716 6.74273 23.4755 6.89773 23.5023 6.9697C23.5469 7.08976 23.5469 7.15538 23.5469 7.28664V15.124C23.5469 15.7867 23.5469 16.118 23.4447 16.2883C23.3407 16.4615 23.2404 16.5421 23.0458 16.6086C22.8545 16.6741 22.4683 16.6019 21.696 16.4577C21.1554 16.3567 20.5143 16.2826 19.7969 16.2826C17.5469 16.2826 15.2969 17.7409 12.2969 17.7409C10.4603 17.7409 9.46702 17.4676 8.97279 17.2557C8.90697 17.2274 8.87406 17.2133 8.77907 17.1252C8.72214 17.0724 8.61821 16.9174 8.59148 16.8454C8.54687 16.7253 8.54687 16.6597 8.54688 16.5285L8.54688 8.69109C8.54688 8.02845 8.54688 7.69713 8.64908 7.52682C8.75305 7.35359 8.85332 7.27305 9.04792 7.20648C9.23923 7.14103 9.6254 7.21317 10.3977 7.35744C10.9383 7.45843 11.5795 7.53255 12.2969 7.53255C14.5469 7.53255 16.7969 6.07422 19.7969 6.07422ZM17.9219 11.9076C17.9219 12.9143 17.0824 13.7305 16.0469 13.7305C15.0113 13.7305 14.1719 12.9143 14.1719 11.9076C14.1719 10.9008 15.0113 10.0846 16.0469 10.0846C17.0824 10.0846 17.9219 10.9008 17.9219 11.9076Z"
                                        stroke="#039855" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                    <rect x="0.1" y="0.2" width="31" height="23" rx="3.5" stroke="none">
                                    </rect>
                                </svg> {{ $OrderData['total'] + $order->delivery_price }} </td>



                            <td> {{ $OrderData['ponus'] + $OrderData['comissation'] }} </td>

                            <td>{{ $order->clientName }} <br> {{ $order->clientPhone }}</td>
                            <td>{{ $order->city }}</td>

                            <td><span
                                    class="orderStatus {{ StatusClass($order->status) }}">{{ fixStatus($order->status) }}</span>
                            </td>

                            @php
                                $dateTime = $order->created_at;
                                $dateTimeObj = new DateTime($dateTime);
                                $date = $dateTimeObj->format('Y-m-d');
                                $time = $dateTimeObj->format('H:i:s A');
                            @endphp

                            <td class="text-center">
                                {{ $date }} {{ $time }} <br>
                                <small> من {{ timeFormat($order->created_at) }}</small>
                            </td>

                            <td style="color: red">{{ $order->notes }}</td>

                            <td>

                                <div class="d-flex">
                                    <div onclick="GetOrderDetails({{ $order->id }})" data-tippy-content="عرض الاوردر"
                                        class="square-btn ltr has-tip"><i class="fa-regular fa-eye mr-2  icon fa-fw"></i>
                                    </div>


                                    <div class="dropdown">

                                        <div class=" square-btn ltr has-tipdropdown-toggle" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false" data-tippy-content="تعديل"><i
                                                class="far fa-edit mr-2  icon fa-fw"></i></div>


                                        <ul class="dropdown-menu" style="text-align: right">
                                            <li> <a class="dropdown-item" href="/users/orders/{{ $order->id }}/show">
                                                    <i class="fa-regular fa-eye fa-fw"></i>
                                                    <span>عرض الاوردر</span></a>
                                            </li>


                                            @if ($order->status == 'قيد المراجعة' || $order->status == 'قيد الانتظار')
                                                <li>
                                                    <a class="dropdown-item" href="/users/orders/{{ $order->id }}/edit">
                                                        <i class="fa-regular fa-pen-to-square fa-fw"></i> بيانات العميل
                                                    </a>
                                                </li>




                                                <li onclick="cancel({{ $order->id }})"><a class="dropdown-item"
                                                        href="#">
                                                        <i class="fa-regular fa-trash-can fa-fw"></i> الغاء الاوردر</a>
                                                </li>
                                            @endif


                                            <li> <a target="_blank" class="dropdown-item"
                                                    href="/users/orders/{{ $order->id }}/statusLogs">
                                                    <i class="fa-solid fa-clock-rotate-left fa-fw" aria-hidden="true"></i>
                                                    <span>تتبع حالات الطلب</span></a>
                                            </li>

                                        
                                        </ul>


                                    </div>
                                </div>



                            </td>


                        </tr>
                    @empty
                        <tr>
                            <td colspan="12">لا يوجد اوردرات مضافة</td>
                        </tr>
                    @endforelse


                </tbody>

            </table>
        </div>
    </div>

    <x-orderFrame></x-orderFrame>


    <div class="pagnate" class="السابق والتالي">
        {{ $orders->appends(request()->query())->links() }}
    </div>
@endsection


@section('js')
    <script>
        $('aside .orders').addClass('active');

        $('.js-example-basic-single').select2();

        flatpickr('input.date', {
            enableTime: false,
            mode: "range",

            dateFormat: "Y-m-d"
        });

        function cancel(id) {
            Swal.fire({
                title: 'هل انت متاكد من الغاء الاوردر ؟',
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: 'نعم',
                cancelButtonText: 'لا خروج',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/users/orders/${id}/cancel`;
                }
            })
        }
    </script>
@endsection
