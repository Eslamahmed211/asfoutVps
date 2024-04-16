@extends('admin/layout')

@php
    if (auth()->user()->role == 'user' || auth()->user()->role == 'moderator') {
        $type = 'users';
    } elseif (auth()->user()->role == 'trader') {
        $type = 'trader';
    }

@endphp

@section('path')
    <ul class="paths">
        <li> <a href="/profile"> اعدادات الحساب / </a> </li>
        <li> <a href="/profile?tab=nav-payment"> طرق السحب / </a> </li>
        <li class="active">{{ $paymentMethod->title }}</li>
    </ul>
@endsection






@section('content')
    <div class=" mt-lg-4 mx-3  position-relative ">


        <form method="post" action="/{{ $type }}/payment-methods/{{ $paymentMethod->id }}" id="theForm"
            class="row bg-white  py-4 px-2 rounded">

            @csrf
            @method('put')

            <div data-title="العنوان" class="col-lg-6 col-12">
                <x-admin.forms.input value="{{ $paymentMethod->title }}" class="checkThis" for="title"
                    lable_title="العنوان" name="title" placeholder=" مثال رقمي ال010  او حسابي البنكي">
                </x-admin.forms.input>
            </div>

            @php
                $options = json_decode($paymentMethod->options);
            @endphp


            <div data-title="  نوع المحفظة " class="col-lg-6 col-12 mb-3 way cash ">


                <label for="type" class="mb-2">نوع المحفظة </label>


                <select name="cash_wallet_type">

                    <option @selected($options->cash_wallet_type == 'vodafone') value="vodafone">فودافون كاش</option>
                    <option @selected($options->cash_wallet_type == 'orange') value="orange">اورنج كاش</option>
                    <option @selected($options->cash_wallet_type == 'etisalat') value="etisalat">اتصالات كاش</option>

                </select>


            </div>

            <div data-title="  رقم المحفظة " class="col-lg-6 col-12  way  cash">
                <x-admin.forms.input value="{{ $options->mobile }}" class="checkThis" for="mobile"
                    lable_title=" رقم المحفظة " name="mobile" placeholder=" رقم المحفظة ">
                </x-admin.forms.input>
            </div>

            <div data-title="  رقم المحفظة " class="col-lg-6 col-12 mb-3 way cash ">
                <x-admin.forms.input value="{{ $options->mobile_confirmation }}" class="checkThis" for="mobile_confirmation"
                    lable_title=" تاكيد رقم المحفظة " name="mobile_confirmation" placeholder=" تاكيد رقم المحفظة ">
                </x-admin.forms.input>
            </div>







            <div class="col-12">
                <x-admin.forms.mainBtn onclick="validate()" icon="update" title="تعديل" class="mt-3">
                </x-admin.forms.mainBtn>
            </div>




        </form>
    </div>
@endsection


@section('js')
    <script>
        $('#aside .settings').addClass('active');
        tippy('[data-tippy-content]');
    </script>
@endsection
