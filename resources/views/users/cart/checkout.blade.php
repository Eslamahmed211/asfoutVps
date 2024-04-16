@extends('admin.layout')

@section('css')
    <style>
        .select2-container {

            width: 100% !important;


        }

        .select2-container .select2-selection--single,
        select {

            height: 40px;

        }

        .select2-container--default .select2-selection--single {
            border-color: #e5e7eb;
            border-radius: 0.375rem;
            font-size: 14px;

            border-width: 0px;

            outline: none;

        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {

            top: 8px;

        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 40px;
            height: 100%;
            --tw-bg-opacity: 1;
            background-color: rgb(243 244 246 / var(--tw-bg-opacity));
            --tw-shadow: 0 1px 3px 0 rgb(0 0 0 / .1), 0 1px 2px -1px rgb(0 0 0 / .1);
            --tw-shadow-colored: 0 1px 3px 0 var(--tw-shadow-color), 0 1px 2px -1px var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);


        }


    </style>
@endsection


@section('path')
    <ul class="paths">
        <li> <a href="{{ url('users/home') }}"> الرئيسية / </a> </li>
        <li> <a href="{{ url('users/cart') }}"> السلة / </a> </li>
        <li class="active"> اتمام الطلب </li>
    </ul>
@endsection



@section('content')
    <div class=" mt-lg-4 mx-3  mb-4  ">
        <form class="row bg-white  py-4 px-2 rounded" action="/users/cart/checkout" method="post" id="theForm"
            autocomplete="off">

            @csrf

            <div class="col-lg-4 col-12" title="اسم العميل">
                <x-admin.forms.input class="checkThis" for="clientName" lable_title="اسم العميل ثنائي" name="clientName"
                    placeholder="اسم العميل ثنائي">
                </x-admin.forms.input>
            </div>

            <div class="col-lg-4 col-12" title="رقم العميل">
                <x-admin.forms.input class="checkThis" for="clientPhone" type="tel" lable_title="رقم العميل "
                    name="clientPhone" placeholder="رقم العميل ">
                </x-admin.forms.input>
            </div>



            <div class="col-lg-4 col-12" title="رقم  اخر للعميل ">
                <x-admin.forms.input notRequired for="clientPhone2" type="tel" lable_title="رقم اخر للعميل "
                    name="clientPhone2" placeholder="رقم اخر للعميل ">
                </x-admin.forms.input>
            </div>


            <div data-title="المحافظة" class="col-lg-4 col-12">


                <label for="city" class="mb-2">المحافظة</label>


                <select name="city" class="js-example-basic-single w-100">


                    @foreach ($cities as $city)
                        <option @if (old('city') == $city->id) selected @endif value="{{ $city->id }}">
                            {{ $city->name }} ( {{ $city->delivery_price }} )
                        </option>
                    @endforeach

                </select>


            </div>


            <div class="col-lg-8 col-12" title="العنوان">
                <x-admin.forms.input @class(['checkThis', 'invalid' => $errors->has('address')]) for="address" lable_title="العنوان" name="address"
                    placeholder="العنوان">
                </x-admin.forms.input>
            </div>

            @error('address')
                <div class="error">{{ $message }}</div>
            @enderror







            <div title="ملاحظات" class=" col-lg-6 col-12 my-2">


                <label for="notes" class="mb-2">ملاحظات لخدمة العملاء <span
                        style="font-weight: bold ; font-size: 12px ;color:#aaa">( اختياري)</span>
                </label>

                <textarea name="notes" id="notes" rows="2" oninput="enforceMaxLength(this, 150)">{{ old('notes') }}</textarea>


            </div>

            <div title="محلاظات الشحن" class="col-lg-6 col-12 my-2">


                <label for="notesBosta" class="mb-2">ملاحظات لشركة الشحن <span
                        style="font-weight: bold ; font-size: 12px ;color:#aaa">( اختياري)</span> </label>

                <textarea name="notesBosta" id="notesBosta" rows="2" oninput="enforceMaxLength(this, 150)">{{ old('notesBosta') }}</textarea>


            </div>

            <div data-title="اتمام الاوردر" class="my-3">


                <x-admin.forms.mainBtn type="button" onclick="validate()" title="تسجيل الاوردر"></x-admin.forms.mainBtn>


            </div>


        </form>

    </div>
@endsection


@section('js')
    <script>
        $('aside .cart').addClass('active');
        $('.js-example-basic-single').select2();

        $('.pagesSelect').select2({
            placeholder: 'اسم البيدج',
            tags: "true",

        });


        function enforceMaxLength(textarea, maxLength) {
            if (textarea.value.length > maxLength) {
                textarea.value = textarea.value.slice(0, maxLength);
            }
            document.getElementById("charCount").textContent = textarea.value.length + '/' + maxLength + ' characters';
        }
    </script>
@endsection
