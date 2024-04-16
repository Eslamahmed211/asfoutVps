@extends('admin/layout')


@section('path')
    <ul class="paths">
        <li> <a href="{{ url('admin/categories') }}"> التصنيقات / </a> </li>
        <li class="active">اضافة </li>
    </ul>
@endsection

@section('css')
    <style>
        .contnet-title {
            font-size: 16px;
            color: rgb(30, 41, 53);
            /* color: red; */
            font-weight: 700;
            width: 100%;
            border-right: solid 4px #ddd;
            background: #f5f5f5;
            padding: 8pt;
            border-radius: 2pt;
            font-family: "cairo";

        }
    </style>
@endsection



@section('content')
    <div class=" mt-lg-4 mx-3  ">

        <form class="row bg-white  py-4 px-2 rounded" action="/admin/categories" method="post" id="theForm" autocomplete="on">

            @csrf

            <div class="col-lg-6 col-12">
                <x-admin.forms.input class="checkThis" for="name" lable_title="العنوان" name="name"
                    placeholder="العنوان ">
                </x-admin.forms.input>
            </div>

            <div class="col-lg-6 col-12">

                <x-admin.forms.input class="checkThis" type="text" for="slug" lable_title="اسم التصنيف في الرابط "
                    name="slug" placeholder="اسم التصنيف في الرابط ">

                    <x-slot:info><i aria-hidden="true"
                            data-tippy-content='تسخدم ككلمة مفتاحية في الرابط علشان تتارشف في محركات البحث'
                            style="color: #aaa;" class="fas fa-question-circle"></i></x-slot:info>

                </x-admin.forms.input>
            </div>






            <div class="col-12">
                <x-admin.forms.mainBtn onclick="validate()" icon="plus" title="اضافة تصنيف جديد" class="mt-3">
                </x-admin.forms.mainBtn>
            </div>




        </form>
    </div>
@endsection


@section('js')
    <script>
        $('#aside .products').addClass('active');
        tippy('[data-tippy-content]');
    </script>
@endsection
