@extends('admin/layout')

@section('css')
    <style>
        thead th,
        thead td {
            text-align: center !important;
            padding: .8em !important;
        }
    </style>
@endsection


@section('content')
    <div class="tableSpace">
        <div class="contnet-title text-center ">
            توزيعات {{ $product->name }} </div>


        <table >
            <thead>

                <tr>
                    <th> اسم المنتج </th>

                    <th>اجمالي  المستلم</th>
                    <th>المخزون  الحالي</th>

                    <th>قيد  المراجعة</th>
                    <th>محاولة  تانية</th>
                    <th>تم  المراجعة</th>

                    <th>جاري  التجهيز</th>

                    <th>تم  الشحن</th>
                    <th>تم  التوصيل</th>
                    <th>مكتمل</th>
                    <th>طلب استرجاع</th>

                </tr>

            </thead>
            <tbody>

                <tr>
                    <td>{{ $product->name }}</td>

                    <td>{{ $TOTAL_COLLECTION }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $status['قيد المراجعة'] }}</td>
                    <td>{{ $status['محاولة تانية'] }}</td>
                    <td>{{ $status['تم المراجعة'] }}</td>
                    <td>{{ $status['جاري التجهيز للشحن'] }}</td>
                    <td>{{ $status['تم ارسال الشحن'] }}</td>
                    <td>{{ $status['تم التوصيل'] }}</td>
                    <td>{{ $status['مكتمل'] }}</td>
                    <td>{{ $status['طلب استرجاع'] }}</td>


                </tr>




            </tbody>


        </table>

        <table >
            <thead>

                <tr>
                    <th>طلبات انتظار</th>
                    <th>تم الالغاء</th>
                    <th> مرتجع</th>
                </tr>

            </thead>

            <tbody>
                <tr>
                    <td>{{ $status['قيد الانتظار'] }}</td>
                    <td>{{ $status['تم الالغاء'] }}</td>
                    <td>{{ $status['فشل التوصيل'] }}</td>

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
@endsection
