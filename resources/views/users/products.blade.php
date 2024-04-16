@extends('admin.layout')


@if (auth()->user()->role == 'user' || auth()->user()->role == 'moderator')
    @php
        $userType = 'users';

    @endphp
@elseif(auth()->user()->role == 'admin' || auth()->user()->role == 'super')
    @php
        $userType = 'admin/toUser';

    @endphp
@endif

@section('css')
    <style>
        @media (max-width:993px) {

            .products .actions {
                flex-direction: column-reverse;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $searchUrl = url("$userType/products/search");
    @endphp

    <x-searchForm :action="$searchUrl">
        @php
            !empty($_GET['name']) ? ($name = $_GET['name']) : ($name = '');
            !empty($_GET['category_id']) ? ($category_id = $_GET['category_id']) : ($category_id = '');
            !empty($_GET['order']) ? ($order = $_GET['order']) : ($order = '');
            !empty($_GET['fav']) ? ($fav = $_GET['fav']) : ($fav = '');
        @endphp

        @if (!empty($_GET['add_new_product']))
            <input type="hidden" name="add_new_product" value="{{ $_GET['add_new_product'] }}">
        @endif

        <x-admin.forms.input name="name" value="{{ $name }}" lable_title="اسم المنتج"
            placeholder="مثال : كوتشي رجالي">
        </x-admin.forms.input>

        <div data-title="التصنيف">

            <label for="name" class="mb-2"> التصنيف </label>

            <select name="category_id">

                <option value="">كل التصنيفات</option>

                @foreach ($categories as $category)
                    <option @if ($category_id == $category->id) selected @endif value="{{ $category->id }}">
                        {{ $category->name }} </option>
                @endforeach

            </select>

        </div>



        <div data-title="رتب حسب">

            <label for="name" class="mb-2"> رتب حسب </label>

            <select name="order">

                <option value="" selected="">رتب حسب</option>
                <option @if ($order == 'new') selected @endif value="new">الاحدث</option>
                <option @if ($order == 'big_price') selected @endif value="big_price">السعر (الاعلي &gt;الاقل)
                </option>
                <option @if ($order == 'low_price') selected @endif value="low_price">السعر (الاقل &gt;الاعلي)
                </option>
            </select>


        </div>

        @if ($userType == 'users')
            <label for="name" class="mb-2"> كل المنتجات </label>


            <div data-title="كل المنتجات">

                <select name="fav">

                    <option @if ($fav == 'no') selected @endif value="no" selected="">كل المنتجات
                    </option>
                    <option @if ($fav == 'yes') selected @endif value="yes">المفضلة فقط</option>
                </select>


            </div>
        @endif

    </x-searchForm>



    <div class="actions">


        <div class="contnet-title">المنتجات </div>

        <div class="d-flex align-items-center gap-2">


            <x-searchBtn></x-searchBtn>


        </div>



    </div>

    <div data-title="المنتجات" class="mt-3">
        <div class="row products g-lg-3 g-1">
            @foreach ($products as $product)
                @if (CanAccess($product->id, auth()->user()->id))
                    <div class="col-lg-3 col-6 mb-2">
                        <div class="product position-relative">

                            <a
                                href="/{{ $userType }}/products/{{ $product->slug }}@if (!empty($_GET['add_new_product'])) ?add_new_product={{ $_GET['add_new_product'] }} @endif">
                                <div class="product_img">
                                    <img src="{{ path($product->firstImg->img) }}" alt="{{ $product->name }}">
                                </div>
                            </a>


                            <p class="product_name"> {{ $product->name }} </p>

                            <div class="more ">
                                <div> <strong>السعر </strong> <span class="product_price">
                                        {{ $product->price + $product->systemComissation }}
                                        ج.م</span>
                                </div>
                                <div>
                                    @if ($product->stock === 0)
                                        <strong> كمية المنتج </strong> <span class="product_stock">
                                            غير متوفر

                                        </span>
                                    @else
                                        <strong> كمية المنتج </strong> <span class="product_stock">
                                            {{ $product->stock ? $product->stock . '  قطع  ' : 'غير محدود' }}

                                        </span>
                                    @endif

                                </div>
                            </div>

                            <div class="actions mt-3 mb-2 f-mobile " style="gap: 1px">


                                @php
                                    $productSlug = $product->slug;
                                    $addNewProduct = !empty($_GET['add_new_product']) ? '?add_new_product=' . $_GET['add_new_product'] : '';
                                    $path = "/$userType/products/{$productSlug}{$addNewProduct}";
                                @endphp

                                <x-admin.forms.mainBtn onclick="window.location.href = '{{ $path }}' "
                                    class="w-50 border-0" title="عرض المنتج">
                                </x-admin.forms.mainBtn>

                                @php
                                    $id = $product->id;
                                @endphp


                                @if ($userType == 'users')
                                    <button
                                        @if (isset($product->favourites[0]->id)) onclick="delete_fav(event ,  this , {{ $id }})"  @else
                              onclick="addFav(  event ,  this ,  {{ $id }})" @endif
                                        class="mainBtn d-flex favBtn w-50"style="border: none">المفضلة

                                        <div class="svg">

                                            @if (isset($product->favourites[0]->id))
                                                <svg class="heart" style="width:20px;height:20px ;" viewBox="0 0 24 24">
                                                    <path fill="#ff4966"
                                                        d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z">
                                                    </path>
                                                </svg>
                                            @else
                                                <svg class="heart opacity-75" style="width:20px;height:20px ;"
                                                    viewBox="0 0 24 24">
                                                    <path fill="#aaa"
                                                        d="M12.1 18.55L12 18.65L11.89 18.55C7.14 14.24 4 11.39 4 8.5C4 6.5 5.5 5 7.5 5C9.04 5 10.54 6 11.07 7.36H12.93C13.46 6 14.96 5 16.5 5C18.5 5 20 6.5 20 8.5C20 11.39 16.86 14.24 12.1 18.55M16.5 3C14.76 3 13.09 3.81 12 5.08C10.91 3.81 9.24 3 7.5 3C4.42 3 2 5.41 2 8.5C2 12.27 5.4 15.36 10.55 20.03L12 21.35L13.45 20.03C18.6 15.36 22 12.27 22 8.5C22 5.41 19.58 3 16.5 3Z">
                                                    </path>
                                                </svg>
                                                </svg>
                                            @endif


                                        </div>
                                    </button>
                                @endif



                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="pagnate" class="السابق والتالي">
        {{ $products->appends(request()->query())->links() }}
    </div>


@endsection


@section('js')
    <script>
        $('aside .products').addClass('active');
    </script>
@endsection
