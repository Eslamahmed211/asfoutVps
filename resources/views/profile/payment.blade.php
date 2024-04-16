@php
    if (auth()->user()->role == 'user' || auth()->user()->role == 'moderator') {
        $type = 'users';
    } elseif (auth()->user()->role == 'trader') {
        $type = 'trader';
    }

@endphp



<div class="actions mt-0 border-0">


    <div class="contnet-title">طرق السحب</div>

    <div class="d-flex align-items-center gap-2">


        <x-link path="/{{ $type }}/payment-methods/create" title="اضافة طريقة سحب"><svg width="22"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
            </svg></x-link>


    </div>




</div>


<div class="tableSpace">

    <table style="background: #f8f8f8;">
        <thead>

            <tr>
                <th> العنوان </th>
                <th>النوع</th>
                <th> الإجراءات </th>

            </tr>

        </thead>
        <tbody>


            @forelse ($paymentMethods as $paymentMethod)
                <td data-text='العنوان'> {{ $paymentMethod->title }}</td>

                <td data-text='العنوان'> {{ $paymentMethod->type == 'cash' ? 'حساب كاش' : 'حساب بنكي' }}</td>

                <td data-text="الإجراءات">
                    <div>


                        <div onclick='window.location.href = "/{{ $type }}/payment-methods/{{ $paymentMethod->id }}/edit"'
                            id="myButton" data-tippy-content="تعديل" class="square-btn ltr has-tip"><i
                                class="far fa-edit mr-2  icon" aria-hidden="true"></i>
                        </div>


                        <div type="button" data-id="{{ $paymentMethod->id }}" data-name="{{ $paymentMethod->title }}"
                            onclick="show_delete_model(this)" data-bs-toggle="modal" data-bs-target="#exampleModal"
                            data-tippy-content="حذف" class="square-btn ltr has-tip"><i
                                class="far fa-trash-alt mr-2 icon" aria-hidden="true"></i></div>
                    </div>

                </td>


                </tr>

            @empty
                <tr>

                    <td colspan="3">لا يوجد طرق سحب مضافة</td>
                </tr>
            @endforelse

        </tbody>


    </table>




    <x-admin.forms.deleteModel type="{{ $type }}" model="payment-methods"
        id="payment_id"></x-admin.forms.deleteModel>



</div>
