@extends('admin/layout')


@section('css')
    <style>
        .square-btn {
            margin: 2px;
        }

        .product {
            border-radius: 0.75rem;
            --tw-bg-opacity: 1;
            background-color: rgb(255 255 255 / var(--tw-bg-opacity));
            padding: 25px 25px;
            --tw-shadow: 0 10px 15px -3px rgb(0 0 0 / .1), 0 4px 6px -4px rgb(0 0 0 / .1);
            --tw-shadow-colored: 0 10px 15px -3px var(--tw-shadow-color), 0 4px 6px -4px var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
        }

        .product {
            position: relative;
        }

        .checkBox {
            position: absolute;
            left: 10px;
            top: 10px;
        }

        .product_img {
            border-radius: 50%;
            width: 90px;
            height: 90px;
            overflow: hidden;
            text-align: center;
            margin: auto;
        }




        .product_content .product_name {
            font-size: 1.125rem;
            font-weight: 700;
            text-align: center;
            margin-top: 20px;

        }

        .prodcut_img {
            padding: 5px;
            width: 100px;
            height: 80px;
            object-fit: contain;

        }

        table:not(.ssi-imgToUploadTable) td:first-child,
        table:not(.ssi-imgToUploadTable) td:last-child {
            padding: 0 !important;

        }
    </style>
@endsection



@section('content')
    <div class="actions">
        <div class="contnet-title">المنتجات </div>


        @can('has', 'products_action')
            <div class="d-flex align-items-center gap-2">


                <x-searchBtn></x-searchBtn>

                <x-link path="/admin/products/create" title="اضافة منتج"><svg width="22" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg></x-link>

            </div>
        @endcan
    </div>


    <x-searchForm withExel action="/admin/products/search">

        @php
            !empty($_GET['id']) ? ($id = $_GET['id']) : ($id = '');
            !empty($_GET['name']) ? ($name = $_GET['name']) : ($name = '');
            !empty($_GET['active']) ? ($active = $_GET['active']) : ($active = '');
            !empty($_GET['deleted']) ? ($deleted = $_GET['deleted']) : ($deleted = '');
            !empty($_GET['trader_id']) ? ($trader_id = $_GET['trader_id']) : ($trader_id = '');
            !empty($_GET['category_id']) ? ($category_id = $_GET['category_id']) : ($category_id = '');
        @endphp


        <x-admin.forms.input name="id" value="{{ $id }}" lable_title="كود المنتج"
            placeholder="مثال : 123456">
        </x-admin.forms.input>

        <x-admin.forms.input name="name" value="{{ $name }}" lable_title="اسم المنتج"
            placeholder="مثال : كوتشي رجالي">
        </x-admin.forms.input>


        <div title="حالة المنتج">

            <label for="name" class="mb-2">حالة المنتج </label>

            <select name="active">
                <option value="">كل الحالات</option>
                <option @if ($active == 'نشط') selected @endif value="نشط">نشط</option>
                <option @if ($active == 'غير نشط') selected @endif value="غير نشط">غير نشط</option>
            </select>
        </div>


        <div title="التاجر">

            <label for="name" class="mb-2"> التاجر </label>


            <select id="select-dropdown" name="trader_id">

                <option value="">كل التجار</option>


                @forelse ($traders as $trader)
                    <option @if ($trader_id == $trader->id) selected @endif value="{{ $trader->id }}">
                        {{ $trader->mobile }} {{ $trader->name }}
                    </option>
                @empty
                    <option disabled>لا يوجد تجار مضافة</option>
                @endforelse
            </select>

        </div>

        <div title="التصنيف">

            <label for="name" class="mb-2"> التصنيف </label>

            <select name="category_id">
                <option value="">كل التصنيفات</option>

                @forelse ($categories as $category)
                    <option @if ($category_id == $category->id) selected @endif value="{{ $category->id }}">
                        {{ $category->name }}</option>
                @empty
                    <option disabled>لا يوجد تصنيفات مضافة</option>
                @endforelse
            </select>

        </div>


        <div title="محذوف">

            <label for="name" class="mb-2"> نوع المنتجات </label>


            <select name="deleted">
                <option value="" @if ($deleted == '') selected @endif>غير محذوف فقط</option>
                <option value="withTrashed" @if ($deleted == 'withTrashed') selected @endif>محذوف او غير محذوف
                </option>
                <option value="onlyTrashed" @if ($deleted == 'onlyTrashed') selected @endif>محذوف فقط</option>
            </select>
        </div>



    </x-searchForm>


    <div class="tableSpace">


        <table>
            <thead>

                <tr>

                    <th> الصورة</th>
                    <th>اسم المنتج</th>
                    <th>التاجر</th>
                    <th>السعر</th>
                    <th> العمولة </th>
                    <th> البونص </th>
                    <th> الكمية</th>
                    <th> م.ف </th>
                    @can('has', 'products_action')
                        <th> حالة المنتج </th>
                        <th> الإجراءات </th>
                    @endcan

                </tr>

            </thead>
            <tbody>


                @forelse ($products as $product)
                    <tr>

                        @php
                            if (isset($product->imgs[0])) {
                                $img = str_replace('public', 'storage', $product->imgs[0]->img);
                                $img = asset("$img");
                            } else {
                                $img = 'https://placehold.co/600x400?text=product+img';
                            }

                        @endphp

                        <td data-text='صورة المنتج '><img class="prodcut_img" src="{{ $img }}"></td>

                        <td data-text='اسم المنتج '> <a target="_blank"
                                href="/admin/products/{{ $product->id }}/edit">{{ $product->name }}</a> </td>

                        <td data-text='التاجر'> {{ $product->trader->name ?? '' }} </td>


                        <td data-text='سعر المنتج '> {{ $product->price + $product->systemComissation }} </td>

                        <td data-text='العمولة'> {{ $product->comissation }} </td>

                        <td data-text='البونص'> {{ $product->ponus ?? 0 }} </td>

                        <td data-text="عدد القطع المتاحة "> <a target="_blank"
                                href="/admin/products/{{ $product->id }}/logs"> {{ $product->stock ?? 'غير محدود ' }}
                            </a> </td>

                        <td data-text="كمية المنتجات الفرعية "> {{ variantStock($product->id) }} </td>
                        @can('has', 'products_action')
                            <td data-text='حالة المنتج '>
                                <div class="form-element inlined switch intable">
                                    <div class="flex">
                                        <label>
                                            <div class="toggle-container lg">
                                                <input value="{{ $product->id }}" onchange="showHideProduct(this)"
                                                    @if ($product->show) checked @endif type="checkbox"
                                                    class="sr-only">
                                                <div class="switch-bg"></div>
                                                <div class="dot"></div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </td>

                            <td data-text="الإجراءات">
                                @if (is_null($product->deleted_at))
                                    <div>


                                        <div onclick='window.location.href = "/admin/products/{{ $product->id }}/variants"'
                                            id="myButton" data-tippy-content="منتجات فرعية" class="square-btn ltr has-tip ">
                                            <i class="fa-solid fa-gear mr-2  icon fa-fw" aria-hidden="true"></i>
                                        </div>

                                        <div onclick='qr({{ $product->id }})' data-tippy-content="طباعة الباركود"
                                            class="square-btn ltr has-tip ">
                                            <i class="fa-solid fa-qrcode mr-2  icon fa-fw" aria-hidden="true"></i>
                                        </div>

                                        <div onclick='window.location.href = "/admin/products/{{ $product->id }}/optimize"'
                                            id="myButton" data-tippy-content="تخصيص المنتج للمسوقين"
                                            class="square-btn ltr has-tip"><i
                                                class="fa-solid fa-unlock  @if ($product->optimizes_count > 0) fa-bounce @endif mr-2  icon fa-fw"
                                                aria-hidden="true"></i></div>


                                        <div type="button" data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                            onclick="show_delete_model(this)" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal" data-tippy-content="حذف"
                                            class="square-btn ltr has-tip"><i class="far fa-trash-alt mr-2 icon fa-fw"
                                                aria-hidden="true"></i>
                                        </div>
                                    </div>
                                @endif

                            </td>
                        @endcan


                    </tr>

                @empty
                    <tr>

                        <td colspan="10">لا يوجد منتجات متاحة</td>
                    </tr>
                @endforelse

            </tbody>


        </table>

        <x-admin.forms.deleteModel withInput model="products" id="product_id"></x-admin.forms.deleteModel>



    </div>

    @if (!Request::is('admin/products/search'))
        <div class="pagnate" class="السابق والتالي">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif
@endsection



@section('js')
    <script>
        $('#aside .products').addClass('active');

        $(".content").css("backgroundColor", "transplant")

        tippy('[data-tippy-content]');

        function show_delete_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $('#model_title').text(data_name);

            $("input[name='product_id']").val(data_id)

        }

        function showHideProduct(e) {
            let id = e.value;

            let show = 0;

            if (e.checked) {
                show = 1;
            }

            fetch(`/admin/products/showHideProduct`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({
                        id: id,
                        show: show
                    }),
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json(); // Parse the response body as JSON
                })

        }

        function qr(id) {

            $.ajax({
                url: `/admin/products/${id}/qr`,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.status == "success") {
                        let html = `
                    <section id="print" class="test text-center">
                        <div style="font-family: cairo ; margin-top:1px;" >
                            ${response.qr}
                        </div>
                        <div style="margin-top: 0px">
                            <div class="name" style="font-size: 12px">${response.product.name}</div>
                        </div>

                        <style>
                            @page {
                                size: 4in 2in;
                                display: flex;
                                align-items: center;
                                justify-content: center;

                            }

                            @media print {
                              body {
                                  padding:5px;
                                  display: flex;
                                align-items: center;
                                justify-content: center;
                                flex-direction: column;
                              }


                            #print   {
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                flex-direction: column;
                                font-family: 'Cairo', sans-serif;
                            }
                          }




                            #print   {
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                flex-direction: column;
                                font-family: 'Cairo', sans-serif;
                            }
                            .nameprint {
                                font-family: 'Cairo', sans-serif;
                            }
                            #id {
                                font-family: cairo;
                                font-size: 13px;
                            }
                            #name {
                                text-align: test;
                            }
                            .value {
                                direction: rtl;
                                -webkit-print-color-adjust: exact;
                            }
                        </style>

                    </section>
                `;

                        Swal.fire({
                            title: "",
                            html: html,
                            focusConfirm: false,
                            showConfirmButton: true,
                            inputAutoFocus: false,
                            confirmButtonText: "طباعة",
                        }).then((result) => {
                            if (result.isConfirmed) {

                                $('#print').printThis({

                                    importCSS: true,
                                    importStyle: true,
                                });
                            }
                        })
                    }
                },
            });

        }
    </script>
@endsection
