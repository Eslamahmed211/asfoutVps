@extends('admin/layout')


@section('path')
    <ul class="paths mx-2">
        <li> <a href="{{ url('admin/products') }}"> المنتجات / </a> </li>
        <li class="active">اضافة </li>
    </ul>
@endsection

@section('css')
    <style>
        .contnet-title {
            font-size: 16px;
            color: rgb(30, 41, 53);
            /* color: red; */
            font-weight: 700;
            width: 100%;
            border-right: solid 4px #ddd;
            background: #f5f5f5;
            padding: 8pt;
            border-radius: 2pt;
            font-family: "cairo";

        }
    </style>
@endsection



@section('content')
    <div class=" mt-lg-4 mx-3  mb-4  ">

        <form class="row    px-2 rounded " action="/admin/products" method="post" id="theForm" autocomplete="off"
            enctype="multipart/form-data">

            @csrf

            <div class=" col-12">


                <div class="bg-white px-1 py-4 rounded row">

                    <div class="col-12">
                        <div class="contnet-title mb-3">المعلومات الأساسية</div>
                    </div>

                    <div data-title="اسم المنتج" class="col-lg-6 col-12">
                        <x-admin.forms.input class="checkThis" for="name" lable_title="اسم المنتج" name="name"
                            placeholder="اسم المنتج">
                        </x-admin.forms.input>
                    </div>

                    <div data-title="اسم الشهرة" class="col-lg-6 col-12">
                        <x-admin.forms.input class="checkThis" for="nickName" lable_title="اسم الشهرة" name="nickName"
                            placeholder="اسم الشهرة">
                        </x-admin.forms.input>
                    </div>


                    <div data-title="وصف المنتج" class="col-12 mb-4">

                        <input type="hidden" id="hiddenArea" name="dis">

                        <label class="mb-3">وصف المنتج</label>

                        <div class="quill-container overflow-hidden">
                            <div id="editor" class="quill-editor quill">
                                <?= old('dis') ?>
                            </div>
                        </div>
                    </div>


                    {{-- -------------------------------- --}}


                    <div class="col-12">
                        <div class="contnet-title mb-3">المعلومات الرقمية</div>
                    </div>




                    <div data-title="سعر المنتج" class="col-lg-3 col-12">
                        <x-admin.forms.input min="0" type="number" class="checkThis" for="price"
                            lable_title="سعر المنتج" name="price">
                        </x-admin.forms.input>
                    </div>

                    <div data-title="عمولة السيستم" class="col-lg-3 col-12">
                        <x-admin.forms.input min="0" type="number" class="checkThis" for="systemComissation"
                            lable_title="عمولة السيستم" name="systemComissation">
                        </x-admin.forms.input>
                    </div>


                    <div data-title="عمولة المسوق"  class="col-lg-3 col-12">
                        <x-admin.forms.input notRequired min="0" type="number" for="comissation" lable_title="عمولة المسوق"
                            name="comissation">
                        </x-admin.forms.input>
                    </div>

                    <div data-title="البونص" class="col-lg-3 col-12">
                        <x-admin.forms.input notRequired min="0" type="number" for="ponus" lable_title="البونص"
                            name="ponus">
                        </x-admin.forms.input>
                    </div>

                    {{-- ------------------------ --}}

                    <div data-title="الحد الادني للعمولة" class="col-lg-4 col-12">
                        <x-admin.forms.input notRequired min="0" type="number" for="min_comissation"
                            lable_title="الحد الادني للعمولة" name="min_comissation">
                        </x-admin.forms.input>
                    </div>

                    <div data-title="الحد الاقصي للعمولة" class="col-lg-4 col-12">
                        <x-admin.forms.input notRequired min="0" type="number" for="max_comissation"
                            lable_title="الحد الاقصي للعمولة" name="max_comissation">
                        </x-admin.forms.input>
                    </div>


                    <div data-title="كمية المنتج " class="col-lg-4 col-12 ">
                        <x-admin.forms.input class="checkThis" min="0" value="0"   type="number" for="stock"
                            lable_title="كمية المنتج" name="stock">
                        </x-admin.forms.input>
                    </div>


                    <div class="col-lg-6 col-12" title="عرض في المنتجات الغير المتوفرة">
                        <label class="mb-2" for="active"> عرض في المنتجات الغير المتوفرة </label>
                        <select name="unavailable" class="checkThis">
                            <option value="yes">نعم</option>
                            <option value="no" selected>لا</option>
                        </select>
                    </div>




                    <div data-title="وحدة الإحتفاظ بالمخزون (SKU)" class="col-lg-6 col-12 mt-lg-0 mt-2">

                        <x-admin.forms.input notRequired class="mono" for="sku"
                            lable_title="وحدة الإحتفاظ بالمخزون (SKU)" name="sku" placeholder="SKU-100001">
                        </x-admin.forms.input>
                    </div>






                    <div data-title="تصنيفات المنتج" class="col-lg-6 col-12 mb-4 ">
                        <label class="mb-3">تصنيفات المنتج</label>

                        <select @class(['invalid' => $errors->has('categories'), 'select-dropdown']) name="categories[]" multiple>


                            @forelse ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @empty
                                <option disabled>لا يوجد تصنيفات مضافة</option>
                            @endforelse
                        </select>


                        @error('categories')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>

                    <div data-title="التاجر" class="col-lg-6 col-12 mb-4 ">
                        <label class="mb-3">التاجر</label>

                        <select @class(['invalid' => $errors->has('trader_id')]) id="select-dropdown" name="trader_id">


                            @forelse ($traders as $trader)
                                <option value="{{ $trader->id }}">{{ $trader->mobile }} {{ $trader->name }}
                                </option>
                            @empty
                                <option disabled>لا يوجد تجار مضافة</option>
                            @endforelse
                        </select>


                        @error('trader_id')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <div data-title="الرابط" class="col-lg-6 col-12">

                        <x-admin.forms.input notRequired type="text" for="slug"
                            lable_title="اسم المنتح في الرابط " name="slug" placeholder="اسم المنتح في الرابط ">

                            <x-slot:info><i aria-hidden="true"
                                    data-tippy-content='تسخدم ككلمة مفتاحية في الرابط علشان تتارشف في محركات البحث'
                                    style="color: #aaa;" class="fas fa-question-circle"></i></x-slot:info>

                        </x-admin.forms.input>
                    </div>


                    <div data-title="لينك الدريف" class="col-lg-6 col-12">

                        <x-admin.forms.input notRequired type="url" for="drive" lable_title="لينك الدريف"
                            name="drive" placeholder="لينك الدريف">


                        </x-admin.forms.input>
                    </div>



                </div>

            </div>







            <div class="col-12">
                <x-admin.forms.mainBtn onclick="validate()" icon="plus" title="اضافة منتج جديد" class="mt-3">
                </x-admin.forms.mainBtn>
            </div>




        </form>

    </div>
@endsection


@section('js')
    <script>
        $('#aside .products').addClass('active');
        tippy('[data-tippy-content]');

        $(".content").css("padding", "0px 10px")


        $(".select-dropdown").selectize({

            delimiter: ',',
            plugins: ["restore_on_backspace", "clear_button", "drag_drop", "remove_button"],

            persist: false,
            maxItems: null,

            placeholder: 'اختار تصنيفات من هنا',


        });


        $("#select-dropdown").selectize({

            delimiter: ',',
            persist: false,
            maxItems: 1,
            // searchField: ['name'],
            placeholder: 'اختار تاجر من هنا',


        });

        $('#ssi-uploader').ssi_uploader({
            inForm: true,
            dropZone: true,
            allowed: ['jpg', 'jpeg', 'png', 'webp', 'gif'],
            allowDuplicates: false,

        });
    </script>
@endsection
