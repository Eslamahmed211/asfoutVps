@extends('admin.layout')

@section('css')
    <style>
        .productImg {
            width: 100%;

        }

        .productImg img {
            border-radius: 10px;
            width: 100%;

        }

        .productContent {
            width: 100%;
        }

        h1 {
            font-size: 2rem;
            font-weight: 500;
            color: #37474f;
            margin-bottom: 15px;

        }


        h2 {
            font-size: .9rem;
            color: #37474f;
            font-weight: 600 !important;
            margin-bottom: 10px
        }

        h2 span {
            color: var(--mainColor) !important;

        }




        hr {
            border-color: #d9d9d9;

        }

        @media(max-width:993px) {
            .firstRow {
                flex-direction: column;
                align-items: flex-start
            }

            h1 {
                margin-top: 10px;
                font-size: 1.5rem
            }

            .firstRow .price {
                text-align: left;
                width: 100%;
                font-size: 1.2rem
            }

        }
    </style>

    <style>
        .attribute {
            font-size: .9rem;
            color: #37474f;
            font-weight: 700 !important;
            margin-bottom: 15px;
        }


        .values {
            display: flex;
            margin-top: 10px;
            flex-wrap: wrap
        }

        .option {
            background: #eceff1;
            margin-left: 0.75rem;
            height: 32pt;
            min-width: 32pt;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 8pt;
            margin-bottom: 8pt;
            transition-duration: 0.2s;
            border: solid 1px #eee margin-bottom: 8pt;
            border-radius: 2px;
            font-weight: 700;
            font-size: 13px;
            color: #37474f;
            cursor: pointer
        }


        .option:hover {
            background: #cfd8dc;
        }

        .option.selected {
            background: var(--xbutton-background);
            border-color: var(--xbutton-background);
            color: white;
        }
    </style>

    <style>
        .label {
            display: flex;
            min-width: 64px;
            font-size: 14px;
            width: fit-content;

        }

        .adjust-quantity-btn {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12pt;
            height: 32pt;
            margin: 0;
            width: 32pt;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            border-radius: 4pt;
            transition-duration: 0.2s;
            background: #eceff1;
            transition-property: background;
            outline: none !important;
        }

        .label {

            justify-content: center;
            font-size: 14px;
            align-items: center;
        }

        .adjust-quantity-btn:hover {
            background: var(--xbutton-background);
            border-color: var(--xbutton-background);
            color: white;
        }

        #Comissation,
        #quantity {
            background: transparent;
            border: none;
            box-shadow: none;
            text-align: center;
            width: 65px;

        }
    </style>

    <style>
        #loader {
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            z-index: 9;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            display: none;
        }

        #loader {
            font-size: 40px;
            color: var(--mainColor);
        }

        .more {
            display: flex;
            margin-top: 10px;
            flex-wrap: nowrap;
            justify-content: center;
            overflow-x: scroll;
            padding-bottom: 15px
        }

        .more img {
            margin: 0px 5px;
            height: 75px;
            width: 75px;
            border-radius: 5px;
            cursor: pointer;
            padding: 2px;
            object-fit: cover;

        }

        .more img.active {
            border: 2px solid var(--mainColor);
        }

        #productImg {
            height: 70vh;
            object-fit: scale-down;
        }


        .threeRow {
            display: flex;
            align-items: center;
            flex-direction: row-reverse;
            justify-content: space-between;
        }



        @media(max-width:993px) {
            #productImg {
                height: 35vh;
                object-fit: contain;
            }

            .threeRow {
                display: flex;
                align-items: flex-start;
                flex-direction: column;
                justify-content: space-between;
            }
        }
    </style>
@endsection


@section('path')
    <ul class="paths">
        <li> <a href="{{ url('admin/orders') }}"> الطلبات / </a> </li>
        @php
            $id = $details->order->id;
        @endphp
        <li> <a href="{{ url("admin/orders/$id/show") }}"> {{ $details->order->clientName }} / </a> </li>
        <li class="active"> {{ $details->discription }}</li>
    </ul>
@endsection



@section('content')
    <div class="  mt-lg-4 mx-3  mb-4  ">

        <div class="row bg-white  py-l-4 py-3 px-2 mx-2 rounded ">

            <div data-title="صورة المنتج" class="col-lg-5 col-12 ">
                <div class="productImg">
                    <img id="productImg" src="{{ path($details->product->firstImg->img) }}"
                        alt="{{ $details->product->name }}">
                </div>

                @if (count($details->product->imgs) > 1)
                    <div class="more">
                        @foreach ($details->product->imgs as $img)
                            <img src="{{ path($img->img) }}" alt="{{ $details->product->name }}" onclick="selectImg(this)">
                        @endforeach

                    </div>
                @endif


            </div>

            <div data-title="جزء البيانات" class="col-lg-7 col-12 px-lg-4 ">

                <div class="productContent position-relative  ">


                    <div id="loader">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                    </div>

                    <div data-title="بيانات المنتج" class="firstRow">
                        <h1>{{ $details->product->name }}</h1>
                        <h2> السعر : <span>{{ $details->product->price + $details->product->systemComissation }} ج.م</span>
                        </h2>

                        @if (!$details->product->comissation)
                            @if ($details->product->min_comissation)
                                <h2> الحد الادني للعمولة : <span>{{ $details->product->min_comissation }} ج.م</span></h2>
                            @endif
                            @if ($details->product->max_comissation)
                                <h2> الحد الاقصي للعمولة : <span>{{ $details->product->max_comissation }} ج.م</span></h2>
                            @endif
                        @endif

                        @if ($details->product->comissation)
                            <h2> عمولتك: <span>{{ $details->product->comissation }} ج.م</span></h2>
                        @endif


                        @if ($details->variant)
                            @if ($details->variant->stock === 0)
                                <h2> حالة المنتج: <span id="stock" class="text-danger"> غير متوفر </span></h2>
                            @else
                                <h2> حالة المنتج: <span id="stock"> متوفر {{ $details->variant->stock }} </span></h2>
                            @endif
                        @else
                            @if ($details->product->stock === 0)
                                <h2> حالة المنتج: <span id="stock" class="text-danger"> غير متوفر </span></h2>
                            @else
                                <h2> حالة المنتج: <span id="stock"> متوفر {{ $details->product->stock }} </span></h2>
                            @endif
                        @endif




                        <hr>

                    </div>


                    <div data-title="خصائص المنتج" class="secondRow">
                        @foreach ($details->product->attributes as $attribute)
                            <div class="attribute">{{ $attribute->name }}</div>

                            <div class="values">
                                @foreach ($attribute->values as $value)
                                    <div @if ($details->product->stock !== 0) onclick="select(this)" @endif
                                        id="{{ $value->id }}"
                                        class="option @if (in_array($value->id, $valueIds)) selected @endif ">
                                        {{ $value->value }}
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                    </div>

                    @if ($details->product->stock !== 0 || $details->product->unavailable == 'yes')
                        <div data-title="العمولة والاستوك" class="threeRow ">



                            @if (!$details->product->comissation)
                                <div title="العمولة" class="quantity-selector  mt-2 mb-2 ">
                                    <div class="attribute">العمولة</div>

                                    <span class="label align-center">

                                        <button @disabled($details->product->comissation) class="adjust-quantity-btn"
                                            onclick="minasComissation()">-</button>

                                        <input id="Comissation" type="number" class="label" min="0 "
                                            value="{{ $details->comissation }}">


                                        <button @disabled($details->product->comissation) class="adjust-quantity-btn"
                                            onclick="plusComissation()">+</button>

                                    </span>
                                </div>
                            @endif

                            <div title="الكمية" class="quantity-selector  mt-2 mb-2 ">
                                <div class="attribute">الكمية</div>

                                <span class="label align-center">

                                    <button class="adjust-quantity-btn" onclick="minas()">-</button>

                                    <input id="quantity" type="number" min="1" class="label"
                                        value="{{ $details->qnt }}">
                                    <button class="adjust-quantity-btn" onclick="plus()">+</button>

                                </span>
                            </div>


                        </div>
                    @endif

                    <div data-title="استبدال" class="mt-2">


                        @if ($details->product->stock !== 0 || $details->product->unavailable == 'yes')
                            <x-admin.forms.mainBtn id="replace" title="استبدال"></x-admin.forms.mainBtn>
                        @endif


                    </div>


                </div>

            </div>
        </div>

    </div>
@endsection



@section('js')
    <script>
        $('aside .orders').addClass('active');

        function select(e) {
            $(e).addClass("selected").siblings().removeClass("selected");
            getVariant()

        }

        function getVariant() {
            let ids = getIds();

            $.ajax({
                url: `/admin/getVariant/{{ $details->product->id }}`,
                type: "get",
                data: {
                    data: ids
                },
                dataType: "json",

                beforeSend() {
                    $("#replace").hide();
                    $("#loader").addClass("d-flex");
                    $("#loader").removeClass("d-none");

                },
                success: function(response) {
                    if (response.status == "success") {

                        $("#loader").addClass("d-none");
                        $("#loader").removeClass("d-flex");

                        if (response.variant != null) {

                            if (response.variant.stock == 0 && response.variant.product.unavailable == "no") {
                                $("#stock").text("غير متوفر")
                            } else if (response.variant.stock == null) {
                                $("#stock").text("غير محدود")
                                $("#replace").show();

                            } else if (response.variant.stock == 0 && response.variant.product.unavailable ==
                                "yes") {
                                $("#stock").text("غير متوفر")
                                $("#replace").show();


                            } else {
                                $("#stock").text(" متوفر  " + response.variant.stock + " قطع ")
                                $("#replace").show();



                            }


                        }


                    }
                },
                error: function() {
                    $("#loader").addClass("d-none");
                    $("#loader").removeClass("d-flex");

                }
            });

        }

        function getIds() {
            let ids = [];

            $(".option.selected").each(function() {
                ids.push($(this).attr("id"));
            });

            return ids;
        }

        let btn = document.getElementById("replace")
        btn.addEventListener("click", function() {



            let cart = {
                "product_id": "{{ $details->product->id }}",
                "productType": "{{ $details->variant_id ? 'variant' : 'product' }}",

                "stock": $("#quantity").val(),
                "comissation": $("#Comissation").val() ?? "{{ $details->comissation }}",
                "values": getIds()
            };


            $.ajax({
                url: '/admin/orders/details/{{ $details->id }}',
                type: 'put',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    data: cart
                },

                beforeSend() {
                    $("#loader").addClass("d-flex");
                    $("#loader").removeClass("d-none");
                },
                success: function(response) {

                    $("#loader").addClass("d-none");
                    $("#loader").removeClass("d-flex");


                    if (response.status == "success") {



                        Swal.fire({
                            title: "تم",
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'فهمت'
                        }).then(function() {
                            window.location.reload();
                        });



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
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'فهمت'
                        })
                    }



                },
                error: function() {
                    $("#loader").addClass("d-none");
                    $("#loader").removeClass("d-flex");


                    Swal.fire({
                        title: 'خطا!',
                        text: "خطأ",
                        icon: 'error',
                        confirmButtonText: 'فهمت'
                    })
                }
            });

        });
    </script>

    {{-- تزويد ونقص العمولة والاستوك --}}

    <script>
        function plus() {
            var quantity = parseInt($("#quantity").val());
            $("#quantity").val(quantity + 1);


        }

        function minas() {
            var quantity = parseInt($("#quantity").val());
            $("#quantity").val(quantity - 1);

            if (parseInt($("#quantity").val()) <= 0) {
                $("#quantity").val("1");
            }
        }


        function plusComissation() {

            var Comissation = parseInt($("#Comissation").val());
            $("#Comissation").val(Comissation + 5);



        }

        function minasComissation() {
            var Comissation = parseInt($("#Comissation").val());
            $("#Comissation").val(Comissation - 5);



            if (parseInt($("#Comissation").val()) < 0) {
                $("#Comissation").val("0");
            }

        }
    </script>

    {{-- الصور --}}

    <script>
        function selectImg(e) {
            $(e).addClass("active").siblings().removeClass("active");
            let path = e.src;
            $("#productImg").attr("src", path)
        }
    </script>
@endsection
