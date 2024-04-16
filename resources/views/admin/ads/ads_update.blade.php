@extends('admin.layout')

@section('path')
    <ul class="paths">
        <li> <a href="{{ url('admin/ads') }}"> البنرات الاعلانية / </a> </li>
        <li class="active"> {{ $ads->alt }}</li>
    </ul>
@endsection


@section('content')
    <div class=" mt-lg-4  bg-white  p-3 rounded  ">

        @php
            $img = str_replace('public', 'storage', $ads->img);
        @endphp

            <img style="width: 100% ; height: 250px ; border-radius: 2px ; aspect-ratio : 16/9 " src="{{ asset("$img") }}">


        <form class="row mt-4 " action="{{ url('admin/ads/update') }}" enctype="multipart/form-data" method="post"
            id="theForm" autocomplete="off">

            @csrf
            <input value="{{ $ads->id }}" type="hidden" name="id">


            <div class="col-lg-5 col-12">
                <x-admin.forms.input value="{{ $ads->link }}" notRequired for="link" lable_title="اللينك"
                    name="link" placeholder="اللينك">
                </x-admin.forms.input>
            </div>


            <div class="col-lg-5 col-12">
                <x-admin.forms.input value="{{ $ads->alt }}" class="checkThis" for="alt"
                    lable_title="عنوان البنر يظهر في جوجل" name="alt" placeholder="عنوان البنر يظهر في جوجل">
                </x-admin.forms.input>
            </div>

            <div class="col-lg-2  col-12">
                <div>
                    <label for="show" class="mb-2">الحالة</label>
                </div>
                <select name="show">
                    <option @if ($ads->show == 'show') selected @endif value="show">ظهور</option>
                    <option @if ($ads->show == 'hidden') selected @endif value="hidden">اخفاء</option>
                </select>
            </div>


            <div class="col-12">
                <div class="contnet-title">البنر الاعلاني</div>
                <input type="file" name="img" accept="image/*">
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
        $('#aside .ads').addClass('active');
        tippy('[data-tippy-content]');
    </script>
@endsection
