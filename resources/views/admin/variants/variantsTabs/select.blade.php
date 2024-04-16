<form action="/admin/products/{{ $product->id }}/storeAttributes" method="post" autocomplete="off">

    @csrf



    <div class="bg-white px-1 py-0 mt-1 rounded row g-4">



        <div data-title="الخصائص" class="mt-0">


            <label class="mb-3">اختار خصائص المنتج</label>

            <select @class(["select-dropdown" ,'invalid' => $errors->has('attributes')])  name="attributes[]" multiple>


                @forelse ($product->attributes_all as $attribute)
                    <option @if (App\Models\product::attributes_check($attribute, $productAttributes)) selected @endif value="{{ $attribute->id }}">
                        {{ $attribute->name . ' ' }} {{ $attribute->key }}</option>

                @empty
                    <option disabled>لا يوجد خصائص مضافة</option>
                @endforelse
            </select>


            @error('attributes')
                <div class="error">{{ $message }}</div>
            @enderror

        </div>




        <div class="col-12">
            <x-admin.forms.mainBtn type="submit" icon="plus" title="اضافة الخصائص" class="mt-3">
            </x-admin.forms.mainBtn>
        </div>




    </div>




</form>
