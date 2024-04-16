<div class=" mt-lg-4 mx-3  ">


    <form class="row p-0" action="/admin/attribute/add" method="post" id="theForm" autocomplete="on">

        @csrf

        <input type="hidden" value="{{ $product->id }}" name="product_id">

        <div class="col-lg-3 col-12">
            <x-admin.forms.input class="checkThis" for="name" lable_title="اسم الخاصية" name="name"
                placeholder="مثال اللون">
            </x-admin.forms.input>
        </div>

        <div class="col-lg-4 col-12">
            <x-admin.forms.input notRequired for="key" lable_title="كملة دلالية" name="key"
                placeholder="كلمة لتميز هذة الخاصية عن باقي الخواص ">
            </x-admin.forms.input>
        </div>

        <div class="col-lg-5 col-12">
            <x-admin.forms.input class="checkThis" for="values" lable_title="القيم" name="values"
                placeholder=" اكتب القيم مثال (احمر - اخضر - ازرق) ">
            </x-admin.forms.input>
        </div>


        <div class="col-12">
            <x-admin.forms.mainBtn onclick="validate()" icon="plus" title="اضافة" class="mt-3">
            </x-admin.forms.mainBtn>
        </div>




    </form>


    <div class="mt-4 tableSpace p-0">


        <table class="not">
            <thead>

                <tr>

                    <th>الاسم</th>
                    <th>كلمة دلالية</th>
                    <th>القيم</th>
                    <th> الإجراءات </th>

                </tr>

            </thead>
            <tbody>


                @forelse ($product->attributes_all as $attribute)
                    <tr>

                        <td>{{ $attribute->name }}</td>
                        <td>{{ $attribute->key }}</td>
                        <td>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($attribute->values as $value)
                                @if ($i == 1)
                                    {{ $value->value }}
                                @else
                                    - {{ $value->value }}
                                @endif

                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        </td>


                        <td data-text="الإجراءات">
                            <div>


                                <div onclick='window.location.href = "/admin/attributes/add/{{ $attribute->id }}"'
                                    id="myButton" data-tippy-content="تعديل" class="square-btn ltr has-tip"><i
                                        class="far fa-edit mr-2  icon" aria-hidden="true"></i></div>


                                <div type="button" data-id="{{ $attribute->id }}"
                                    data-name="{{ $attribute->name }} @if ($attribute->slug) {{ ' - ' . $attribute->key }} @endif"
                                    onclick="show_delete_model(this)" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal" data-tippy-content="حذف"
                                    class="square-btn ltr has-tip"><i class="far fa-trash-alt mr-2 icon"
                                        aria-hidden="true"></i></div>
                            </div>

                        </td>


                    </tr>

                @empty
                    <tr>

                        <td colspan="4">لا يوجد خصائص متاحة</td>
                    </tr>
                @endforelse

            </tbody>


        </table>



        <x-admin.forms.deleteModel model="attribute" id="attribute_id"></x-admin.forms.deleteModel>



    </div>

</div>
