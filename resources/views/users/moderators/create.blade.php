@extends('admin/layout')



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



@section('path')
    <ul class="paths">
        <li> <a href="{{ url('users/moderators') }}"> الموديتور / </a> </li>
        <li class="active">اضافة </li>
    </ul>
@endsection


@section('content')
    <div class=" mt-lg-4 mx-3  ">



        <form class="row bg-white  py-4 px-2 rounded" action="/users/moderators" method="post" id="theForm"
            autocomplete="off">

            @csrf

            <div class="col-lg-4 col-12" title="اسم الموديتور">
                <x-admin.forms.input class="checkThis" for="name" lable_title="اسم الموديتور" name="name"
                    placeholder="اسم الموديتور">
                </x-admin.forms.input>
            </div>

            <div class="col-lg-4 col-12" title="ايميل الموديتور">
                <x-admin.forms.input class="checkThis" type="email" for="email" lable_title="البريد الالكتروني"
                    name="email" placeholder="البريد الالكتروني">
                </x-admin.forms.input>
            </div>

            <div class="col-lg-4 col-12" title="كلمة السر">
                <x-admin.forms.input class="checkThis" type="password" for="password" lable_title="كلمة السر"
                    name="password" placeholder="كلمة السر">
                </x-admin.forms.input>
            </div>

            <div class="col-lg-4 col-12" title="تاكيد كلمة السر">
                <x-admin.forms.input class="checkThis" type="password" for="password_confirmation"
                    lable_title="تأكيد كلمة السر" name="password_confirmation" placeholder="تأكيد كلمة السر">
                </x-admin.forms.input>
            </div>

            <div class="col-lg-4 col-12" title="رقم التليفون">
                <x-admin.forms.input class="checkThis" type="tel" for="mobile" lable_title="رقم التليفون"
                    name="mobile" placeholder="رقم التليفون">
                </x-admin.forms.input>
            </div>


            <div class="col-lg-4 col-12" title="العنوان">
                <x-admin.forms.input class="checkThis" type="text" for="address" lable_title="العنوان" name="address"
                    placeholder="العنوان">
                </x-admin.forms.input>
            </div>


            <div class="col-lg-4 col-12" title="المحافظة">
                <x-admin.forms.input class="checkThis" type="text" for="city" lable_title="المحافظة" name="city"
                    placeholder="المحافظة">
                </x-admin.forms.input>
            </div>

            <div class="col-lg-4 col-12" title="حالة الحساب">
                <label class="mb-2" for="active">حالة الحساب</label>
                <select name="active" class="checkThis">
                    <option value="1">نشط</option>
                    <option value="0">غير نشط</option>
                </select>
            </div>


            <div class="col-lg-4 col-12" title="نوع العمولة">
                <label class="mb-2" for="commissionType">نوع العمولة</label>
                <select name="commissionType" class="checkThis">
                    <option value="null">بدون</option>
                    <option value="orderTotal">عمولة علي اجمالي اوردر</option>
                    <option value="qnt">عمولة علي كل قطعة في الاوردر</option>
                    <option value="orderTotalPercent">نسبة مئوية من اجمالي الاوردر</option>
                </select>
            </div>


            <div class="col-lg-4 col-12" data-title="العمولة او النسبة">
              <x-admin.forms.input class="checkThis" type="number" for="commission" lable_title="العمولة او النسبة" name="commission"
                  placeholder="العمولة او النسبة">

                  <x-slot:info><i aria-hidden="true"
                  data-tippy-content='اكتب رقما بناءا علي نوع العمولة  المختارة مسبقا وليكن 10 اذا اخترت نسبة سيتم خصم 10% واذا اخترت عمولة سيتم خصم 10 جنية '
                  style="color: #aaa;" class="fas fa-question-circle"></i></x-slot:info>

              </x-admin.forms.input>
          </div>


            


            <div class="col-12">
                <x-admin.forms.mainBtn onclick="validate()" icon="plus" title="اضافة موديتور جديد" class="mt-3">
                </x-admin.forms.mainBtn>
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
