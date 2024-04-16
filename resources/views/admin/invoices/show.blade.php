@extends('admin.layout')

@section('title')
    <title>{{ $invoice->InvoiceName }}</title>
@endsection

@section('css')
    <style type="text/css">
        @media print {
            @page {
                size: A4;
                margin: 0mm;
            }

        }

        .total {
            background-color: var(--mainColor) !important;
            color: white !important;
            text-align: center;
            padding: 10px 5px !important;
        }
    </style>
@endsection



@section('content')


        <div class="tableSpace p-0">



            <table id="area" class="not" style="direction: rtl">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>التاريخ</th>
                        <th>التاجر</th>
                        <th>المنتج</th>
                        <th>الخصائص</th>
                        <th>السعر</th>
                        <th>الكمية</th>
                        <th>الاجمالي</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $index = 1;
                        $qnt = 0;
                        $price = 0;
                    @endphp

                    @foreach ($invoice->items as $item)
                        <tr>
                            <td>{{ $index }}</td>
                            <td>{{ str_replace('00:00:00', '', $item->date) }}</td>
                            <td>{{ $invoice->trader->name }} <br> {{ $invoice->trader->mobile }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->variants }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->qnt }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                        @php
                            $index++;
                            $qnt += $item->qnt;
                            $price += $item->total;
                        @endphp
                    @endforeach

                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="2" class="total">الاجمالي</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="total">{{ $qnt }}</td>
                        <td class="total">{{ $price }}</td>
                    </tr>
                </tfoot>


            </table>


        </div>

        <button class="mainBtn mt-3" id="print">طباعة</button>

        @if (auth()->user()->role == 'admin')
            <a target="_blank" href="https://api.whatsapp.com/send?phone=2{{ $invoice->trader->mobile }}"> <button
                    class="mainBtn mt-3" style="background: rgb(16 185 129 /1);border-color: rgb(16 185 129 /1)">فتح واتساب
                    التاجر</button> </a>
        @endif


@endsection


@section('js')
    <script>
        $('aside .invoices').addClass('active');


        tippy('[data-tippy-content]');


        flatpickr('input.date', {
            enableTime: false,
            dateFormat: "Y-m-d"
        });

        $('.js-example-basic-single').select2();


        $('.js-example-basic-single.main').on('select2:select', function(e) {


            let product_id = e.params.data.id;

            $.ajax({
                url: '/admin/products/ajaxVariant',
                type: 'GET',
                dataType: 'json',
                data: {
                    query: product_id
                },

                beforeSend() {
                    $("#addRowBtn").hide()
                },
                success: function(response) {
                    if (response.status == "success") {



                        let cartona = ``;


                        for (const variant of response.variant) {

                            var name = ''

                            for (const value of variant["values"]) {
                                name += value.value
                            }


                            cartona += `<option value="${variant.id}">${name}</option>`

                        }


                        $('#variant').html(cartona);



                    } else {

                        $('#variant').html('');


                    }

                    $("#addRowBtn").show()

                }
            });


        });
    </script>

    <script>
        $('#print').click(function() {

            $('#area').printThis({

                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('assets/admin/css/adminstyle.css') }}",
                pageTitle: "{{ $invoice->InvoiceName }}",
            });
        });
    </script>
@endsection
