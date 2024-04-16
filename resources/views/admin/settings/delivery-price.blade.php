<div class="tableSpace">

    <div class="actions mt-0">


        <div class="contnet-title">المحافظات </div>

        <x-admin.forms.mainBtn onclick="create()" icon="plus" title="اضافة محافظة" >
        </x-admin.forms.mainBtn>



    </div>

    <p class="tip"><i aria-hidden="true" class="fas fa-info-circle "></i>

        يمكن تغير ترتيب المحافظات من خلال الازرار او من خلال Drag and Drop

    </p>

    <table>
        <thead>

            <tr>
                <th>#</th>

                <th> المحافظة </th>
                <th> الكود </th>
                <th> سعر الشحن </th>
                <th> سعر المرتجع  </th>
                <th> الإجراءات </th>

            </tr>

        </thead>
        <tbody>


            @forelse ($deliveryPrices as $deliveryPrice)
                <tr draggable='true' ondragstart='start()' ondragover='dragover()'>

                    <input type="hidden" value="{{ $deliveryPrice->id }}" class="ids">

                    <td style="font-weight: 600" data-text="#">{{ $deliveryPrice->order }}</td>


                    <td data-text='العنوان'> {{ $deliveryPrice->name }}</td>
                    <td data-text='العنوان'> {{ $deliveryPrice->code }}</td>
                    <td data-text='العنوان'> {{ $deliveryPrice->delivery_price }}</td>
                    <td data-text='العنوان'> {{ $deliveryPrice->return_price }}</td>

                    <td data-text="الإجراءات">
                        <div>
                            <div onclick="up(this)" data-tippy-content="فوق" class="square-btn ltr has-tip"><i
                                    class=" fa-solid fa-up-long mr-2  icon" aria-hidden="true"></i></div>

                            <div onclick="down(this)" data-tippy-content="تحت" class="square-btn ltr has-tip">
                                <i class=" fa-solid fa-down-long mr-2  icon" aria-hidden="true"></i>
                            </div>
                            <div type="button" data-id="{{ $deliveryPrice->id }}"
                                data-name="{{ $deliveryPrice->name }}" data-code="{{ $deliveryPrice->code }}"
                                data-delivery_price="{{ $deliveryPrice->delivery_price }}"
                                data-return_price="{{ $deliveryPrice->return_price }}"
                                onclick="show_new_value_model(this)" data-bs-toggle="modal" data-bs-target="#theForm"
                                data-tippy-content="تعديل" class="square-btn ltr has-tip">
                                <i class="far fa-edit mr-2 icon" aria-hidden="true"></i>
                            </div>


                            <div type="button" data-id="{{ $deliveryPrice->id }}"
                                data-name="{{ $deliveryPrice->name }}" onclick="show_delete_model(this)"
                                data-bs-toggle="modal" data-bs-target="#exampleModal" data-tippy-content="حذف"
                                class="square-btn ltr has-tip"><i class="far fa-trash-alt mr-2 icon"
                                    aria-hidden="true"></i></div>
                        </div>

                    </td>


                </tr>

            @empty
                <tr>

                    <td colspan="2">لا يوجد محافظات متاحة</td>
                </tr>
            @endforelse

        </tbody>


    </table>


    @if (isset($deliveryPrices[0]['id']))
        <x-admin.forms.mainBtn onclick="UpdateOrder()" icon="update" title="تحديث" class="mt-3">
        </x-admin.forms.mainBtn>
    @endif


    <form method="post" action="{{ url('admin/deliveryPrice/edit') }}" class="modal fade" id="theForm"
        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        @csrf
        @method('PUT')
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تغير البيانات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="deliveryPrice_id">

                    <x-admin.forms.input for="name" class="checkThis" lable_title="اسم المحافظة" id="name"
                        name="name" placeholder="اسم المحافظة">
                    </x-admin.forms.input>
                    <x-admin.forms.input for="code" class="checkThis" lable_title="كود المحافظة" id="code"
                        name="code" placeholder="كود المحافظة">
                    </x-admin.forms.input>
                    <x-admin.forms.input for="delivery_price" min="0" class="checkThis" type="number"
                        lable_title="سعر الشحن" id="delivery_price" name="delivery_price" placeholder="سعر الشحن">
                    </x-admin.forms.input>

                    <x-admin.forms.input for="return_price" min="0" class="checkThis" type="number"
                        lable_title="سعر المرتجع" id="return_price" name="return_price" placeholder="سعر المرتجع">
                    </x-admin.forms.input>


                </div>
                <div class="modal-footer">
                    <button type="button" onclick="validate()" class="btn btn-primary">حفط التغيرات</button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </form>


    <x-admin.forms.deleteModel model="deliveryPrice" id="deliveryPrice_id"></x-admin.forms.deleteModel>



</div>
