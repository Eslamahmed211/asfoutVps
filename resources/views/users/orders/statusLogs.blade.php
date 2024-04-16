@extends('admin.layout')


@section('path')
    <ul class="paths">
        <li> <a href="{{ url('users/orders') }}"> الطلبات / </a> </li>
        <li> <a href="{{ url("users/orders/$order->id/show") }}"> {{ $order->clientName }} / </a> </li>
        <li class="active"> تتبع حالات الطلب </li>
    </ul>
@endsection



@section('content')
    <div class=" mt-lg-2   mb-4  ">
        <div class=" py-l-4 py-3 px-2 rounded position-relative " style="min-height: 60vh; overflow-x: scroll">

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




                            <td> {{ userName($log['user'])->mobile }}</td>


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
    </div>
    <x-admin.forms.buttonLink path="/users/orders" title="رجوع" icon="back"></x-admin.forms.buttonLink>
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
