@extends('admin.layout')

@section('css')
    <style>
        .bordered-alert {
            border-right-width: 4px;
            font-size: .875rem;
            padding: 10px 20px;
            border-radius: 0.2rem;
            font-weight: 600;
            font-size: 18px;
            --tw-border-opacity: 1;
            border-color: rgb(245 158 11 / var(--tw-border-opacity));
            --tw-bg-opacity: 1;
            background-color: rgb(254 243 199 / var(--tw-bg-opacity));
            --tw-text-opacity: 1;
            color: rgb(180 83 9 / var(--tw-text-opacity));
        }

        .notes {
            margin-top: 5px;
            color: red;
            font-weight: 700;
            font-size: 14px
        }

        .firstRow {
            display: flex;
            align-items: center;
            padding: 0px;
            margin-top: 15px;
            flex-wrap: wrap;

        }

        .firstRow div {
            margin-left: 10px;
        }

        .id {
            font-size: 15px;
            font-weight: 600;
            color: var(--mainColor)
        }

        .time {
            color: #7a7b97;
            font-size: 14px;
            font-weight: 700;
        }

        .prodcut_img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            border-radius: 5px;

        }
    </style>

    <style>
        .info {
            background-color: #f5f5f7 !important;
            padding: 20px;
            border-radius: 2px;
            margin-bottom: 15px;
        }

        .order_title {
            color: rgb(37, 42, 49);
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 5px;
            border-bottom: 1px solid rgba(128, 128, 128, 0.26);
            padding-bottom: 10px;
        }

        .client_data p,
        .client_data a,
        .client_data .note {
            margin: 0px;
            color: rgb(95, 115, 140);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            margin-right: 10px;
            margin-bottom: 5px;
            cursor: pointer;
        }

        .bwipyB {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            vertical-align: middle;
            fill: currentcolor;
        }


        .total {
            margin: 5px 0px;
            font-size: 15px;
            font-weight: 600;
        }

        .total span {
            margin: 5px 0px;
            font-weight: 700;
            font-size: 14px;
            color: rgba(0, 0, 0, 0.8);
            color: var(--mainColor)
        }

        .radio {
            width: fit-content !important;
            padding: 0.6rem !important;
            margin-right: 10px !important;
            margin-top: 3px !important;

        }

        .form-check {
            width: fit-content !important;


        }
    </style>
@endsection


@section('path')
    <ul class="paths">
        <li> <a href="{{ url('admin/home') }}"> الرئيسية / </a> </li>
        <li> <a href="{{ url('admin/orders') }}"> الطلبات / </a> </li>
        <li> <a href="{{ url("admin/orders/$order->id/show") }}"> {{ $order->clientName }} </a> </li>
    </ul>
@endsection



@section('content')
    <div class=" mt-lg-4 mx-3  mb-4  ">
        @include('admin.orders.component.edit')
    </div>

    <div class=" mt-lg-4 mx-3  mb-4  ">
        @include('admin.orders.component.details')
    </div>



    <x-admin.forms.deleteModel model="orders/details" id="detail_id"></x-admin.forms.deleteModel>
    <x-admin.forms.buttonLink path="/admin/orders" title="رجوع" icon="back"></x-admin.forms.buttonLink>
@endsection


@section('js')
    <script>
        $('aside .orders').addClass('active');
        tippy('[data-tippy-content]');

        $('.js-example-basic-single').select2();

        function show_delete_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $('#model_title').text(data_name);

            $("input[name='detail_id']").val(data_id)

        }

        function show_edit_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $("#message").val(data_name)
            $("input[name='note_id']").val(data_id)
        }
    </script>

    <script>
        function deleteComment(id) {
            Swal.fire({
                title: 'هل انت متاكد من ازالة الملاحظة ؟',
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: 'نعم',
                cancelButtonText: 'لا الغاء',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/admin/orders/notes/${id}/delete`;
                }
            })
        }
    </script>

    <script>
        function changeComissation() {



            let comissation = parseInt($("#comissation").val());
            let total = parseInt($("#total").val());
            let get = parseInt($("#get").val());
            let take = parseInt($("#take").val());

            // if (get > $("input[name=comissation]").val()) {
            //     console.log($("input[name=comissation]").val());
            //     $("#get").val("")
            //     $("#take").val("")
            //     $("input[name=total]").val(total)
            //     $("input[name=comissation]").val(comissation)

            //     return;

            // }


            if (isNaN(comissation)) {
                comissation = 0;
            }

            if (isNaN(get)) {
                get = 0;
            }

            if (isNaN(take)) {
                take = 0;
            }

            $("input[name=comissation]").val(comissation + take - get);
            $("input[name=total]").val(total + take - get);
        }
    </script>
@endsection
