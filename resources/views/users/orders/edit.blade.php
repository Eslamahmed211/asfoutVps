@extends('admin.layout')




@section('path')
    <ul class="paths">
        <li> <a href="{{ url('users/home') }}"> الرئيسية / </a> </li>
        <li> <a href="{{ url('users/orders') }}"> الطلبات / </a> </li>
        <li class="active"> {{ $order->clientName }}</li>
    </ul>
@endsection



@section('content')
    <div class=" mt-lg-4 mx-3  mb-4  ">
        <form class="row bg-white  py-4 px-2 rounded" action="/users/orders/{{ $order->id }}" method="post" id="theForm"
            autocomplete="off">

            @csrf
            @method('put')

            <div class="col-lg-4 col-12" title="اسم العميل">
                <x-admin.forms.input value="{{ $order->clientName }}" class="checkThis" for="clientName"
                    lable_title="اسم العميل ثنائي" name="clientName" placeholder="اسم العميل ثنائي">
                </x-admin.forms.input>
            </div>

            <div class="col-lg-4 col-12" title="رقم العميل">
                <x-admin.forms.input value="{{ $order->clientPhone }}" class="checkThis" for="clientPhone" type="tel"
                    lable_title="رقم العميل " name="clientPhone" placeholder="رقم العميل ">
                </x-admin.forms.input>
            </div>



            <div class="col-lg-4 col-12" title="رقم  اخر للعميل ">
                <x-admin.forms.input value="{{ $order->clientPhone2 }}" notRequired for="clientPhone2" type="tel"
                    lable_title="رقم  اخر للعميل " name="clientPhone2" placeholder="رقم  اخر للعميل ">
                </x-admin.forms.input>
            </div>


            <div data-title="المحافظة" class="col-lg-6 col-12">


                <label for="city" class="mb-2">المحافظة</label>


                <select name="city" class="js-example-basic-single w-100 ">


                    @foreach ($cities as $city)
                        <option @if ($order->city == $city->name) selected @endif value="{{ $city->id }}">
                            {{ $city->name }} ( {{ $city->delivery_price }} )
                        </option>
                    @endforeach

                </select>


            </div>



            <div title="العنوان" class=" col-12 mt-3">

                <label for="address" class="mb-2"> العنوان </label>

                <textarea @class(['checkThis', 'invalid' => $errors->has('address')]) name="address" id="address" rows="2">{{ $order->address }}</textarea>

                @error('address')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>



            <div title="ملاحظات" class=" col-12 my-2">


                <label for="notes" class="mb-2">ملاحظات لخدمة العملاء <span
                        style="font-weight: bold ; font-size: 12px ;color:#aaa">( اختياري)</span>
                </label>

                <textarea name="notes" id="notes" rows="2" oninput="enforceMaxLength(this, 150)">{{ $order->notes }}</textarea>


            </div>

            <div title="محلاظات الشحن" class=" col-12 my-2">


                <label for="notesBosta" class="mb-2">ملاحظات لشركة الشحن <span
                        style="font-weight: bold ; font-size: 12px ;color:#aaa">( اختياري)</span> </label>

                <textarea name="notesBosta" id="notesBosta" rows="2" oninput="enforceMaxLength(this, 150)">{{ $order->notesBosta }}</textarea>


            </div>

            <div data-title="اتمام الاوردر" class="my-3">


                <x-admin.forms.mainBtn type="button" onclick="validate()" title="حفظ"></x-admin.forms.mainBtn>


            </div>


        </form>

        <div class="mt-3">
            <x-admin.forms.buttonLink path="/users/orders" title="رجوع" icon="back"></x-admin.forms.buttonLink>
        </div>

    </div>
@endsection


@section('js')
    <script>
        $('aside .orders').addClass('active');
        $('.js-example-basic-single').select2();

        function enforceMaxLength(textarea, maxLength) {
            if (textarea.value.length > maxLength) {
                textarea.value = textarea.value.slice(0, maxLength);
            }
            document.getElementById("charCount").textContent = textarea.value.length + '/' + maxLength + ' characters';
        }
    </script>
@endsection
