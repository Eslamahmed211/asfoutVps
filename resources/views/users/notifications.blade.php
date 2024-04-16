@extends('admin.layout')


@section('css')
    <style>
        table:not(.ssi-imgToUploadTable) tr:nth-child(even) td {
            background-color: white;
        }
    </style>
@endsection

@section('path')
    <ul class="paths">
        <li> <a href="{{ url('users/home') }}"> الرئيسية / </a> </li>
        <li class="active">كل الاشعارات</li>
    </ul>
@endsection


@section('content')
    <div>

        <div class="tableSpace" id="inventor">
            <table id="example" class="not">
                <thead>

                    <tr>

                        <th> المحتوي </th>

                        <th>التاريخ</th>
                        <th> من </th>

                    </tr>

                </thead>

                <tbody>
                    @foreach (auth()->user()->Notifications as $notification)
                        @if ($notification->data['type'] == 'orderStatus')
                            <tr data-link="/users/orders/{{ $notification->data['order_id'] }}/show"
                                onclick="read2(this , '{{ $notification->id }}')" style="cursor: pointer">



                                <td @if ($notification->read_at == null) style="background: #f2f2f2" @endif
                                    data-text='المحتوي'>
                                    {{ $notification->data['message'] }} </td>


                                <td>{{ fixdata($notification->created_at) }}</td>


                                <td @if ($notification->read_at == null) style="background: #f2f2f2" @endif data-text='الوقت'>
                                    {{ timeFormat($notification->created_at) }} </td>




                            </tr>
                        @elseif($notification->data['type'] == 'user_withdrow')
                            <tr data-link="/admin/users/withdraws"
                                onclick="read2(this , '{{ $notification->id }}' , 'admin')" style="cursor: pointer">

                                <td @if ($notification->read_at == null) style="background: #f2f2f2" @endif
                                    data-text='المحتوي'>
                                    {{ $notification->data['message'] }} </td>

                                    <td>{{ fixdata($notification->created_at) }}</td>


                                <td @if ($notification->read_at == null) style="background: #f2f2f2" @endif data-text='الوقت'>
                                    {{ timeFormat($notification->created_at) }} </td>

                            </tr>
                        @elseif($notification->data['type'] == 'order_note')
                            <tr data-link="/users/orders/{{ $notification->data['id'] }}/show"
                                onclick="read2(this , '{{ $notification->id }}')" style="cursor: pointer">

                                <td @if ($notification->read_at == null) style="background: #f2f2f2" @endif
                                    data-text='المحتوي'>
                                    {{ $notification->data['message'] }} </td>

                                    <td>{{ fixdata($notification->created_at) }}</td>


                                <td @if ($notification->read_at == null) style="background: #f2f2f2" @endif data-text='الوقت'>
                                    {{ timeFormat($notification->created_at) }} </td>

                            </tr>
                        @endif
                    @endforeach
                </tbody>

            </table>

        </div>

    </div>


    <div class="mt-3" style="width: fit-content">
        <x-admin.forms.buttonLink path="/users/settings" title="رجوع" icon="back"></x-admin.forms.buttonLink>
    </div>
@endsection


@section('js')
    <script>
        $('aside .notifications').addClass('active');
    </script>

    <script>
        var table = new DataTable('#example', {

            "language": {

                "paginate": {
                    "first": "الاول",
                    "last": "الاخير",
                    "next": "التالي",
                    "previous": "السابق"
                },
                info: '',
                infoEmpty: '',
                zeroRecords: 'لا يوجد  اشعارات ',
                infoFiltered: "",
                search: "",
                "searchPlaceholder": "ابحث في الاشعارات",
                sLengthMenu: "عرض _MENU_"
            },


        });

        $('.paginate_button.next', table.table().container()).addClass('xbutton');
    </script>
@endsection
