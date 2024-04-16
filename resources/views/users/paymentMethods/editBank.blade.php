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


        <form method="post" action="/{{$type}}/payment-methods/{{ $paymentMethod->id }}" id="theForm"
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


            <div data-title="الاسم" class="col-lg-6 col-12  way  bank">
                <x-admin.forms.input value="{{ $options->name }}" for="bank" lable_title="اسمك كامل" name="name"
                    placeholder="اسمك كامل">
                </x-admin.forms.input>
            </div>

            <div data-title=" اسم البنك" class="col-lg-6 col-12  way  bank">
                <x-admin.forms.input for="bank_name" value="{{ $options->bank_name }}" lable_title=" اسم البنك"
                    name="bank_name" placeholder=" اسم البنك">
                </x-admin.forms.input>
            </div>

            <div data-title="رقم الحساب" class="col-lg-6 col-12  way  bank">
                <x-admin.forms.input value="{{ $options->bank_account_id }}" for="bank_account_id" lable_title="رقم الحساب"
                    name="bank_account_id" placeholder="رقم الحساب">
                </x-admin.forms.input>
            </div>

            <div data-title="عنوان الفرع" class="col-lg-6 col-12  way  bank">
                <x-admin.forms.input value="{{ $options->bank_branch_number }}" for="bank_branch_number"
                    lable_title="عنوان الفرع" name="bank_branch_number" placeholder="عنوان الفرع">
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
