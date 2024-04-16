@extends('admin.layout')



@section('content')
    <div class="actions mt-0">

        <div class="contnet-title">عمولات الطلبات </div>

        <div class="d-flex align-items-center gap-2">

            <x-searchBtn></x-searchBtn>

        </div>
    </div>

    <x-searchForm action="{{ url('admin/orders/order_commissions/search') }}">
        @php
            !empty($_GET['date']) ? ($date = $_GET['date']) : ($date = '');
            !empty($_GET['track']) ? ($track = $_GET['track']) : ($track = '');
        @endphp

        <div data-title="المسوق">

            <label>اختار المسوق</label>

            <select name="user_id" class="js-example-tags w-100">
                <option value> كل المسوقين</option>

                @if (isset($user))
                    <option selected value="{{ $user->id }}"> {{ $user->name }}</option>
                @endif

            </select>


        </div>


        <label>اختار التاريخ</label>

        <div dir="ltr" class="mb-3 ">

            <input name="date" class="date" type="text" value="{{ $date }}" placeholder="اختار التاريخ">
        </div>

        <label>نوع التاريخ</label>

        <div data-title="تتبع">

            <select name="track">

                <option @selected($track == 'commission') value="commission">تاريخ اضافه العموله</option>
                <option @selected($track == 'order') value="order">تاريخ اضافه الطلب</option>
            </select>

        </div>


    </x-searchForm>



    <div class="tableSpace">


        <table>
            <thead>
                <tr>


                    <th>#</th>
                    <th>المسوق</th>
                    <th>المودريتور</th>
                    <th>كود الاوردر</th>
                    <Th>رقم الشحنة</Th>
                    <th>العمولة </th>
                    <th>تاريخ الطلب </th>
                    <th> تاريخ اضافة العمولة </th>

                </tr>
            </thead>
            <tbody>

                @foreach ($all as $i)
                    <tr>
                        <td> {{ ($all->currentpage() - 1) * $all->perpage() + $loop->index + 1 }} </td>



                        <td> {{ $i->order->user->role == 'user' ? $i->order->user->name : App\Models\User::find($i->order->user->marketer_id)->name }}
                            <br>
                            {{ $i->order->user->role == 'user' ? $i->order->user->mobile : App\Models\User::find($i->order->user->marketer_id)->mobile }}
                        </td>


                        <td> {{ $i->order->user->role == 'moderator' ? $i->order->user->name : '' }} <br>
                            {{ $i->order->user->role == 'moderator' ? $i->order->user->mobile : '' }} </td>



                        <td> {{ $i->order->reference }} </td>
                        <td> {{ $i->order->trackingNumber }} </td>
                        <td> {{ $i->commission }} </td>
                        <td> {{ fixData($i->order->created_at) }} </td>
                        <td> {{ fixData($i->created_at) }} </td>
                    </tr>
                @endforeach

            </tbody>

        </table>
    </div>

    <div class="pagnate" class="السابق والتالي">
        {{ $all->appends(request()->query())->links() }}
    </div>
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
@endsection
