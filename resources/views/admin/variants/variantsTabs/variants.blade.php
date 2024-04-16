<form action="/admin/products/{{ $product->id }}/variants" method="post" autocomplete="off">

    @csrf



    <div class="bg-white px-1 py-0 mt-1 rounded row ">




        @foreach ($product->attributes as $attribute)
            <label>{{ $attribute->name }}</label>

            <select name="attrs[{{ $attribute->name }}][]" class="select-dropdown my-2 px-1">

                <option disabled selected></option>

                @foreach ($attribute->values as $value)
                    <option value="{{ $value->id }}">{{ $value->value }}</option>
                @endforeach


            </select>
        @endforeach



        <div data-title="كمية المنتج " class="col-lg-3 col-12 px-1">
            <x-admin.forms.input min="0" value="0" type="number" for="stock" lable_title="كمية المنتج" name="stock">
            </x-admin.forms.input>
        </div>




        <div data-title="اضافة" class="p-0">
            <x-admin.forms.mainBtn type="submit" icon="plus" title="اضافة منتج فرعي جديد" class="mt-3">
            </x-admin.forms.mainBtn>


        </div>




    </div>




</form>

<div data-title="ازالة" id="delete" class="col-12 " style="display: none">
    <form action="/admin/products/variants/bulkDelete" method="POST" id="bulkDeleteForm">
        @method('delete')
        @csrf
        <input type="hidden" name="ids" id="ids">
    </form>

    <x-admin.forms.mainBtn onclick="bulkDelete()" title="ازالة المنتجات الفرعية المحددة" icon="delete"
        class="ssi-button info"></x-admin.forms.mainBtn>
</div>




<div class="tableSpace  p-0 mt-3">
    <table class="w-100 not">

        <thead>
            <tr>
                <th><input onchange="allVarant(this)" type="checkbox" class="not"></th>
                <th>الخصائص</th>
                <th>كمية المنتح</th>
                <th>الاجراءات</th>

            </tr>
        </thead>

        <tbody>
            @forelse ($product->variants as $variant)
                <tr>


                    <td><input type="checkbox" onchange="check()" class="not variantCheckBox"
                            value="{{ $variant->id }}"></td>

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



                    <td>

                        <div onclick='qr({{ $variant->id }})' data-tippy-content="طباعة الباركود"
                            class="square-btn ltr has-tip ">
                            <i class="fa-solid fa-qrcode mr-2  icon fa-fw" aria-hidden="true"></i>
                        </div>


                        <div type="button" data-id="{{ $variant->id }}" data-name="{{ $variant->stock }}"
                            onclick="show_new_value_model(this)" data-bs-toggle="modal" data-bs-target="#exampleModal2"
                            data-tippy-content="تعديل كمية المنتج" class="square-btn ltr has-tip">
                            <i class="far fa-edit mr-2 icon" aria-hidden="true"></i>
                        </div>


                        <div type="button" data-id="{{ $variant->id }}" data-name="{{ $name }}"
                            onclick="deleteVariant(this)" data-bs-toggle="modal" data-bs-target="#deleteVariant"
                            data-tippy-content="حذف" class="square-btn ltr has-tip"><i
                                class="far fa-trash-alt mr-2 icon" aria-hidden="true"></i></div>
                    </td>




                </tr>
            @empty
                <td colspan="4">لا يوجد منتجات فرعية</ف>
            @endforelse
        </tbody>
    </table>
</div>



{{-- delete model --}}
<x-admin.forms.deleteModel withInput model="products/variant" id="variant_id"
    modelId="deleteVariant"></x-admin.forms.deleteModel>
{{-- update model --}}
<form method="post" action="{{ url('admin/products/variants/updateStock') }}" class="modal fade" id="exampleModal2"
    tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @csrf
    @method('put')
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">تزويد او خصم الاستوك</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="variant_id">
                <input type="text" name="stock" class="w-100" id="stock">
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">حفط التغيرات</button>

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</form>
