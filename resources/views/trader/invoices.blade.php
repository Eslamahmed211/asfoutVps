@extends('admin/layout')


@section('css')
    <style>
        .select2-container {

            width: 100% !important;


        }

        .select2-container .select2-selection--single,
        select {

            height: 40px;

        }

        .select2-container--default .select2-selection--single {
            border-color: #e5e7eb;
            border-radius: 0.375rem;
            font-size: 14px;

            border-width: 0px;

            outline: none;

        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {

            top: 8px;

        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 40px;
            height: 100%;
            --tw-bg-opacity: 1;
            background-color: rgb(243 244 246 / var(--tw-bg-opacity));
            --tw-shadow: 0 1px 3px 0 rgb(0 0 0 / .1), 0 1px 2px -1px rgb(0 0 0 / .1);
            --tw-shadow-colored: 0 1px 3px 0 var(--tw-shadow-color), 0 1px 2px -1px var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);


        }
    </style>
@endsection


@section('content')

    <x-searchForm action="{{ url('trader/invoices/search') }}">
        @php
            !empty($_GET['InvoiceName']) ? ($InvoiceName = $_GET['InvoiceName']) : ($InvoiceName = '');
            !empty($_GET['date']) ? ($date = $_GET['date']) : ($date = '');
            !empty($_GET['traderId']) ? ($traderId = $_GET['traderId']) : ($traderId = '');
            !empty($_GET['type']) ? ($type = $_GET['type']) : ($type = '');
        @endphp

        <x-admin.forms.input name="InvoiceName" value="{{ $InvoiceName }}" lable_title="اسم الفاتورة">
        </x-admin.forms.input>


        <div class="mb-3">
            <label>اختار التاريخ</label>

            <input name="date" class="date" type="text" value="{{ $date }}">

        </div>



        <div class="mb-3" data-title="نوع الفاتورة">
            <label>نوع الفواتير</label>
            <select name="type">
                <option value="">كل الفواتير</option>
                <option @if ($type == 'مشتريات') selected @endif value="مشتريات">مشتريات</option>
                <option @if ($type == 'مرتجعات') selected @endif value="مرتجعات">مرتجعات</option>
            </select>

        </div>


    </x-searchForm>

    <div class="actions">


        <div class="contnet-title"> الفواتير </div>
        <x-searchBtn></x-searchBtn>
    </div>


    <div class="tableSpace">



        @if (Request::is('trader/invoices/search'))
            <div class="frameHead my-3">

                <div class="group">
                    <div> اجمالي الفواتير :</div>
                    <div class="value" id="reference">{{ abs($total) }}</div>
                </div>

                <div class="group">
                    <div> اجمالي القطع :</div>
                    <div class="value" id="reference">{{ abs($qnt) }}</div>
                </div>



                <div class="group">
                    <div> اجمالي المبيعات :</div>
                    <div class="value" id="reference">{{ abs($price) }}</div>
                </div>



            </div>
        @endif



        <table>
            <thead>

                <tr>
                    <th>#</th>
                    <th> اسم الفاتورة </th>
                    <th>نوع الفاتورة</th>
                    <th>عدد القطع</th>
                    <th>اجمالي الفاتورة</th>
                    <th> التاريخ </th>

                </tr>

            </thead>
            <tbody>


                @forelse ($invoices as $invoice)
                    <tr>


                        <td> {{ ($invoices->currentpage() - 1) * $invoices->perpage() + $loop->index + 1 }} </td>


                        <td> <a target="_blank" href="/trader/invoices/{{ $invoice->id }}/show">{{ $invoice->InvoiceName }}
                            </a> </td>

                        <td>{{ $invoice->type }}</td>

                        @php
                            $qnt = 0;
                            $price = 0;
                        @endphp

                        @foreach ($invoice->items as $item)
                            @php
                                $qnt += $item->qnt;
                                $price += $item->total;
                            @endphp
                        @endforeach

                        <td>{{ $qnt }}</td>
                        <td>{{ $price }}</td>


                        <td>{{ $invoice->created_at }}</td>



                    </tr>


                @empty
                    <tr>

                        <td colspan="6">لا يوجد فواتير متاحة</td>
                    </tr>
                @endforelse

            </tbody>


        </table>





    </div>

    <div class="pagnate" class="السابق والتالي">
        {{ $invoices->appends(request()->query())->links() }}
    </div>
@endsection




@section('js')
    <script>
        $('#aside .invoices ').addClass('active');
        tippy('[data-tippy-content]');

        flatpickr('input.date', {
            enableTime: false,
            mode: "range",

            dateFormat: "Y-m-d"
        });

        $('.js-example-basic-single').select2();
    </script>
@endsection
