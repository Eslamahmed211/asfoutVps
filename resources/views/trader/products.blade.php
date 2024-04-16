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

        @media (max-width:993px) {
            label {
                width: 100%;

            }
        }
    </style>
@endsection


@section('content')
    <div class="tableSpace ">


        <table id="example" class="not">
            <thead>

                <tr>
                    <th> # </th>
                    <th> المنتج</th>
                    <th>السعر</th>
                    <th>الكمية</th>


                </tr>

            </thead>
            <tbody>


                @forelse ($products as $product)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>

                        <td> <a target="_blank" href="/trader/products/{{ $product->id }}">{{ $product->name }}</a> </td>

                        <td>{{ $product->price }}</td>
                        <td>{{ $product->stock }}</td>


                    </tr>

                @empty
                    <tr>

                        <td colspan="3" style="text-align: center">لا يوجد بيانات</td>
                    </tr>
                @endforelse

            </tbody>


        </table>

    </div>
@endsection




@section('js')
    <script>
        $('#aside .products').addClass('active');
        tippy('[data-tippy-content]');

        flatpickr('input.date', {
            enableTime: false,
            mode: "range",

            dateFormat: "Y-m-d"
        });
    </script>

    @if (isset($products[0]))
        <script>
            var table = new DataTable('#example', {

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

            $('.paginate_button.next', table.table().container()).addClass('xbutton');
        </script>
    @endif
@endsection
