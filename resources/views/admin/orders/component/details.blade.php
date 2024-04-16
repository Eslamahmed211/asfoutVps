<div class="row bg-white  py-2 px-2 rounded">

    <div class="firstRow">
        <div class="id"># {{ $order->reference }}</div>
        <div class="orderStatus {{ StatusClass($order->status) }}">{{ $order->status }}</div>
        <div class="time">
            <span> {{ $order->created_at }}
            </span>
        </div>

    </div>

    @if ($order->notes)
        <div class="notes">
            {{ $order->notes }}
        </div>
    @endif

    @if ($order->notesBosta)
        <div class="notes">
            {{ $order->notesBosta }}
        </div>
    @endif

    <div class="col-lg-8 mb-3 mt-2">


        <div class="p-0 " style="overflow-x: scroll">

            <table class="not " style="background: #f8f8f8;">
                <thead>
                    <tr>
                        <th>صورة المنتج</th>
                        <th>اسم المنتج</th>
                        <th>عدد القطع</th>
                        <th>السعر</th>
                        <th>العمولة</th>
                        <th>البونص</th>
                        <th>الاجمالي</th>

                        @if (
                            $order->status == 'قيد المراجعة' ||
                                $order->status == 'محاولة تانية' ||
                                $order->status == 'قيد الانتظار' ||
                                $order->status == 'تم المراجعة' ||
                                $order->status == 'مؤجل تسليمها' ||
                                $order->status == 'جاري التجهيز للشحن' ||
                                $order->status == 'جاري التجيهز شحن يدوي' ||
                                $order->status == 'في انتظار ردك')
                            <th>ادارة</th>
                        @endif

                    </tr>
                </thead>

                <tbody id="frameTable">
                    @foreach ($order->details as $detail)
                        <tr>

                            @php
                                $path = $detail->product->firstImg->img ;
                            @endphp

                            <td> <img width="70" style="object-fit: contain" src="{{ path("$path") }}" alt="{{ $detail->discription }}" > </td>

                            <td> {{ $detail->discription }} </td>
                            <td>{{ $detail->qnt }}</td>
                            <td>{{ $detail->price }}</td>


                            <td>{{ $detail->qnt * $detail->comissation }}</td>

                            <td>{{ $detail->qnt * $detail->ponus }}</td>


                            <td>{{ $detail->qnt * ($detail->price + $detail->comissation) }} </td>

                            @if (
                                $order->status == 'قيد المراجعة' ||
                                    $order->status == 'محاولة تانية' ||
                                    $order->status == 'قيد الانتظار' ||
                                    $order->status == 'تم المراجعة' ||
                                    $order->status == 'مؤجل تسليمها' ||
                                    $order->status == 'جاري التجهيز للشحن' ||
                                    $order->status == 'جاري التجيهز شحن يدوي' ||
                                    $order->status == 'في انتظار ردك')
                                <td>

                                    @if ($detail->product->show)
                                        <a target="_blank" href="/admin/orders/details/{{ $detail->id }}/edit">
                                            <div data-tippy-content="تعديل المنتج" class="square-btn ltr has-tip"><i
                                                    class="far fa-edit mr-2 icon mr-2  icon fa-fw"></i></div>
                                        </a>
                                    @endif




                                    <div type="button" data-id="{{ $detail->id }}"
                                        data-name="{{ $detail->discription }}" onclick="show_delete_model(this)"
                                        data-bs-toggle="modal" data-bs-target="#exampleModal" data-tippy-content="حذف"
                                        class="square-btn ltr has-tip"><i class="far fa-trash-alt mr-2 icon fa-fw"
                                            aria-hidden="true"></i>
                                    </div>

                                </td>
                            @endif



                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>


        @if (
            $order->status == 'قيد المراجعة' ||
                $order->status == 'قيد الانتظار' ||
                $order->status == 'تم المراجعة'  ||
                $order->status == 'محاولة تانية'  ||
                $order->status == 'جاري التجهيز للشحن'

        )
            <div class="mt-3">
                <x-admin.forms.buttonLink title="اضافة منتج" target="_blank"
                    path="/admin/toUser/products?add_new_product={{ $order->id }}"></x-admin.forms.buttonLink>

            </div>
        @endif



        <div class="order_title mt-4"> اﻻجمالى</div>

        @php
            $data = getOrderData($order->details);
        @endphp

        <p class="total"> الاوردر : <span>{{ $data['total'] }}</span> </p>
        <div class="total"> مصاريف الشحن : <span> {{ $order->delivery_price }} </span> </div>
        <div class="total"> اجمالي الاوردر : <span>{{ $data['total'] + $order->delivery_price }}</span> </div>


    </div>


    <div class="col-lg-4 mt-2 ">
        <div class="panel">
            <div class="panelTitle">ملاحظات الاوردر</div>

            <div class="panelContent">

                <form class="col-12 " title="اضف ملاحظة" method="post" action="/admin/orders/notes">


                    @csrf

                    <div title="ملاحظات" class=" col-12 my-2">


                        <label for="notes" class="mb-2"> اضف ملاحظة </label>

                        <input type="hidden" value="{{ $order->id }}" name="order_id">

                        <textarea required name="notes" id="notes" rows="2"></textarea>


                    </div>
                    <div style="text-align: left">
                        <button class="mainBtn">اضافة</button>
                    </div>
                </form>

                <div class="orderNotes mt-3">

                    @foreach ($notes as $note)
                        <div class="note">
                            <div class="user d-flex"> {{ $note->user->name }} <div>
                                    <span>{{ timeFormat($note->created_at) }}</span>
                                </div>
                            </div>

                            <div class="message"> {{ $note->message }} </div>

                            <div class="noteActions">
                                @if (CanAccessComment($note->user_id))
                                    <div class="note_edit" data-id="{{ $note->id }}"
                                        data-name="{{ $note->message }}" onclick="show_edit_model(this)"
                                        data-bs-toggle="modal" data-bs-target="#exampleModal2"
                                        data-tippy-content="تعديل الملاحظة " class="square-btn ltr has-tip"><i
                                            class="far fa-edit mr-2 icon fa-fw"></i>
                                    </div>

                                    <div class="note_delete" onclick="deleteComment('{{ $note->id }}')"
                                        data-tippy-content="حذف" class="square-btn ltr has-tip"><i
                                            class="far fa-trash-alt mr-2 icon fa-fw"></i>
                                    </div>
                                @endif
                            </div>

                        </div>
                    @endforeach



                </div>

            </div>
        </div>
    </div>

</div>


<form method="post" action="{{ url('admin/orders/notes/update') }}" class="modal fade" id="exampleModal2"
    tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @csrf
    @method('put')
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">تعديل الملاحظة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="note_id">
                <input type="text" name="message" class="w-100" id="message">
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">حفط التغيرات</button>

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</form>
