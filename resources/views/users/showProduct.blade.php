@extends('admin.layout')




@if (auth()->user()->role == 'user' || auth()->user()->role == 'moderator')
    @php
        $userType = 'users';

    @endphp
@elseif(auth()->user()->role == 'admin' || auth()->user()->role == 'super')
    @php
        $userType = 'admin';

    @endphp
@endif


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
        <li> <a href="{{ url('users/home') }}"> الرئيسية / </a> </li>
        <li> <a href="{{ url('users/products') }}"> المنتجات / </a> </li>
        <li class="active">{{ $product->name }} </li>
    </ul>
@endsection


@section('content')
    <div class="row bg-white  py-l-4 py-3 px-2 mx-2 rounded ">


        <input type="hidden" id="add_new_product" value="{{ $_GET['add_new_product'] ?? '' }}">



        <div data-title="صورة المنتج" class="col-lg-5 col-12 ">
            <div class="productImg">
                <img id="productImg" src="{{ path($product->firstImg->img) }}" alt="{{ $product->name }}">
            </div>

            @if (count($product->imgs) > 1)
                <div class="more">
                    @foreach ($product->imgs as $img)
                        <img src="{{ path($img->img) }}" alt="{{ $product->name }}" onclick="selectImg(this)">
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
                    <h1>{{ $product->name }}</h1>
                    <h2> السعر : <span>{{ $product->price + $product->systemComissation }} ج.م</span></h2>

                    @if (!$product->comissation)
                        @if ($product->min_comissation)
                            <h2> الحد الادني للعمولة : <span>{{ $product->min_comissation }} ج.م</span></h2>
                        @endif
                        @if ($product->max_comissation)
                            <h2> الحد الاقصي للعمولة : <span>{{ $product->max_comissation }} ج.م</span></h2>
                        @endif
                    @endif

                    @if ($product->comissation)
                        <h2> عمولتك: <span>{{ $product->comissation }} ج.م</span></h2>
                    @endif


                    @if ($product->ponus)
                        <h2> البونص: <span>{{ $product->ponus }} ج.م</span></h2>
                    @endif

                    @if ($product->stock === 0)
                        <h2> حالة المنتج: <span id="stock" class="text-danger"> غير متوفر </span></h2>
                    @else
                        <h2> حالة المنتج: <span id="stock"> متوفر {{ $product->stock }} </span></h2>
                    @endif


                    <hr>

                </div>


                <div data-title="خصائص المنتج" class="secondRow">
                    @foreach ($product->attributes as $attribute)
                        <div class="attribute">{{ $attribute->name }}</div>

                        <div class="values">
                            @foreach ($attribute->values as $value)
                                <div @if ($product->stock !== 0) onclick="select(this)" @endif
                                    id="{{ $value->id }}" class="option">{{ $value->value }}
                                </div>
                            @endforeach
                        </div>
                    @endforeach


                </div>

                @if ($product->stock !== 0 || $product->unavailable == 'yes')
                    <div data-title="العمولة والاستوك" class="threeRow ">



                        @if (!$product->comissation)
                            <div title="العمولة" class="quantity-selector  mt-2 mb-2 ">
                                <div class="attribute">العمولة</div>

                                <span class="label align-center">

                                    <button @disabled($product->comissation) class="adjust-quantity-btn"
                                        onclick="minasComissation()">-</button>

                                    <input onchange="checkComissation()" id="Comissation" type="number" class="label"
                                        @disabled($product->comissation) @readonly($product->comissation)
                                        min="{{ $product->comissation ? $product->comissation : $product->min_comissation ?? 0 }}"
                                        max="{{ $product->comissation ? $product->comissation : $product->max_comissation }}"
                                        value="{{ $product->comissation ? $product->comissation : $product->min_comissation ?? 0 }}">


                                    <button @disabled($product->comissation) class="adjust-quantity-btn"
                                        onclick="plusComissation()">+</button>

                                </span>
                            </div>
                        @endif

                        <div title="الكمية" class="quantity-selector  mt-2 mb-2 ">
                            <div class="attribute">الكمية</div>

                            <span class="label align-center">

                                <button class="adjust-quantity-btn" onclick="minas()">-</button>

                                <input id="quantity" type="number" min="1" class="label" value="1">


                                <button class="adjust-quantity-btn" onclick="plus()">+</button>

                            </span>
                        </div>


                    </div>
                @endif

                <div data-title="اضف الي السلة" class="mt-2">

                    @if (count($product->variants) > 0)
                        <x-admin.forms.mainBtn id="addToCartBtn" style="display: none"
                            title="اضف الي السلة"></x-admin.forms.mainBtn>
                    @else
                        @if ($product->stock !== 0 || $product->unavailable == 'yes')
                            <x-admin.forms.mainBtn id="addToCartBtn" title="اضف الي السلة"></x-admin.forms.mainBtn>
                        @endif
                    @endif


                </div>


            </div>

        </div>
    </div>

    <div data-title="التابات" class=" my-4 mx-2  ">

        <div class="w-100">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-dis-tab" data-bs-toggle="tab" data-bs-target="#nav-dis"
                        type="button" role="tab" aria-controls="nav-dis" aria-selected="true">وصف المنتج</button>

                    @if (count($product->variants) > 0 && $product->stock !== 0)
                        <button class="nav-link " id="nav-stock-tab" data-bs-toggle="tab" data-bs-target="#nav-stock"
                            type="button" role="tab" aria-controls="nav-stock" aria-selected="true">توزيع
                            الاستوك</button>
                    @endif

                    <button class="nav-link " onclick="downloadAllImages()">
                        تحميل الصور علي جهازك <i class="fa-solid fa-download mx-2"></i></button>

                    @if ($product->drive)
                        <a target="_blank" href="{{ $product->drive }}"> <button class="nav-link ">
                                لينك الدرايف <i class="fa-brands fa-google-drive  mx-2"></i></button>
                        </a>
                    @endif



                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">

                <div class="tab-pane fade show active" id="nav-dis" role="tabpanel" aria-labelledby="nav-dis-tab"
                    tabindex="0">
                    <div class="py-3">
                        <div class="bg-white px-3 py-3 mt-1 rounded ">

                            <div class="dis">
                                <?= $product->dis ?>
                            </div>
                        </div>
                    </div>
                </div>

                @if (count($product->variants) > 0 && $product->stock !== 0)
                    <div class="tab-pane fade" id="nav-stock" role="tabpanel" aria-labelledby="nav-stock-tab"
                        tabindex="0">
                        <div class="py-3">
                            <div class=" px-3 py-3 mt-1 rounded ">

                                <div class="tableSpace  p-0 mt-3">
                                    <table class="w-100 not">

                                        <thead>
                                            <tr>
                                                <th>الخصائص</th>
                                                <th>كمية المنتح</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($product->variants as $variant)
                                                <tr>


                                                    <td>

                                                        @php
                                                            $name = '';
                                                        @endphp
                                                        @foreach ($variant->values as $value)
                                                            @php
                                                                $name = $name . ' ' . $value->value;
                                                            @endphp
                                                        @endforeach
                                                        {{ $name }}

                                                    </td>

                                                    <td>
                                                        @if (is_null($variant->stock))
                                                            [غير محدود]
                                                        @else
                                                            {{ $variant->stock }}
                                                        @endif

                                                    </td>


                                                </tr>
                                            @empty
                                                <td>لا يوجد منتجات فرعية</ف>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif



            </div>
        </div>
    </div>

@endsection


@section('js')
    <script>
        $('aside .products').addClass('active');

        function select(e) {
            $(e).addClass("selected").siblings().removeClass("selected");
            getVariant()

        }
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

            if ({{ $product->comissation ? $product->comissation : !$product->comissation }}) {

                if ({{ $product->max_comissation ? 'true' : 'false' }}) {
                    if (parseInt($("#Comissation").val()) >
                        {{ $product->max_comissation ? $product->max_comissation : 'false' }}) {
                        $("#Comissation").val("{{ $product->max_comissation }}");
                    }
                }

            }


        }

        function minasComissation() {
            var Comissation = parseInt($("#Comissation").val());
            $("#Comissation").val(Comissation - 5);

            if (!{{ $product->comissation ? 'true' : 'false' }}) {
                if ({{ $product->min_comissation ? 'true' : 'false' }}) {
                    if (parseInt($("#Comissation").val()) <
                        {{ $product->min_comissation ? $product->min_comissation : 'false' }}) {
                        $("#Comissation").val("{{ $product->min_comissation }}");
                    }
                }
            }

            if (parseInt($("#Comissation").val()) < 0) {
                $("#Comissation").val("0");
            }

        }


        function checkComissation() {
            let Comissation = $("#Comissation").val();
            if (Comissation > {{ $product->max_comissation ? $product->max_comissation : 'false' }}) {
                $("#Comissation").val({{ $product->max_comissation }})
            }

            if (Comissation < {{ $product->min_comissation ? $product->min_comissation : 'false' }}) {
                $("#Comissation").val({{ $product->min_comissation }})
            }

        }
    </script>

    {{-- الفرعية --}}

    <script>
        function getVariant() {
            let ids = getIds();

            $.ajax({
                url: `/{{ $userType }}/getVariant/{{ $product->id }}`,
                type: "get",
                data: {
                    data: ids
                },
                dataType: "json",

                beforeSend() {
                    $("#addToCartBtn").hide();
                    $("#loader").addClass("d-flex");
                    $("#loader").removeClass("d-none");

                },
                error: function() {
                    $("#loader").addClass("d-none");
                    $("#loader").removeClass("d-flex");
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
                                $("#addToCartBtn").show();

                            } else if (response.variant.stock == 0 && response.variant.product.unavailable ==
                                "yes") {
                                $("#stock").text("غير متوفر")
                                $("#addToCartBtn").show();


                            } else {
                                $("#stock").text(" متوفر  " + response.variant.stock + " قطع ")
                                $("#addToCartBtn").show();

                            }

                        }


                    }
                },
            });



        }
    </script>

    {{-- الصور --}}

    <script>
        function selectImg(e) {
            $(e).addClass("active").siblings().removeClass("active");
            let path = e.src;
            $("#productImg").attr("src", path)
        }

        const imagePaths = [];

        $('.content img').each(function() {
            var imagePath = $(this).attr('src');
            imagePaths.push(imagePath);
        });


        function downloadAllImages() {

            Swal.fire({
                title: "هل انت متاكد من تحميل جميع الصور علي جهازك ؟",
                showDenyButton: true,
                confirmButtonText: 'تحميل',
                denyButtonText: "لا اغلاق",
            }).then((result) => {
                if (result.isConfirmed) {
                    download()
                }
            })



        }


        function download() {
            imagePaths.forEach(function(imagePath) {
                const link = document.createElement("a");
                link.href = imagePath;
                link.download = "";
                link.style.display = "none";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        }
    </script>

    {{-- اضافة للسلة --}}

    <script>
        function getIds() {
            let ids = [];

            $(".option.selected").each(function() {
                ids.push($(this).attr("id"));
            });

            return ids;
        }

        let btn = document.getElementById("addToCartBtn")
        btn.addEventListener("click", function() {

            let cart = {
                "product_id": "{{ $product->id }}",
                "productType": "{{ count($product->variants) ? 'variant' : 'product' }}",

                "stock": $("#quantity").val(),
                "comissation": $("#Comissation").val() ?? "{{ $product->comissation }}",
                "add_new_product": $("#add_new_product").val(),
                "values": getIds()
            };


            $.ajax({
                url: '/{{ $userType }}/cart',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    data: cart
                },

                beforeSend() {
                    $("#addToCartBtn").hide();
                    $("#loader").addClass("d-flex");
                    $("#loader").removeClass("d-none");

                },
                success: function(response) {


                    if (
                        {{ auth()->user()->role == 'user' || auth()->user()->role == 'moderator' ? 'true' : 'false' }}) {
                        $.ajax({
                            url: '/users/cart/count',
                            success: function(response) {
                                console.log(response)
                                $("#cartCount").text(response.cartCount);
                                $("#cart").text(`( ${response.cartCount} )`);
                            }
                        });
                    }


                    returnBtns();

                    if (response.status == "success") {



                        Swal.fire({
                            title: "تم",
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'فهمت'
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
                    returnBtns()

                    Swal.fire({
                        title: 'خطا!',
                        text: "خطأ",
                        icon: 'error',
                        confirmButtonText: 'فهمت'
                    })
                }
            });

        });

        function returnBtns() {

            $("#addToCartBtn").show();
            $("#loader").removeClass("d-flex");
            $("#loader").addClass("d-none");
        }
    </script>
@endsection
