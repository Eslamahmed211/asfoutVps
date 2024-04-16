@extends('admin.layout')




@section('content')

    <x-searchForm action="{{ url('users/orders/order_commissions/search') }}">
        @php
            !empty($_GET['date']) ? ($date = $_GET['date']) : ($date = '');
            !empty($_GET['track']) ? ($track = $_GET['track']) : ($track = '');
            !empty($_GET['withModerators']) ? ($withModerators = $_GET['withModerators']) : ($withModerators = '');

        @endphp

        <label for="date">اختار التاريخ</label>
        <div dir="ltr">
            <input name="date" class="date" type="text" value="{{ $date }}" placeholder="اختار التاريخ">
        </div>


        <div class="mt-3" data-title="تتبع">
            <label for="date">نوع البحث</label>

            <select name="track">
                <option @selected($track == 'commission') value="commission">تاريخ اضافه العموله</option>
                <option @selected($track == 'order') value="order">تاريخ اضافه الطلب</option>
            </select>

        </div>

        @if (auth()->user()->role == 'user')
            <div data-title="كل الطلبات">
                <label for="date">نوع الطلبات</label>

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
        @endif

    </x-searchForm>

    <div class="actions mt-0">

        <div class="contnet-title">عمولات الطلبات ( {{ $all_count->sum('commission') }} ) </div>

        <div class="d-flex align-items-center gap-2">

            <x-searchBtn></x-searchBtn>

        </div>
    </div>

    <div class="tableSpace">
        <table>
            <thead>
                <tr>


                    <th>#</th>
                    <th>المستخدم</th>
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



                        <td>{{ $i->user->name }}</td>
                        <td> <a target="_blank" href="/users/orders/{{ $i->order_id }}/show">{{ $i->order->reference }}</a>
                        </td>
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
        $('aside .order_commissions').addClass('active');
        tippy('[data-tippy-content]');

        $('.js-example-basic-single').select2();

        flatpickr('input.date', {
            enableTime: false,
            mode: "range",

            dateFormat: "Y-m-d"
        });
    </script>
@endsection
