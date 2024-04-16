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

        form  .role {
            display: flex;
            align-items: center;
            column-gap: 20px;
            border-bottom: 1px solid #dddddd;
            padding: 10px;

        }

        .action2,
        .master {
            display: flex;
            align-items: center;
            column-gap: 10px;

        }

        .master label {
            margin: 0px;
            font-size: 15px;
            font-weight: 700;
        }


        .role label {
            margin: 0px;
            font-size: 15px;
        }
    </style>
@endsection



@section('path')
    <ul class="paths">
        <li> <a href="{{ url('admin/users') }}"> المستخدمين / </a> </li>
        <li class="active">اضافة </li>
    </ul>
@endsection


@section('content')
    <div class=" mt-lg-4 mx-3  ">

        <form class="row bg-white  py-4 px-2 rounded" action="/admin/users" method="post" id="theForm" autocomplete="off">

            @csrf

            <div class="col-lg-4 col-12" title="اسم المستخدم">
                <x-admin.forms.input class="checkThis" for="name" lable_title="اسم المستخدم" name="name"
                    placeholder="اسم المستخدم">
                </x-admin.forms.input>
            </div>

            <div class="col-lg-4 col-12" title="ايميل المستخدم">
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


            <div class="col-lg-4 col-12 mb-3" title="نوع الحساب">
                <label class="mb-2" for="role">نوع الحساب</label>
                <select name="role" onchange="check(this)" class="checkThis">
                    <option selected value="user">مسوق</option>
                    <option value="admin">ادمن</option>
                    <option value="trader">تاجر</option>
                    {{-- <option value="postman">مندوب شحن</option> --}}
                </select>
            </div>


            <div class="col-lg-4 col-12" title="حالة الحساب">
                <label class="mb-2" for="active">حالة الحساب</label>
                <select name="active" class="checkThis">
                    <option value="1">نشط</option>
                    <option value="0">غير نشط</option>
                    <option value="3">محظور</option>
                </select>
            </div>






            <div id="AdminRoles" class="mt-3" style="display: none">

                <p class="contnet-title d-flex align-items-center ">
                    <label for="all"> الصلاحيات كاملة </label> <input class="mx-2" onchange="allCheckedAdmin(this)"
                        id="all" type="checkbox">
                </p>

                <div class="tree">
                    @foreach (config('adminRoles.permissions') as $role => $actions)
                        <div class="role">
                            <div class="master">
                                <input type="checkbox" id="{{ $role }}" class="master-checkbox">
                                <label for="{{ $role }}">{{ $role }}</label>
                            </div>
                            <div class="actions2 mt-2" style="flex-wrap: wrap">
                                @foreach ($actions as $actionName => $actionKey)
                                    <div class="action2">
                                        <input type="checkbox" id="{{ $actionKey }}" name="permissions[]"
                                            value="{{ $actionKey }}" class="action-checkbox">
                                        <label for="{{ $actionKey }}">{{ $actionName }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>



            <div class="col-12">
                <x-admin.forms.mainBtn onclick="validate()" title="حفظ" class="mt-3">
                </x-admin.forms.mainBtn>
            </div>




        </form>
    </div>
@endsection


@section('js')
    <script>
        $('#aside .users').addClass('active');
        tippy('[data-tippy-content]');




        function allCheckedAdmin(e) {
            $('#AdminRoles input:checkbox').not(e).prop('checked', e.checked);
            $('#SuperRoles input:checkbox').not(e).prop('checked', false);
        }


        function allCheckedSuper(e) {
            $('#SuperRoles input:checkbox').not(e).prop('checked', e.checked);
            $('#AdminRoles input:checkbox').not(e).prop('checked', false);

        }




        function notAll(e) {

            if (!e.checked) {
                $('input#all').not(e).prop('checked', false);
            }

        }



        function check(e) {

            $('input[type=checkbox]').not(e).prop('checked', false);


            if (e.value == "admin") {
                $("#AdminRoles").show();
                $("#SuperRoles").hide();

            } else if (e.value == "super") {
                $("#AdminRoles").hide();
                $("#SuperRoles").show();
            } else {
                $("#AdminRoles").hide();
                $("#SuperRoles").hide();
            }
        }
    </script>
@endsection
