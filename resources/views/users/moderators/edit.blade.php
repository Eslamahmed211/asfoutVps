@extends('admin/layout')


@section('path')
    <ul class="paths">
        <li> <a href="{{ url('users/moderators') }}"> الموديتور / </a> </li>
        <li class="active"> {{ $moderator->name }} / </li>
        <li class="active"> تعديل </li>
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
    <div class=" mt-lg-4 mt-3 tableSpace">


        <form class="row bg-white  py-4 px-2 rounded" action="/users/moderators/{{ $moderator->id }}" method="post"
            id="theForm" autocomplete="off">

            @csrf
            @method('put')

            <div class="col-lg-4 col-12" title="اسم الموديتور">
                <x-admin.forms.input value="{{ $moderator->name }}" class="checkThis" for="name"
                    lable_title="اسم الموديتور" name="name" placeholder="اسم الموديتور">
                </x-admin.forms.input>
            </div>


            <div class="col-lg-4 col-12" title="كلمة السر">
                <x-admin.forms.input notRequired type="password" for="password" lable_title="كلمة السر" name="password"
                    placeholder="كلمة السر">
                </x-admin.forms.input>
            </div>

            <div class="col-lg-4 col-12" title="تاكيد كلمة السر">
                <x-admin.forms.input notRequired type="password" for="password_confirmation" lable_title="تأكيد كلمة السر"
                    name="password_confirmation" placeholder="تأكيد كلمة السر">
                </x-admin.forms.input>
            </div>

            <div class="col-lg-4 col-12" title="رقم التليفون">
                <x-admin.forms.input value="{{ $moderator->mobile }}" class="checkThis" type="tel" for="mobile"
                    lable_title="رقم التليفون" name="mobile" placeholder="رقم التليفون">
                </x-admin.forms.input>
            </div>


            <div class="col-lg-4 col-12" title="العنوان">
                <x-admin.forms.input value="{{ $moderator->address }}" class="checkThis" type="text" for="address"
                    lable_title="العنوان" name="address" placeholder="العنوان">
                </x-admin.forms.input>
            </div>


            <div class="col-lg-4 col-12" title="المحافظة">
                <x-admin.forms.input value="{{ $moderator->city }}" class="checkThis" type="text" for="city"
                    lable_title="المحافظة" name="city" placeholder="المحافظة">
                </x-admin.forms.input>
            </div>


            <div class="col-lg-4 col-12" title="حالة الحساب">
                <label class="mb-2" for="active">حالة الحساب</label>
                <select name="active" class="checkThis">
                    <option @if ($moderator->active == 1) selected @endif value="1">نشط</option>
                    <option @if ($moderator->active == 0) selected @endif value="0">غير نشط</option>
                </select>
            </div>


            <div class="col-lg-4 col-12" title="نوع العمولة">
                <label class="mb-2" for="commissionType">نوع العمولة</label>
                <select name="commissionType" class="checkThis">
                    <option @selected($moderator->moderatorOptions?->commissionType == 'null') value="null">بدون</option>
                    <option @selected($moderator->moderatorOptions?->commissionType == 'orderTotal') value="orderTotal">عمولة علي اجمالي اوردر</option>
                    <option @selected($moderator->moderatorOptions?->commissionType == 'qnt') value="qnt">عمولة علي كل قطعة في الاوردر</option>
                    <option @selected($moderator->moderatorOptions?->commissionType == 'orderTotalPercent') value="orderTotalPercent">نسبة مئوية من اجمالي الاوردر</option>
                </select>
            </div>


            <div class="col-lg-4 col-12" data-title="العمولة او النسبة">
                <x-admin.forms.input class="checkThis" type="number" for="commission" lable_title="العمولة او النسبة"
                    value="{{ $moderator->moderatorOptions?->commission }}" name="commission"
                    placeholder="العمولة او النسبة">

                    <x-slot:info><i aria-hidden="true"
                            data-tippy-content='اكتب رقما بناءا علي نوع العمولة  المختارة مسبقا وليكن 10 اذا اخترت نسبة سيتم خصم 10% واذا اخترت عمولة سيتم خصم 10 جنية '
                            style="color: #aaa;" class="fas fa-question-circle"></i></x-slot:info>

                </x-admin.forms.input>
            </div>




            <div class="col-12">
                <x-admin.forms.mainBtn type="submit" title="تعديل {{ $moderator->name }}"
                    class="mt-3"></x-admin.forms.mainBtn>
            </div>




        </form>

    </div>

    <div class="mt-3">
      <x-admin.forms.buttonLink path="/users/moderators" title="رجوع" icon="back"></x-admin.forms.buttonLink>
  </div>

@endsection


@section('js')
    <script>
        $('#aside .moderators').addClass('active');
        tippy('[data-tippy-content]');
    </script>
@endsection
