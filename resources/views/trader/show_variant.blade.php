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
            width: 100%;
            white-space: nowrap;
            column-gap: 24px;
        }
    </style>
@endsection



@section('content')
    <div class="tableSpace">
        <div class="contnet-title text-center ">
            توزيعات خصائص {{ $product->name }} </div>


        <table class="example not">
            <thead>

                <tr>
                    <th> اسم المنتج </th>

                    <th>اجمالي المستلم ( {{ $TOTAL_COLLECTION }} ) </th>
                    <th>المخزون الحالي ( {{ $product->stock }} ) </th>

                    <th>قيد المراجعة ( {{ $product_status['قيد المراجعة'] }} )</th>
                    <th>محاولة تانية ( {{ $product_status['محاولة تانية'] }} )</th>
                    <th>تم المراجعة ( {{ $product_status['تم المراجعة'] }} )</th>



                    <th>جاري التجهيز ( {{ $product_status['جاري التجهيز للشحن'] }} )</th>

                    <th>تم الشحن ( {{ $product_status['تم ارسال الشحن'] }} )</th>

                    <th>تم التوصيل ( {{ $product_status['تم التوصيل'] }} )</th>

                    <th> طلب استرجاع ( {{ $product_status["طلب استرجاع"] }} )</th>

                </tr>

            </thead>
            <tbody>

                <tr>


                    @foreach ($status as $state)
                        <td>{{ $state['product_name'] }}</td>

                        <td>{{ $state['TOTAL_COLLECTION_VARIANT'] }}</td>
                        <td>{{ $state['variant']->stock }}</td>
                        <td>{{ $state['status']['قيد المراجعة'] }}</td>
                        <td>{{ $state['status']['محاولة تانية'] }}</td>
                        <td>{{ $state['status']['تم المراجعة'] }}</td>
                        <td>{{ $state['status']['جاري التجهيز للشحن'] }}</td>
                        <td>{{ $state['status']['تم ارسال الشحن'] }}</td>
                        <td>{{ $state['status']['تم التوصيل'] }}</td>
                        <td>{{ $state['status']['طلب استرجاع'] }}</td>


                </tr>
                @endforeach


                </tr>




            </tbody>
        </table>


        <table class="example not">
            <thead>

                <tr>
                    <th> اسم المنتج </th>

                    <th>طلبات انتظار ( {{ $product_status['قيد الانتظار'] }} )</th>
                    <th> تم الالغاء ( {{ $product_status['تم الالغاء'] }} )</th>
                    <th> مرتجع ( {{ $product_status['فشل التوصيل'] }} )</th>

                </tr>

            </thead>
            <tbody>

                <tr>


                    @foreach ($status as $state)
                        <td>{{ $state['product_name'] }}</td>


                        <td>{{ $state['status']['قيد الانتظار'] }}</td>
                        <td>{{ $state['status']['تم الالغاء'] }}</td>
                        <td>{{ $state['status']['فشل التوصيل'] }}</td>



                </tr>
                @endforeach


                </tr>




            </tbody>
        </table>



    </div>
@endsection




@section('js')
    <script>
        $('#aside .products').addClass('active');
        tippy('[data-tippy-content]');
    </script>

    <script>
        var table = new DataTable('.example', {

            "paging": false,

            "language": {

                "paginate": {
                    "first": "الاول",
                    "last": "الاخير",
                    "next": "التالي",
                    "previous": "السابق"
                },
                info: ' ',
                infoEmpty: '',
                zeroRecords: 'لا يوجد بيانات',
                infoFiltered: "",
                search: "فلترة النتائج",
                sLengthMenu: "عرض _MENU_"
            }
        });
    </script>
@endsection
