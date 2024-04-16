<form action="/admin/products/{{ $product->id }}" method="post" id="theForm" autocomplete="off">

    @csrf
    @method('put')



    <div class="bg-white px-1 py-2 mt-1 rounded row g-4">

        <div class="col-12">
            <div class="contnet-title mb-3">المعلومات الأساسية</div>
        </div>

        <div data-title="اسم المنتج" class="col-lg-6 col-12">
            <x-admin.forms.input class="checkThis" for="name" value="{{ $product->name }}" lable_title="اسم المنتج"
                name="name" placeholder="اسم المنتج">
            </x-admin.forms.input>
        </div>

        <div data-title="اسم الشهرة" class="col-lg-6 col-12">
            <x-admin.forms.input class="checkThis" value="{{ $product->nickName }}" for="nickName"
                lable_title="اسم الشهرة" name="nickName" placeholder="اسم الشهرة">
            </x-admin.forms.input>
        </div>



        <div data-title="وصف المنتج" class="col-12 mb-4">

            <input type="hidden" id="hiddenArea" name="dis">

            <label class="mb-3">وصف المنتج</label>

            <div class="quill-container overflow-hidden">
                <div id="editor" class="quill-editor quill">
                    <?= old('dis') ?? $product->dis ?>
                </div>
            </div>
        </div>



        <div data-title="سعر المنتج" class="col-lg-3 col-12">
            <x-admin.forms.input min="0" type="number" class="checkThis" for="price" lable_title="سعر المنتج"
                value="{{ $product->price }}" name="price">
            </x-admin.forms.input>
        </div>

        <div data-title="عمولة السيستم" class="col-lg-3 col-12">
            <x-admin.forms.input min="0" type="number" class="checkThis" for="systemComissation"
                lable_title="عمولة السيستم" value="{{ $product->systemComissation }}" name="systemComissation">
            </x-admin.forms.input>
        </div>






        <div data-title="عمولة المسوق" class="col-lg-3 col-12">
            <x-admin.forms.input min="0" notRequired value="{{ $product->comissation }}" type="number"
                for="comissation" lable_title="عمولة المسوق" name="comissation">
            </x-admin.forms.input>
        </div>

        <div data-title="البونص" class="col-lg-3 col-12">
            <x-admin.forms.input notRequired min="0" value="{{ $product->ponus }}" type="number" for="ponus"
                lable_title="البونص" name="ponus">
            </x-admin.forms.input>
        </div>



        <div data-title="الحد الادني للعمولة" class="col-lg-4 col-12">
            <x-admin.forms.input notRequired min="0" value="{{ $product->min_comissation }}" type="number"
                for="min_comissation" lable_title="الحد الادني للعمولة" name="min_comissation">
            </x-admin.forms.input>
        </div>

        <div data-title="الحد الاقصي للعمولة" class="col-lg-4 col-12">
            <x-admin.forms.input notRequired min="0" value="{{ $product->max_comissation }}" type="number"
                for="max_comissation" lable_title="الحد الاقصي للعمولة" name="max_comissation">
            </x-admin.forms.input>
        </div>


        <div data-title="كمية المنتج " class="col-lg-4 col-12">
            <x-admin.forms.input class="checkThis" min="0" type="number" for="stock" lable_title="كمية المنتج"
                value="{{ $product->stock }}" name="stock">
            </x-admin.forms.input>
        </div>

        <div data-title="كمية المنتجات الفرعية " class="col-lg-3 col-12">
            <x-admin.forms.input readonly for="stock" lable_title="كمية المنتجات الفرعية "
                value="{{ variantStock($product->id) ?? '' }}
                " name="" disabled>
            </x-admin.forms.input>
        </div>


        <div data-title="الرابط" class="col-lg-4 col-12">

            <x-admin.forms.input notRequired type="text" for="slug" lable_title="اسم المنتح في الرابط "
                name="slug" value="{{ $product->slug }}" placeholder="اسم المنتح في الرابط ">

                <x-slot:info><i aria-hidden="true"
                        data-tippy-content='تسخدم ككلمة مفتاحية في الرابط علشان تتارشف في محركات البحث'
                        style="color: #aaa;" class="fas fa-question-circle"></i></x-slot:info>

            </x-admin.forms.input>
        </div>


        <div data-title="وحدة الإحتفاظ بالمخزون (SKU)" class="col-lg-5 col-12">

            <x-admin.forms.input class="mono checkThis" for="sku" lable_title="وحدة الإحتفاظ بالمخزون (SKU)"
                value="{{ $product->sku }}" name="sku" placeholder="SKU-100001">
            </x-admin.forms.input>
        </div>


        <div class="col-lg-6 col-12" title="عرض في المنتجات الغير المتوفرة">
            <label class="mb-2" for="active"> عرض في المنتجات الغير المتوفرة </label>
            <select name="unavailable" class="checkThis">
                <option @if ($product->unavailable == 'yes') selected @endif value="yes">نعم</option>
                <option @if ($product->unavailable == 'no') selected @endif value="no">لا</option>
            </select>
        </div>


        <div data-title="التاجر" class="col-lg-6 col-12">

            <x-admin.forms.input disabled class=" checkThis" for="trader" lable_title="التاجر"
                value="{{ $product->trader->name ?? '' }} " name="trader">
            </x-admin.forms.input>
        </div>




        <div data-title="تصنيفات المنتج" class=" col-12 mb-4 ">
            <label class="mb-3">تصنيفات المنتج</label>

            <select @class(['invalid' => $errors->has('categories')]) id="select-dropdown" name="categories[]" multiple>


                @forelse ($categories as $category)
                    @if (App\Models\product::category_check($category, $product))
                        <option selected value="{{ $category->id }}">{{ $category->name }}</option>
                    @else
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endif
                @empty
                    <option disabled>لا يوجد تصنيفات مضافة</option>
                @endforelse
            </select>


            @error('categories')
                <div class="error">{{ $message }}</div>
            @enderror

        </div>



        <div data-title="لينك الدريف" class="col-12">
            <x-admin.forms.input notRequired type="url" for="drive" lable_title="لينك الدريف"
                value="{{ $product->drive }}" name="drive">
            </x-admin.forms.input>
        </div>






    </div>


    <x-admin.forms.mainBtn onclick="validate()" icon="edit" title="تعديل البيانات" class="mt-3">
    </x-admin.forms.mainBtn>




</form>
