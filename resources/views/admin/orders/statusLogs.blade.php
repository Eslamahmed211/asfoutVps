@extends('admin.layout')


@section('css')
    <style>
        .firstRow {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .key {
            font-weight: 700;
            font-size: 15px;
        }

        .value {
            color: var(--mainColor);
            font-weight: 700;
            font-size: 15px;
        }
    </style>
@endsection

@section('path')
    <ul class="paths">
        <li> <a href="{{ url('admin/orders') }}"> الطلبات / </a> </li>
        <li> <a href="{{ url("admin/orders/$order->id/show") }}"> {{ $order->clientName }} / </a> </li>
        <li class="active"> تتبع حالات الطلب </li>
    </ul>
@endsection



@section('content')
    <div style="text-align: left" title="الزرار">

        <div data-title="اضافة رسالة جديدة" style="text-align: left">
            <x-admin.forms.mainBtn onclick="addMessage()" title="اضافة رسالة جديدة" icon="plus">
            </x-admin.forms.mainBtn>
        </div>

    </div>

    <div class=" py-l-4 py-3 px-2 rounded position-relative " style="min-height: 50vh; overflow-x: scroll">

        <div class="firstRow">

            <div class="about"> <span class="key">الرقم المرجعي</span> : <span
                    class="value">{{ $order->reference }}</span></div>
            <div class="about"> <span class="key">اسم العميل</span> : <span
                    class="value">{{ $order->clientName }}</span></div>
            <div class="about"> <span class="key">رقم العميل</span> : <span
                    class="value">{{ $order->clientPhone }}</span></div>

            <div class="about"> <span class="key">رقم الشحنة</span> : <span
                    class="value">{{ $order->trackingNumber }}</span></div>


            <div class="about"> <span class="key">طلبات الحالية</span> : <span
                    class="value">{{ count_order($order->clientPhone) }}</span></div>



        </div>

        <table class="not ">
            <thead>
                <tr>
                    <th>الرسالة</th>
                    <th>بواسطة</th>
                    <th>الوقت</th>
                    <th>من</th>
            </thead>
            <tbody>

                @forelse ($order->logs as $log)
                    <tr>

                        @if ($log['type'] == 'message')
                            <td>
                                {{ $log['message'] }}
                            </td>
                        @else
                            <td>
                                <span>تم تغير حالة الطلب من <span
                                        class="orderStatus  {{ StatusClass($log['oldStatus']) }}">{{ $log['oldStatus'] }}</span></span>
                                <span> الي <span
                                        class=" orderStatus  {{ StatusClass($log['newStatus']) }}">{{ $log['newStatus'] }}</span></span>
                            </td>
                        @endif




                        <td> {{ userName($log['user'])->name }} <br> {{ userName($log['user'])->mobile }}</td>


                        @php
                            $dateTime = $log['date'];
                            $dateTimeObj = new DateTime($dateTime);
                            $date = $dateTimeObj->format('Y-m-d');
                            $time = $dateTimeObj->format('H:i:s');
                        @endphp

                        <td class="text-center">
                            {{ $date }} <br> {{ $time }}
                        </td>

                        <Td>
                            {{ timeFormat($dateTime) }}
                        </Td>










                    </tr>
                @empty

                    <td colspan="4" class="text-center">لا يوجد اي تغيرات في حالة الطلب</td>
                @endforelse



            </tbody>

        </table>
    </div>
    <x-admin.forms.buttonLink path="/admin/orders" title="رجوع" icon="back"></x-admin.forms.buttonLink>
@endsection

@section('js')
    <script>
        $('aside .orders').addClass('active');
        tippy('[data-tippy-content]');

        function addMessage() {

            Swal.fire({
                title: "اضافة رسالة جديدة",
                html: `  <form method="post" action="{{ url("admin/orders/$order->id/logs") }}" >
                  @csrf

                  <div data-title="المبلغ" class="w-100">

                    <textarea name="message" required ></textarea>

                  </div>


                  <x-admin.forms.mainBtn type="submit" title="اضافة"></x-admin.forms.mainBtn>


              </form>`,
                focusConfirm: false,
                showConfirmButton: false,
                inputAutoFocus: false
            })
        }
    </script>
@endsection
