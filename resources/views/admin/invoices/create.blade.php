@extends('admin.layout')

@section('css')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single,
        select:not(.not) {

            height: 35px;

        }

        .swal2-popup {
            width: 26em !important;
        }

        .swal2-title {
            font-size: 1.5em !important;
        }

        .swal2-styled {
            padding: 0.5em 1em !important;


        }
    </style>
@endsection


@section('path')
    <ul class="paths">
        <li> <a href="{{ url('admin/invoices') }}"> الفواتير / </a> </li>
        <li class="active"> {{ $user->name }} /</li>
        <li class="active">اضافة </li>
    </ul>
@endsection

@section('content')
    <div class=" mt-lg-4  ">

        <div class="row py-3 rounded bg-white mx-2">

            <input type="hidden" value="{{ $user->id }}" id="trader_id">


            <div data-title="اسم الفاتورة" class="col-lg-4 col-12">
                <x-admin.forms.input for="name" id="name" lable_title="اسم الفاتورة" name="name"
                    placeholder="اسم الفاتورة">
                </x-admin.forms.input>
            </div>

            <div data-title="اسم الفاتورة" class="col-lg-4 col-12">
                <x-admin.forms.input for="date" value="{{ now()->format('Y-m-d') }}" id="date"
                    lable_title="تاريخ الفاتورة" class="date" name="date" placeholder="اختار التاريخ">
                </x-admin.forms.input>
            </div>


            <div class="col-lg-4 col-12 " title="نوع الفاتورة">

                <label for="type" class="mb-2">نوع الفاتورة</label>

                <select class="not" id="type">
                    <option value="مشتريات">مشتريات</option>
                    <option value="مرتجعات">مرتجعات</option>
                </select>

            </div>

            <div class="col-lg-6 col-12 mb-3 " title="المنتج">
                <label for="product" class="mb-2">المنتج</label>
                <select class="js-example-basic-single main" id="product">

                    <option disabled selected>اختار المنتج من هنا</option>

                    @forelse ($products as $product)
                        <option name="{{ $product->price }}" value="{{ $product->id }}">{{ $product->name }}</option>
                    @empty
                        <option disabled selected>لا يوجد منتجات لهذا التاجر</option>
                    @endforelse

                </select>
            </div>

            <div class="col-lg-6 col-12 " title="الفرعية">

                <label for="product" class="mb-2">خصائص المنتج</label>

                <select class="js-example-basic-single" id="variant">
                </select>

            </div>


            <div class="col-12">
                <x-admin.forms.mainBtn onclick="addRow()" id="addRowBtn" type="button" title="اضافة حقل"
                    class="mt-3"></x-admin.forms.mainBtn>
            </div>


        </div>


        <div class="mt-4  mx-1 tableSpace p-0">



            <table class="not">

                <thead>
                    <th>#</th>
                    <th>التاريخ</th>
                    <th>التاجر</th>
                    <th>المنتج</th>
                    <th>الخصائص</th>
                    <th>السعر</th>
                    <th>الكمية</th>
                    <th>الاجمالي</th>
                    <th>ازالة</th>
                </thead>

                <tbody>


                </tbody>


            </table>






        </div>

        <div class="col-12">
            <x-admin.forms.mainBtn id="storeBtn" onclick="saveInvoice()" icon="plus" title="اضافة الفاتورة"
                class="mt-3">
            </x-admin.forms.mainBtn>
        </div>
    </div>
@endsection


@section('js')
    {{-- main --}}

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

    {{-- add row --}}

    <script>
        function getTotal(e) {


            let tr = $(e).parent();
            let stock = $(e).html();
            stock = parseFloat(stock);


            if (isNaN(stock)) {
                $(tr).children(".total").html(0);
                return
            }


            let price = $(tr).children(".price").html();
            let total = price * stock;
            $(tr).children(".total").html(total);



        }

        function addRow() {

            let AllData = getInputsData();

            if (AllData == undefined) {
                return
            }

            if (checkRepeat(AllData)) {
                Swal.fire({
                    title: 'خطا!',
                    text: "هذا الحقل موجود من قبل",
                    icon: 'error',
                    confirmButtonText: 'فهمت'
                })

                return
            }


            let cartona = ``;

            cartona += `<tr>`
            cartona += `<td> 0 </td>`
            cartona += `<td class="date"> ${AllData["date"]} </td>`
            cartona += `<td> ${AllData["trader"]} </td>`
            cartona += `<td data-id='${AllData["product_id"]}'class="product_name_ "> ${AllData["product_name"]}</td>`
            cartona += `<td data-id='${AllData["variantId"]}' class="variantText">  ${AllData["variantText"]}  </td>`
            cartona += `<td class="price">${AllData["price"]}</td>`
            cartona += `<td oninput="getTotal(this)" class="tableInput" contenteditable="true"> </td>`
            cartona += `<td class="total">0</td>`
            cartona += `<td><button onclick="removeRow(this)" class="mainBtn py-1 px-3"
                        style="font-size: 12px !important">ازالة </button></td> </tr>`

            $(".not tbody").append(cartona);
            updateRowNumbers();

        }

        function getInputsData() {

            let select = $('.js-example-basic-single.main').select2('data');

            if (select[0].disabled) {
                return;
            }

            let price = $(select[0].element).attr('name');
            let selectedVariant = $('#variant').select2('data');
            let name = $("#name").val();
            let date = $("#date").val();
            let product = $("#product").val();
            let variant = $("#variant").val();
            let product_name = select[0].text
            let product_id = select[0].id


            return {
                "name": name,
                "date": date,
                "trader": "{{ $user->name }} <br> {{ $user->mobile }}",
                "product_name": product_name,
                "product_id": product_id,
                "price": price,
                "variantId": selectedVariant[0]?.id || "",
                "variantText": selectedVariant[0]?.text || "",
            }

        }

        function checkRepeat(AllData) {

            if (AllData['variantId'] != "") {

                let rows = document.querySelectorAll('.not tbody tr');

                for (var i = 0; i < rows.length; i++) {

                    if ($(rows[i]).children("td.variantText").text().trim() == AllData['variantText'].trim()) {
                        return true
                    }

                }
                return false
            }
        }

        function removeRow(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            updateRowNumbers();

        }

        function updateRowNumbers() {
            var rows = document.querySelectorAll('.not tbody tr');
            for (var i = 0; i < rows.length; i++) {
                rows[i].querySelector('td:first-child').textContent = (i + 1).toString();
            }
        }
    </script>

    {{-- ajax --}}

    <script>
        function saveInvoice() {

            Swal.fire({
                title: "هل انت متاكد من اضافة الفاتورة",
                showDenyButton: true,
                confirmButtonText: 'اضافة',
                denyButtonText: "لا اغلاق",
            }).then((result) => {
                if (result.isConfirmed) {
                    store()
                }
            })




        }


        function getAjaxData() {

            let InvoiceName = $("#name").val().trim();


            let type = $("#type").val().trim()


            let traderId = $("#trader_id").val().trim();

            let rows = document.querySelectorAll('.not tbody tr');

            let AllRows = [];

            for (var i = 0; i < rows.length; i++) {

                let rowData = {
                    date: $(rows[i]).children("td.date").text().trim(),
                    productId: $(rows[i]).children("td.product_name_").attr("data-id").trim(),
                    variantId: $(rows[i]).children("td.variantText").attr("data-id").trim(),
                    price: $(rows[i]).children("td.price").text().trim(),
                    qnt: $(rows[i]).children("td.tableInput").text().trim(),
                    total: $(rows[i]).children("td.total").text().trim()
                };

                AllRows.push(rowData)

            }

            return {
                "InvoiceName": InvoiceName,
                "traderId": traderId,
                "type": type,
                "rows": AllRows
            }

        }

        function CheckAjaxData(AjaxData) {

            let index = 1;

            for (const row of AjaxData.rows) {


                if (row.qnt == "" || isNaN(row.qnt)) {
                    Swal.fire({
                        title: 'خطا!',
                        text: "يرجي كتابة الكمية في الفاتورة رقم " + index,
                        icon: 'error',
                        confirmButtonText: 'فهمت'
                    })

                    return true;
                }
                index++;
            };

        }

        function store() {
            let AjaxData = getAjaxData();

            CheckAjaxData(AjaxData)

            if (CheckAjaxData(AjaxData)) {
                return
            };

            if (AjaxData.InvoiceName == "") {
                Swal.fire({
                    title: 'خطا!',
                    text: "يرجي كتابة اسم الفاتورة",
                    icon: 'error',
                    confirmButtonText: 'فهمت'
                })
                return
            }


            $.ajax({
                url: '/admin/invoices',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    data: AjaxData
                },

                beforeSend() {
                    $("#addRowBtn").hide();
                    $("#storeBtn").hide();

                },
                success: function(response) {
                    if (response.status == "success") {



                        window.location.href = `/admin/invoices/${response.invoice.id}/show`;


                    } else if (response.status == "ValidationError") {

                        Swal.fire({
                            title: 'خطا!',
                            text: Object.values(response.Errors)[0][0],
                            icon: 'error',
                            confirmButtonText: 'فهمت'
                        })
                    } else if (response.status == "CoustomErrors") {



                        Swal.fire({
                            title: 'خطا!',
                            text: response.CoustomErrors,
                            icon: 'error',
                            confirmButtonText: 'فهمت'
                        })
                    }

                    $("#addRowBtn").show()
                    $("#storeBtn").show();


                },
                error: function() {
                    $("#addRowBtn").show()
                    $("#storeBtn").show();
                }
            });
        }
    </script>
@endsection
