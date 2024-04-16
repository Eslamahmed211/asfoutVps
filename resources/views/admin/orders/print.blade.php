@extends('admin.layout')


@section('css')
    <style>
        td {
            text-align: center;
        }

        .qr {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .info {
            background-color: #f5f5f7 !important;
            padding: 20px;
            border-radius: 2px;
            margin-bottom: 15px;
        }

        hr {
            margin: .5rem 0;
            color: inherit;
            border: 0;
            border-top: 1px solid;
            opacity: .25;
        }

        .order_title {
            color: rgb(37, 42, 49);
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 5px;
            border-bottom: 1px solid rgba(128, 128, 128, 0.26);
            padding-bottom: 5px;
            margin-right: 5px;
        }

        table {
            border-collapse: separate;
            border-spacing: 2px;
        }

        .total {
            margin: 5px 0px;

        }

        .total span {
            margin: 5px 0px;
            font-weight: 700;
            font-size: 12px;
            color: rgba(0, 0, 0, 0.8);
        }

        .total .order_title {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        #print {
            width: 100% !important;
            direction: rtl;
            text-align: center;
            font-weight: 500;
            font-family: 'Cairo', sans-serif;
            width: 209mm;

        }

        table {
            border: 2px solid #F8F8F8;
            margin: auto;
            color: #626262;
            width: 100vw !important;
            text-align: center
        }

        thead {
            border-color: inherit;
            background-color: #f8f8f8;
            text-align: center;
            -webkit-print-color-adjust: exact;

        }

        tr {
            border-bottom: 1px solid #F3F2F7;
            background: white;
            font-size: 14px;
            text-align: center;
            -webkit-print-color-adjust: exact;

        }

        td:not(.ssi-uploader td) {
            padding: .5rem;
            color: #626262;
            font-weight: 500;
            font-size: 12px;
            white-space: nowrap;
        }

        th,
        td {
            font-size: 12px;
            text-align: center
        }

        .info {
            background-color: #f5f5f7 !important;
            -webkit-print-color-adjust: exact;
            width: 50% !important;
            padding: 10px;
            border-radius: 2px;
            margin-bottom: 0px;
            border-bottom: 1px solid #F3F2F7;
        }

        .client_data p,
        .client_data a,
        .client_data .note {
            margin: 0px;
            color: rgb(95, 115, 140);
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            margin-right: 10px;
            margin-bottom: 5px;
            cursor: pointer;
            -webkit-print-color-adjust: exact;

        }

        .client_data {
            display: flex !important;
            margin-bottom: 0px
        }

        .total {
            text-align: right;
        }

        .bold {
            font-weight: 700 !important;
            color: black !important;
        }

        tfoot {
            border-top: 2px solid rgba(37, 42, 49, 0.4) !important;
        }
    </style>

    <style>
        .orderPage:nth-child(even) {
            margin-top: 30px;
        }

        @media print {
            @page {
                margin: .5cm;
            }
        }
    </style>

@endsection


@section('content')
    <div class="mt-lg-4 ">

        <div class="bg-white   rounded p-2">

            <button onclick="print()" type="button" class="mainBtn mt-2 mb-4">طباعة البوالص <i
                    class="fa-solid fa-print mx-1"></i>
            </button>

            <div id="print" class="w-100">

                @foreach ($orders as $order)
                    <div class="orderPage">
                        <div class="qr mx-2">
                            <div>
                                {{-- C39 --}}
                                <?= DNS1D::getBarcodeSVG("$order->reference", 'C128', 1.2, 50) ?>
                                {{-- <div style="font-size: 12px"> {{ $order->city }}</div> --}}
                            </div>
                            <div>
                                <div class="order_title mt-2">ملاحظات العميل</div>
                                @if ($order->notesBosta != '')
                                    <p class="note">{{ $order->notesBosta }}</p>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="mt-2 category-table-div" style="min-height:auto  ;padding: 0px ;margin-bottom: 10px">
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

                                {{-- <tfoot>
                                    <tr>
                                        <td class="bold">اجمالي القطع</td>
                                        <td class="bold">{{ $totalQnt }}</td>
                                    </tr>
                                </tfoot> --}}
                            </table>
                        </div>

                        <hr>

                        <div class="client_data">
                            <div class="info" style="margin-left: 10px">
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
                                            class="Icon__StyledIcon-sc-178dh1b-0 bwipyB" viewBox="0 0 10 16"
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
                                            d="M15,23H13V21H15V23M19,21H17V23H19V21M15,17H13V19H15V17M7,21H5V23H7V21M7,17H5V19H7V17M19,17H17V19H19V17M15,13H13V15H15V13M19,13H17V15H19V13M21,9A2,2 0 0,1 23,11V23H21V11H11V23H9V15H3V23H1V15A2,2 0 0,1 3,13H9V11A2,2 0 0,1 11,9V7A2,2 0 0,1 13,5H15V1H17V5H19A2,2 0 0,1 21,7V9M19,9V7H13V9H19Z" />
                                    </svg> {{ $order->city }}</p>

                                <p class="address"> <svg style="width:16px;height:16px" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5M12,2A7,7 0 0,1 19,9C19,14.25 12,22 12,22C12,22 5,14.25 5,9A7,7 0 0,1 12,2M12,4A5,5 0 0,0 7,9C7,10 7,12 12,18.71C17,12 17,10 17,9A5,5 0 0,0 12,4Z" />
                                    </svg> {{ $order->address }}</p>

                            </div>

                        </div>
                        @php
                            $data = getOrderData($order->details);
                        @endphp

                        <div class="total mb-4">
                            <div class="order_title mt-2 ">
                                <div style="font-size: 14px"> اجمالي الاوردر :
                                    {{ $data['total'] + $order->delivery_price }}
                                </div>
                                <span>يسمح للعميل بفتح الشحنة مع الحفاظ عليها</span>
                            </div>

                            <div style="font-size: 12px;margin: 5px;text-align: left">
                                @if ($order->trackingNumber != null)
                                    <?= DNS1D::getBarcodeSVG("$order->trackingNumber ", 'C128', 1.2, 45) ?>
                                @endif
                            </div>

                        </div>
                        @if ($loop->index % 2 == 0)
                            <hr style="border:5px solid black">
                        @endif



                    </div>

                    @if (count($order->details) >= 3)
                        <div style='page-break-after:always'></div>
                    @elseif($loop->index % 2 != 0)
                        <div style='page-break-after:always'></div>
                    @endif
                @endforeach


            </div>

        </div>



    </div>
@endsection


@section('js')
    <script>
        $('aside .delivery_manual').addClass('active');
    </script>

    <script>
        function print() {

            $('#print').printThis({

                importCSS: true,
                importStyle: true,
            });
        }
    </script>
@endsection
