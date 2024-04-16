@extends('admin/layout')

@section('css')
    <style>
        .w-div {
            color: #7a7b97 !important;
            background-color: #f5f5f7 !important;
            border-radius: 0.2rem;
            padding: 10px 15px;
            margin-bottom: 10px;
            text-decoration: none;
            width: fit-content;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;

        }

        .w-div span {
            font-size: 18px !important;
            font-weight: 600
        }
    </style>
@endsection

@php
    if (auth()->user()->role == 'user' || auth()->user()->role == 'moderator') {
        $type = 'users';
    } elseif (auth()->user()->role == 'trader') {
        $type = 'trader';
    }

@endphp

@section('content')
    <div class="actions">


        <div class="contnet-title">  لديك بالمحفظة  (  {{ auth()->user()->wallet }} )    </div>

        <div class="d-flex align-items-center gap-2">



            <x-admin.forms.mainBtn onclick="show()" title="اطلب صرف عمولة" type="button mx-3"></x-admin.forms.mainBtn>

        </div>




    </div>





    <div class="tableSpace p-0  ">


        <table id="example" class="not">
            <thead>

                <tr>
                    <th> # </th>
                    <th>المبلغ</th>
                    <th> الحالة </th>
                    <th> جهة الصرف </th>
                    <th> البيانات </th>
                    <th> تاريخ السحب </th>
                    <th> تاريخ الدفع </th>

                </tr>

            </thead>
            <tbody>


                @forelse ($withdrows as $withdrow)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>

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
                            @if ($withdrow->paid_at)
                                {{ fixdata($withdrow->paid_at) }}
                            @else
                            @endif

                        </td>



                    </tr>

                @empty
                    <tr>

                        <td colspan="7" style="text-align: center">لا يوجد طلبات سحب</td>
                    </tr>
                @endforelse

            </tbody>


        </table>


    </div>
@endsection




@section('js')
    <script>
        $('#aside .wallet').addClass('active');
        tippy('[data-tippy-content]');
    </script>

    <script>
        function show() {






            Swal.fire({
                title: "طلب سحب عمولة",
                html: `  <form method="post" action="{{ url("$type/wallet") }}" >
                  @csrf

                  <div data-title="المبلغ" class="w-100">
                      <x-admin.forms.input for="amount" type="number" lable_title="المبلغ" min="0" name="amount">
                      </x-admin.forms.input>
                  </div>


                  <div data-title="طريقة السحب" class="w-100 mb-3  ">


                      <label for="type" class="mb-2"> طريقة السحب </label>


                      <select name="type">

                          @foreach (auth()->user()->paymentMethods as $paymentMethod)
                              <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->title }}</option>
                          @endforeach

                      </select>


                  </div>


                  <x-admin.forms.mainBtn type="submit" title="طلب سحب"></x-admin.forms.mainBtn>


              </form>`,
                focusConfirm: false,
                showConfirmButton: false,
                inputAutoFocus: false
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
