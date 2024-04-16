@extends('admin.layout')

@section('css')
    <style>
        .bordered-alert {
            border-right-width: 4px;
            font-size: .875rem;
            padding: 10px 20px;
            border-radius: 0.2rem;
            font-weight: 600;
            font-size: 18px;
            --tw-border-opacity: 1;
            border-color: rgb(245 158 11 / var(--tw-border-opacity));
            --tw-bg-opacity: 1;
            background-color: rgb(254 243 199 / var(--tw-bg-opacity));
            --tw-text-opacity: 1;
            color: rgb(180 83 9 / var(--tw-text-opacity));
        }

        .notes {
            margin-top: 5px;
            color: red;
            font-weight: 700;
            font-size: 14px
        }

        .firstRow {
            display: flex;
            align-items: center;
            padding: 0px;
            margin-top: 15px;
            flex-wrap: wrap;

        }

        .firstRow div {
            margin-left: 10px;
        }

        .id {
            font-size: 15px;
            font-weight: 600;
            color: var(--mainColor)
        }

        .time {
            color: #7a7b97;
            font-size: 14px;
            font-weight: 700;
        }

        .prodcut_img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            border-radius: 5px;

        }
    </style>

    <style>
        .info {
            background-color: #f5f5f7 !important;
            padding: 20px;
            border-radius: 2px;
            margin-bottom: 15px;
        }

        .order_title {
            color: rgb(37, 42, 49);
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 5px;
            border-bottom: 1px solid rgba(128, 128, 128, 0.26);
            padding-bottom: 10px;
        }

        .client_data p,
        .client_data a,
        .client_data .note {
            margin: 0px;
            color: rgb(95, 115, 140);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            margin-right: 10px;
            margin-bottom: 5px;
            cursor: pointer;
        }

        .bwipyB {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            vertical-align: middle;
            fill: currentcolor;
        }


        .total {
            margin: 5px 0px;
            font-size: 15px;
            font-weight: 600;
        }

        .total span {
            margin: 5px 0px;
            font-weight: 700;
            font-size: 14px;
            color: rgba(0, 0, 0, 0.8);
            color: var(--mainColor)
        }
    </style>
@endsection


@section('path')
    <ul class="paths">
        <li> <a href="{{ url('users/home') }}"> الرئيسية / </a> </li>
        <li> <a href="{{ url('users/orders') }}"> الطلبات / </a> </li>
        <li class="active"> {{ $order->clientName }}</li>
    </ul>
@endsection



@section('content')
    <div class=" mt-lg-4 mx-3  mb-4  ">
        <div class="row bg-white  py-2 px-2 rounded">


            <div class="col-lg-8 mb-3">

                <div class="firstRow">
                    <div class="id"># {{ $order->reference }}</div>
                    <div class="orderStatus {{ StatusClass($order->status) }}">{{ fixStatus($order->status) }}</div>
                    <div class="time">
                        <span> {{ $order->created_at }}
                        </span>
                    </div>

                </div>

                @if ($order->notes)
                    <div class="notes">
                        {{ $order->notes }}
                    </div>
                @endif

                @if ($order->notesBosta)
                    <div class="notes">
                        {{ $order->notesBosta }}
                    </div>
                @endif





                <div class="mt-4 p-0 " style="overflow-x: scroll">

                    <table style="background: #f8f8f8;">
                        <thead>
                            <tr>
                                <th>صورة المنتج</th>

                                <th>اسم المنتج</th>
                                <th>عدد القطع</th>
                                <th>السعر</th>
                                <th>العمولة</th>
                                <th>البونص</th>
                                <th>الاجمالي</th>

                                @if ($order->status == 'قيد المراجعة' || $order->status == 'قيد الانتظار')
                                    <th>ادارة</th>
                                @endif

                            </tr>
                        </thead>

                        <tbody id="frameTable">
                            @foreach ($order->details as $detail)
                                <tr>
                                    @php
                                        $path = $detail->product->firstImg->img;
                                    @endphp

                                    <td> <img width="70" style="object-fit: contain" src="{{ path("$path") }}"
                                            alt="{{ $detail->discription }}"> </td>

                                    <td> {{ $detail->discription }} </td>
                                    <td>{{ $detail->qnt }}</td>
                                    <td>{{ $detail->price }}</td>


                                    <td>{{ $detail->qnt * $detail->comissation }}</td>

                                    <td>{{ $detail->qnt * $detail->ponus }}</td>


                                    <td>{{ $detail->qnt * ($detail->price + $detail->comissation) }} </td>

                                    @if ($order->status == 'قيد المراجعة' || $order->status == 'قيد الانتظار')
                                        <td>

                                            @if ($detail->product->show)
                                                <a target="_blank" href="/users/orders/details/{{ $detail->id }}/edit">
                                                    <div data-tippy-content="تعديل المنتج" class="square-btn ltr has-tip"><i
                                                            class="far fa-edit mr-2 icon mr-2  icon fa-fw"></i></div>
                                                </a>
                                            @endif




                                            <div type="button" data-id="{{ $detail->id }}"
                                                data-name="{{ $detail->discription }}" onclick="show_delete_model(this)"
                                                data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                data-tippy-content="حذف" class="square-btn ltr has-tip"><i
                                                    class="far fa-trash-alt mr-2 icon fa-fw" aria-hidden="true"></i>
                                            </div>
                                    @endif


                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>


                @if ($order->status == 'قيد المراجعة' || $order->status == 'قيد الانتظار')
                    <div class="mt-3">
                        <x-admin.forms.buttonLink title="اضافة منتج" target="_blank"
                            path="/users/products?add_new_product={{ $order->id }}"></x-admin.forms.buttonLink>

                    </div>
                @endif


                <div class="order_title mt-4"> اﻻجمالى</div>

                @php
                    $data = getOrderData($order->details);
                @endphp

                <p class="total"> الاوردر : <span>{{ $data['total'] }}</span> </p>
                <div class="total"> مصاريف الشحن : <span> {{ $order->delivery_price }} </span> </div>
                <div class="total"> اجمالي الاوردر : <span>{{ $data['total'] + $order->delivery_price }}</span> </div>


            </div>


            <div class="col-lg-4 ">
                <div class="client_data">

                    <div class="info">
                        <div class="order_title">بيانات العميل</div>

                        <p class="name">{{ $order->clientName }}</p>


                        <a style="font-family: 'Cairo';font-size: 13px" href="tel:01006531955" class="phone">
                            <svg class="Icon__StyledIcon-sc-178dh1b-0 bwipyB" viewBox="0 0 10 16"
                                preserveAspectRatio="xMidYMid meet">
                                <path
                                    d="M8 .667H2c-.92 0-1.667.746-1.667 1.666v11.334c0 .92.747 1.666 1.667 1.666h6c.92 0 1.666-.746 1.666-1.666V2.333C9.666 1.413 8.92.667 8 .667ZM5 14.083a.75.75 0 1 1-.002-1.499.75.75 0 0 1 .002 1.5ZM8.333 12H1.666V2.667h6.667V12Z">
                                </path>
                            </svg> {{ $order->clientPhone }}</a>


                        @if ($order->clientPhone2)
                            <a style="font-family: 'Cairo';font-size: 13px" href="tel:01006531955" class="phone">
                                <svg class="Icon__StyledIcon-sc-178dh1b-0 bwipyB" viewBox="0 0 10 16"
                                    preserveAspectRatio="xMidYMid meet">
                                    <path
                                        d="M8 .667H2c-.92 0-1.667.746-1.667 1.666v11.334c0 .92.747 1.666 1.667 1.666h6c.92 0 1.666-.746 1.666-1.666V2.333C9.666 1.413 8.92.667 8 .667ZM5 14.083a.75.75 0 1 1-.002-1.499.75.75 0 0 1 .002 1.5ZM8.333 12H1.666V2.667h6.667V12Z">
                                    </path>
                                </svg> {{ $order->clientPhone2 }}</a>
                        @endif

                    </div>

                    <div class="info">
                        <div class="order_title">عنوان الشحن</div>

                        <p class="city"><svg style="width:16px;height:16px" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M15,23H13V21H15V23M19,21H17V23H19V21M15,17H13V19H15V17M7,21H5V23H7V21M7,17H5V19H7V17M19,17H17V19H19V17M15,13H13V15H15V13M19,13H17V15H19V13M21,9A2,2 0 0,1 23,11V23H21V11H11V23H9V15H3V23H1V15A2,2 0 0,1 3,13H9V11A2,2 0 0,1 11,9V7A2,2 0 0,1 13,5H15V1H17V5H19A2,2 0 0,1 21,7V9M19,9V7H13V9H19Z">
                                </path>
                            </svg> {{ $order->city }} </p>

                        <p class="address"> <svg style="width:16px;height:16px" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5M12,2A7,7 0 0,1 19,9C19,14.25 12,22 12,22C12,22 5,14.25 5,9A7,7 0 0,1 12,2M12,4A5,5 0 0,0 7,9C7,10 7,12 12,18.71C17,12 17,10 17,9A5,5 0 0,0 12,4Z">
                                </path>
                            </svg> {{ $order->address }} </p>

                    </div>





                </div>
            </div>

        </div>
    </div>


    <x-admin.forms.buttonLink path="/users/orders" title="رجوع" icon="back"></x-admin.forms.buttonLink>

    <x-admin.forms.deleteModel type="users" model="orders/details" id="detail_id"></x-admin.forms.deleteModel>
@endsection


@section('js')
    <script>
        $('aside .orders').addClass('active');

        function show_delete_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $('#model_title').text(data_name);

            $("input[name='detail_id']").val(data_id)

        }
    </script>
@endsection
