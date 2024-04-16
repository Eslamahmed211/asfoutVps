@extends('admin/layout')



@section('css')
    <style>
        .contnet-title {
            font-size: 16px;
            color: rgb(30, 41, 53);
            /* color: red; */
            font-weight: 700;
            width: 100%;
            border-right: solid 4px #ddd;
            background: #f5f5f5;
            padding: 8pt;
            border-radius: 2pt;
            font-family: "cairo";

        }
    </style>
@endsection



@section('path')
    <ul class="paths">
        <li> <a href="{{ url('users/moderators') }}"> الموديتور / </a> </li>
        <li class="active">طلبات السحب </li>
    </ul>
@endsection


@section('content')
    <div class="mt-0 tableSpace p-0  ">


        <table id="example" class="not">
            <thead>

                <tr>
                    <th> # </th>
                    <th> الموديتور </th>
                    <th>المبلغ</th>
                    <th> الحالة </th>
                    <th> جهة الصرف </th>
                    <th> البيانات </th>
                    <th> تاريخ السحب </th>
                    <th> تاريخ الدفع </th>
                    <th> اجرءات </th>

                </tr>

            </thead>
            <tbody>


                @forelse ($withdrows as $withdrow)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>

                        <td>{{ $withdrow->user->name }} <br> {{ $withdrow->user->mobile }}</td>

                        <td data-text='المبلغ'> {{ $withdrow->amount }}</td>
                        <td data-text='الحالة'> {{ $withdrow->status }}</td>
                        <td data-text=' جهة الصرف '>
                            {{ $withdrow->type == 'cash' ? 'حساب كاش' : 'حساب بنكي' }} </td>
                        <td data-text="البيانات">
                            @php $options = json_decode($withdrow->options)@endphp
                            @if ($withdrow->type == 'cash')
                                {{ $options->mobile }}
                            @else
                                {{ $options->name }} <br> {{ $options->bank_account_id }}
                            @endif
                        </td>

                        <td>

                            {{ fixdata($withdrow->created_at) }}

                        </td>

                        <td>
                            {{ fixdata($withdrow->paid_at) }}

                        </td>
                        <td>
                            @if (!$withdrow->paid_at)
                                <button onclick="paid({{ $withdrow->id }})" class="mainBtn small">تسديد</button>
                            @endif

                        </td>


                    </tr>

                @empty
                    <tr>

                        <td colspan="9" style="text-align: center">لا يوجد طلبات سحب</td>
                    </tr>
                @endforelse

            </tbody>


        </table>


    </div>


    <div class="mt-3">
        <x-admin.forms.buttonLink path="/users/moderators" title="رجوع" icon="back"></x-admin.forms.buttonLink>
    </div>
@endsection


@section('js')
    <script>
        $('#aside .moderators').addClass('active');
        tippy('[data-tippy-content]');

        function paid($id) {
            Swal.fire({
                title: 'هل انت متاكد من تسديد طلب  السحب ؟',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'تسديد',
                denyButtonText: `اغلاق`,
                focusDeny: true,

            }).then((result) => {
                if (result.isConfirmed) {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/users/moderators/withdraws`;
                    var input1 = document.createElement('input');
                    input1.type = 'text';
                    input1.name = 'id';
                    input1.value = `${$id}`;
                    form.appendChild(input1);
                    var input2 = document.createElement('input');

                    input2.type = 'hidden';
                    input2.name = '_token';
                    input2.value = "{{ csrf_token() }}";
                    form.appendChild(input2);
                    document.body.appendChild(form);
                    form.submit();
                }
            })
        }
    </script>

    @if (isset($withdrows[0]))
        <script>
            var table = new DataTable('#example', {

                paging: false,


                "language": {

                    "paginate": {
                        "first": "الاول",
                        "last": "الاخير",
                        "next": "التالي",
                        "previous": "السابق"
                    },
                    info: '',
                    infoEmpty: '',
                    zeroRecords: 'لا يوجد طلبات سحب ',
                    infoFiltered: "",
                    search: "",
                    "searchPlaceholder": "ابحث في طلبات السحب",
                    sLengthMenu: "عرض _MENU_"
                }
            });

            $('.paginate_button.next', table.table().container()).addClass('xbutton');
        </script>
    @endif
@endsection
