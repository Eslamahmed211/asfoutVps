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
@if (Request::is('trader/product_percent'))
    <x-searchForm action="{{ url('trader/product_percent') }}" id="form">

        @php
            !empty($_GET['product_id']) ? ($product_id = $_GET['product_id']) : ($product_id = '');
            !empty($_GET['date']) ? ($date = $_GET['date']) : ($date = '');
        @endphp

        <label>اختار المنتج</label>


        <div title="المنتج">
            <select name="product_id" class="js-example-basic-single main" id="product">

                <option value="" selected>كل المنتجات</option>

                @forelse ($Products as $product)
                    <option @selected($product_id == $product->id) name="{{ $product->price }}" value="{{ $product->id }}">
                        {{ $product->name }}</option>
                @empty
                    <option disabled selected>لا يوجد منتجات </option>
                @endforelse

            </select>
        </div>

        <label>اختار التاريخ</label>


        <div class="mb-3" dir="ltr">
            <input name="date" class="date" type="text" value="{{ $date }}" placeholder="اختار التاريخ">
        </div>
    </x-searchForm>

    <div class="actions">


        <div class="contnet-title">تسليمات المنتجات </div>

        <x-searchBtn></x-searchBtn>

    </div>
@endif

    <div class=" tableSpace ">

        <table id="example" class=" mt-0">
            <thead>

                <tr>
                    <th> # </th>
                    <th>المنتج</th>
                    <th>طلبات تم توصيلها</th>
                    <th>عدد القطع المسلمه</th>
                    <th>طلبات مرتجعه</th>
                    <th>عدد القطع المرتجعه</th>
                    <th>نسبه التسليم</th>

                </tr>

            </thead>
            <tbody>


                @forelse ($all as $product)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>

                        @if ($product['variants_count'] > 0)
                            <td><a target="_blank"
                                    href="/trader/variant_percent/{{ $product['product_id'] }}">{{ $product['product'] }}</a>
                            </td>
                        @else
                            <td>{{ $product['product'] }}</td>
                        @endif

                        <td>{{ $product['ordersDeliverdCount'] }}</td>
                        <td>{{ $product['DeliverdQnt'] }}</td>
                        <td>{{ $product['DeliveryFailureCount'] }}</td>
                        <td>{{ $product['DeliveryFailureQnt'] }}</td>
                        <td>{{ $product['precent'] }} % </td>

                    </tr>

                @empty
                    <tr>

                        <td colspan="7" style="text-align: center">لا يوجد بيانات</td>
                    </tr>
                @endforelse

            </tbody>


        </table>

    </div>
@endsection




@section('js')
    <script>
        $('#aside .product_percent').addClass('active');

        $('.js-example-basic-single').select2();

        tippy('[data-tippy-content]');

        flatpickr('input.date', {
            enableTime: false,
            mode: "range",

            dateFormat: "Y-m-d"
        });
    </script>

    @if (isset($all[0]))
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
