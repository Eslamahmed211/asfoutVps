<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    @yield('title')


    @include('admin.inc.head')

    <style>
        td {
            text-align: center;
        }


        .border-wrapper {

            border-radius: 20px;
            border: 2px solid #000;
            /* Set the border style as per your requirement */
            padding: 5px;
            /* Adjust padding as needed */
        }


        .qr {
            padding-top: 5px;
            align-items: center;
            justify-content: space-between;
        }

        .info {
            background-color: #f5f5f7 !important;
            padding: 5px !important;
            border-radius: 2px;
            margin-bottom: 5px;
        }

        .order_title {
            color: rgb(37, 42, 49);
            font-size: 12px;
            font-weight: 700;
            /* margin-bottom: 5px; */
            /* border-bottom: 1px solid rgba(128, 128, 128, 0.26); */

            margin-right: 5px;
        }

        .total {
            text-align: center;
            margin: 5px 0px;

        }

        .total span {

            margin: 5px 0px;
            font-weight: 700;
            font-size: 15px;
            color: rgba(0, 0, 0, 0.8);
        }

        .total .order_title {

            align-items: center;
            justify-content: space-between;
        }

        #print {
            width: 100% !important;
            direction: rtl;
            text-align: center;
            font-weight: 500;
            font-family: 'Cairo', sans-serif;
            /* width: 209mm; */

        }

        table {
            border: 2px solid #F8F8F8;
            margin: auto;
            color: #626262;
            width: 100vw !important;
            text-align: center;
            border-spacing: 0 0.2rem;

        }

        thead {
            border-color: inherit;
            background-color: #f8f8f8;
            text-align: center;
            -webkit-print-color-adjust: exact;

        }


        .border-wrapper {

            border-radius: 20px;
            border: 2px solid #000;
            /* Set the border style as per your requirement */
            padding: 5px;
            /* Adjust padding as needed */
        }



        hr {
            margin: 5px 0px border-top: 2px solid;
            opacity: 100%;
            margin: 10px;
        }

        tr {
            border-bottom: 1px solid #F3F2F7;
            background: white;
            font-size: 17px;
            text-align: center;
            -webkit-print-color-adjust: exact;

        }

        th,
        td {
            font-size: 17px;
            text-align: center
        }

        .info {
            background-color: #f5f5f7 !important;
            -webkit-print-color-adjust: exact;
            width: 100% !important;
            padding: 20px;
            border-radius: 2px;
            margin-bottom: 5px;
            border-bottom: 1px solid #F3F2F7;
        }

        .client_data p,
        .client_data a,
        .client_data .note {
            margin: 0px;
            color: rgb(95, 115, 140);
            font-size: 10px;
            font-weight: 700;
            text-decoration: none;
            margin-right: 10px;
            margin-bottom: 5px;
            cursor: pointer;
            -webkit-print-color-adjust: exact;


        }

        .note {
            font-size: 13px;
            margin: 0px
        }

        .client_data {

            margin-bottom: 0px;
            flex-wrap: wrap
        }

        .total {
            text-align: center;
        }

        .bold {
            font-weight: 700 !important;
            color: black !important;
        }

        tfoot {
            border-top: 2px solid rgba(37, 42, 49, 0.4) !important;
        }

        body {
            font-size: 12px
        }

        th,
        td {
            font-size: 10px !important;
            font-weight: 700
        }

        .bold {
            font-size: 10px !important;
        }

        td:not(.ssi-uploader td) {
            padding: 5px 0px;
        }


        .vertical-line {
            padding-top: 20px;
            padding-bottom: 22px;
            border-left: 2px solid #000;
            /* لون الخط يمكن تغييره حسب تفضيلاتك */
            height: 100%;
            /* يجعل الخط يمتد على طول العنصر */
            margin-right: 10px;
            /* المسافة بين النص والخط الرأسي، يمكن تعديلها حسب الحاجة */

        }
    </style>

    <style>
        .orderPage:nth-child(even) {
            margin-top: 30px;
        }


        @import url('path/to/your/first/style.css');
        @import url('path/to/your/second/style.css');
    </style>

    <style>
        @media print {
            @page {
                size: 10cm 15cm;
                margin: .5cm;
            }

            .address{
                font-size: 8px !important ;
            }

    





        }
    </style>


    <script>
        function print() {
            $('#print').printThis({
                importCSS: true,
                importStyle: true,
            });
        }
    </script>


</head>

<body>




    <div class="content">
        <div class="mt-lg-4 ">

            <div class="bg-white   rounded p-2">

                <button onclick="print()" type="button" class="mainBtn mt-2 mb-4">طباعة البوالص <i
                        class="fa-solid fa-print mx-1"></i>
                </button>

                <div id="print" class="w-100">

                    <div>
                        @foreach ($orders as $order)
                            <div class="orderPage border-wrapper">

                                <div class="qr">
                                    <div style="font-size: 12px">
                                        <?= DNS1D::getBarcodeSVG("$order->reference", 'C128', 1, 45) ?>


                                    </div>
                                </div>


                                <div>
                                    <div class="order_title mt-2" style="font-size: 11px">ملاحظات العميل</div>

                                    <p class="note" >{{ $order->notesBosta }}</p>
{{--
                                    <p  style="font-size: 10px">الرجاء المعاينه وليس القياس او التجربه مع ضمان ثلاثه ايام فقط ضد عيوب الصناعة شرط
                                        الاسترجاع سلامه المنتج والعبوه والفاتوره</p> --}}

                                </div>

                                <hr>

                                <div class="mt-2 category-table-div"
                                    style="min-height:auto  ;padding: 0px ;margin-bottom: 10px">
                                    <table class="w-100  not">

                                        <thead>
                                            <tr>
                                                <th>المنتج</th>
                                                <th>الكمية</th>

                                            </tr>
                                        </thead>

                                        <tbody>

                                            @php
                                                $total = 0;
                                                $totalQnt = 0;
                                            @endphp


                                            @foreach ($order->details as $detail)
                                                <tr>
                                                    <td style="white-space:normal">{{ $detail->discription }}</td>
                                                    <td>{{ $detail->qnt }}</td>
                                                </tr>
                                                @php
                                                    $totalQnt += $detail->qnt;
                                                @endphp
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>

                                <hr>

                                <div>
                                    <div class="client_data row">
                                        <div class="col-6">
                                            <div class="info" style="margin-left:2px">
                                                <div class="order_title">بيانات العميل</div>

                                                <p class="name">{{ $order->clientName }}</p>


                                                <a href="tel:{{ $order->clientPhone }}" class="phone"> <svg
                                                        class="Icon__StyledIcon-sc-178dh1b-0 bwipyB" viewBox="0 0 10 16"
                                                        preserveAspectRatio="xMidYMid meet">
                                                        <path
                                                            d="M8 .667H2c-.92 0-1.667.746-1.667 1.666v11.334c0 .92.747 1.666 1.667 1.666h6c.92 0 1.666-.746 1.666-1.666V2.333C9.666 1.413 8.92.667 8 .667ZM5 14.083a.75.75 0 1 1-.002-1.499.75.75 0 0 1 .002 1.5ZM8.333 12H1.666V2.667h6.667V12Z">
                                                        </path>
                                                    </svg> {{ $order->clientPhone }}</a>

                                                @if (!is_null($order->clientPhone2))
                                                    <a href="tel:{{ $order->clientPhone2 }}" class="phone"> <svg
                                                            class="Icon__StyledIcon-sc-178dh1b-0 bwipyB"
                                                            viewBox="0 0 10 16" preserveAspectRatio="xMidYMid meet">
                                                            <path
                                                                d="M8 .667H2c-.92 0-1.667.746-1.667 1.666v11.334c0 .92.747 1.666 1.667 1.666h6c.92 0 1.666-.746 1.666-1.666V2.333C9.666 1.413 8.92.667 8 .667ZM5 14.083a.75.75 0 1 1-.002-1.499.75.75 0 0 1 .002 1.5ZM8.333 12H1.666V2.667h6.667V12Z">
                                                            </path>
                                                        </svg> {{ $order->clientPhone2 }}</a>
                                                @endif

                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="info m-0">
                                                <div class="order_title">عنوان الشحن</div>

                                                <p class="city"><svg style="width:16px;height:16px"
                                                        viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M15,23H13V21H15V23M19,21H17V23H19V21M15,17H13V19H15V17M7,21H5V23H7V21M7,17H5V19H7V17M19,17H17V19H19V17M15,13H13V15H15V13M19,13H17V15H19V13M21,9A2,2 0 0,1 23,11V23H21V11H11V23H9V15H3V23H1V15A2,2 0 0,1 3,13H9V11A2,2 0 0,1 11,9V7A2,2 0 0,1 13,5H15V1H17V5H19A2,2 0 0,1 21,7V9M19,9V7H13V9H19Z" />
                                                    </svg> {{ $order->city }}</p>

                                                <p class="address" style="font-size: 9px !importnat"> <svg style="width:16px;height:16px"
                                                        viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5M12,2A7,7 0 0,1 19,9C19,14.25 12,22 12,22C12,22 5,14.25 5,9A7,7 0 0,1 12,2M12,4A5,5 0 0,0 7,9C7,10 7,12 12,18.71C17,12 17,10 17,9A5,5 0 0,0 12,4Z" />
                                                    </svg> {{ $order->address }}</p>

                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $data = getOrderData($order->details);
                                    @endphp
                                    <div class="d-flex justify-content-between my-2">

                                        <div class="total">
                                            <div class="order_title ">
                                                <div class="d-flex flex-column "> <span class="m-0"
                                                        style="font-size: 13px">اجمالي الاوردر :</span>
                                                    <span
                                                        class="m-0">{{ $data['total'] + $order->delivery_price }}</span>
                                                </div>
                                            </div>

                                        </div>
                                        <div style="font-size: 12px;margin: 5px">
                                            @if ($order->trackingNumber != null)
                                                <?= DNS1D::getBarcodeSVG("$order->trackingNumber ", 'C128', 1.2, 45) ?>
                                            @endif
                                        </div>
                                    </div>
                                </div>


                            </div>

                            {{-- @if ($loop->index % 2 != 0) --}}
                            <div style='page-break-after:always'></div>
                            {{-- @endif --}}
                        @endforeach


                    </div>
                </div>

            </div>



        </div>
    </div>

    </div>


    @include('admin.inc.scripts')





</body>

</html>
