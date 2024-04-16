@extends('admin/layout')

@section('css')
    <style>
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            padding: 0.4rem 0.75rem !important;

        }

        #example_filter {
            width: 100%;
        }

        label {
            width: 50%;
            white-space: nowrap;
            column-gap: 24px;
        }

        td:not(:last-child) {
            white-space: pre !important;
        }

        @media (max-width:993px) {
            label {
                width: 100%;

            }
        }
    </style>
@endsection


@section('content')
    <x-searchForm action="{{ url('trader/orders/search') }}" id="form">

        @php
            !empty($_GET['product_id']) ? ($product_id = $_GET['product_id']) : ($product_id = '');
            !empty($_GET['date']) ? ($date = $_GET['date']) : ($date = '');
            !empty($_GET['status']) ? ($status = $_GET['status']) : ($status = '');

        @endphp


        <label>اختار المنتج</label>


        <div title="المنتج">
            <select name="product_id" class="js-example-basic-single main" id="product">

                <option value="" selected>كل المنتجات</option>

                @forelse ($Products as $product)
                    <option @selected($product_id == $product->id) name="{{ $product->price }}" value="{{ $product->id }}">
                        {{ $product->name }}</option>
                @empty
                    <option disabled selected>لا يوجد منتجات </option>
                @endforelse


            </select>
        </div>

        <div data-title="كل الحالات">

            <label>حالة الطلب </label>


            <select name="status" class="js-example-basic-single">

                <option value="">كل الحالات</option>

                @foreach (ALL_STATUS() as $state)
                    <option @selected($status == $state) value="{{ $state }}"> {{ $state }} </option>
                @endforeach

            </select>

        </div>

        <div>
            <label>اختار التاريخ</label>

            <div class="mb-3" dir="ltr">
                <input name="date" class="date" type="text" value="{{ $date }}" placeholder="اختار التاريخ">
            </div>
        </div>
    </x-searchForm>

    <div class="actions">
        <div class="contnet-title"> الطلبات علي منتجاتك </div>

        <x-searchBtn></x-searchBtn>
    </div>



    {{-- <div id="search" class="mt-2 mb-2 p-3 rounded  overflow-hidden bg-white" title="البحث">

        <div class="contnet-title mb-3"> ابحث في الطلبات </div>

        <form action="{{ url('trader/orders/search') }}" id="form" class="mb-3 row align-items-center  "
            autocomplete="off">

            @php
                !empty($_GET['product_id']) ? ($product_id = $_GET['product_id']) : ($product_id = '');
                !empty($_GET['date']) ? ($date = $_GET['date']) : ($date = '');
                !empty($_GET['status']) ? ($status = $_GET['status']) : ($status = '');

            @endphp


            <div class="col-lg-4 col-12 mb-lg-0 mb-3" title="المنتج">
                <select name="product_id" class="js-example-basic-single main" id="product">

                    <option value="" selected>كل المنتجات</option>

                    @forelse ($Products as $product)
                        <option @selected($product_id == $product->id) name="{{ $product->price }}" value="{{ $product->id }}">
                            {{ $product->name }}</option>
                    @empty
                        <option disabled selected>لا يوجد منتجات </option>
                    @endforelse

                </select>
            </div>

            <div data-title="كل الحالات" class="col-lg-4 col-12 mb-lg-0   mb-3">

                <select name="status" class="js-example-basic-single">

                    <option value="">كل الحالات</option>
                    <option @selected($status == 'قيد المراجعة') value="قيد المراجعة">قيد المراجعة</option>
                    <option @selected($status == 'تم المراجعة') value="تم المراجعة">تم المراجعة</option>
                    <option @selected($status == 'محاولة تانية') value="محاولة تانية">محاولة تانية</option>
                    <option @selected($status == 'في انتظار ردك') value="في انتظار ردك">في انتظار ردك</option>
                    <option @selected($status == 'جاري التجهيز للشحن') value="جاري التجهيز للشحن">جاري التجهيز للشحن</option>
                    <option @selected($status == 'تم ارسال الشحن') value="تم ارسال الشحن">تم ارسال الشحن</option>
                    <option @selected($status == 'تم التوصيل') value="تم التوصيل">تم التوصيل</option>
                    <option @selected($status == 'جاري التجيهز شحن يدوي') value="جاري التجيهز شحن يدوي">جاري التجيهز شحن يدوي</option>
                    <option @selected($status == 'ارسال شحن يدوي') value="ارسال شحن يدوي">ارسال شحن يدوي</option>
                    <option @selected($status == 'تم التوصيل شحن يودي') value="تم التوصيل شحن يودي">تم التوصيل شحن يودي</option>
                    <option @selected($status == 'قيد الانتظار') value="قيد الانتظار">قيد الانتظار</option>
                    <option @selected($status == 'الغاء من المسوق') value="الغاء من المسوق">الغاء من المسوق</option>
                    <option @selected($status == 'الغاء من خدمة العملاء') value="الغاء من خدمة العملاء">الغاء من خدمة العملاء</option>
                    <option @selected($status == 'الغاء من العميل') value="الغاء من العميل">الغاء من العميل</option>
                    <option @selected($status == 'فشل التوصيل') value="فشل التوصيل">فشل التوصيل</option>
                    <option @selected($status == 'لم يتم تأكيد الطلب') value="لم يتم تأكيد الطلب">لم يتم تأكيد الطلب</option>
                    <option @selected($status == 'فشل التوصيل يدوي') value="فشل التوصيل يدوي">فشل التوصيل يدوي</option>

                </select>

            </div>

            <div class="col-lg-4 col-12 mb-lg-0 mb-3 " dir="ltr">
                <input name="date" class="date" type="text" value="{{ $date }}" placeholder="اختار التاريخ">
            </div>


            <div>
                <x-admin.forms.mainBtn type="submit" title="بحث" class="mt-3"></x-admin.forms.mainBtn>
            </div>



        </form>
    </div> --}}


    <div class="tableSpace">

        @if (Request::is('trader/orders/search'))
            <div class="frameHead my-3">

                <div class="group">
                    <div>عدد الطلبات :</div>
                    <div class="value" id="reference">{{ $orderTotal }}</div>
                </div>

                <div class="group">
                    <div> اجمالي القطع :</div>
                    <div class="value" id="reference">{{ $totalQnt }}</div>
                </div>



                <div class="group">
                    <div> اجمالي المبيعات :</div>
                    <div class="value" id="reference">{{ $total }}</div>
                </div>



            </div>
        @endif



        <table class="not">
            <thead>

                <tr>
                    <th> # </th>
                    <th>رقم الطلب</th>
                    <th>عدد القطع</th>
                    <th>الموصفات</th>
                    <th>تاريخ الطلب</th>
                    <th>حالة الطلب</th>

                </tr>

            </thead>
            <tbody>


                @forelse ($orders as $order)
                    <tr>
                        <td> {{ ($orders->currentpage() - 1) * $orders->perpage() + $loop->index + 1 }} </td>
                        <td>{{ $order->reference }}</td>

                        @php
                            $data = getOrderData($order->details);
                        @endphp

                        <td>{{ $data['qnt'] }}</td>


                        <td>{{ $data['dis'] }}</td>

                        <td>{{ $order->created_at->format('Y-m-d h:i:s A') }}</td>



                        <td><span class="orderStatus {{ StatusClass($order->status) }}">
                                {{ fixStatus($order->status) }}</span> </td>



                    </tr>

                @empty
                    <tr>

                        <td colspan="7" style="text-align: center">لا يوجد بيانات</td>
                    </tr>
                @endforelse

            </tbody>


        </table>

    </div>

    <div class="pagnate" class="السابق والتالي">
        {{ $orders->appends(request()->query())->links() }}
    </div>
@endsection




@section('js')
    <script>
        $('#aside .orders').addClass('active');
        tippy('[data-tippy-content]');

        $('.js-example-basic-single').select2();


        flatpickr('input.date', {
            enableTime: false,
            mode: "range",
            dateFormat: "Y-m-d"
        });
    </script>
@endsection
