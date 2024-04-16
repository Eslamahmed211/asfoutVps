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
        <li class="active">اضافة</li>
    </ul>
@endsection


@section('css')
    <style>
        .way {
            display: none;
        }

        #loader {
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            z-index: 9;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            display: none;
        }

        #loader {
            font-size: 40px;
            color: var(--mainColor);
        }
    </style>
@endsection



@section('content')
    <div class=" mt-lg-4 mx-3  position-relative ">

        <div id="loader">
            <i class="fa-solid fa-spinner fa-spin"></i>
        </div>

        <div class="row bg-white  py-4 px-2 rounded">



            @csrf

            <div data-title="العنوان" class="col-lg-6 col-12">
                <x-admin.forms.input class="checkThis" for="title" lable_title="العنوان" name="title"
                    placeholder=" مثال رقمي ال010  او حسابي البنكي">
                </x-admin.forms.input>
            </div>


            <div data-title=" طريقة السحب" class="col-lg-6 col-12 mb-3">


                <label for="type" class="mb-2"> طريقة السحب </label>


                <select onchange="change(this)" name="type">
                    <option value="" selected disabled>اختار طريقة السحب</option>
                    <option @selected(old('type') == 'cash') value="cash">محفظة كاش</option>
                    <option @selected(old('type') == 'bank') value="bank">حساب بنكي</option>

                </select>


            </div>



            <div data-title="  نوع المحفظة " class="col-lg-6 col-12 mb-3 way cash ">


                <label for="type" class="mb-2">نوع المحفظة </label>


                <select name="cash_wallet_type">

                    <option value="vodafone">فودافون كاش</option>
                    <option value="orange">اورنج كاش</option>
                    <option value="etisalat">اتصالات كاش</option>

                </select>


            </div>

            <div data-title="  رقم المحفظة " class="col-lg-6 col-12  way  cash">
                <x-admin.forms.input class="checkThis" for="mobile" lable_title=" رقم المحفظة " name="mobile"
                    placeholder=" رقم المحفظة ">
                </x-admin.forms.input>
            </div>

            <div data-title="  رقم المحفظة " class="col-lg-6 col-12 mb-3 way cash ">
                <x-admin.forms.input class="checkThis" for="mobile_confirmation" lable_title=" تاكيد رقم المحفظة "
                    name="mobile_confirmation" placeholder=" تاكيد رقم المحفظة ">
                </x-admin.forms.input>
            </div>



            <div data-title="الاسم" class="col-lg-6 col-12  way  bank">
                <x-admin.forms.input for="bank" lable_title="اسمك كامل" name="name" placeholder="اسمك كامل">
                </x-admin.forms.input>
            </div>

            <div data-title=" اسم البنك" class="col-lg-6 col-12  way  bank">
                <x-admin.forms.input for="bank_name" lable_title=" اسم البنك" name="bank_name" placeholder=" اسم البنك">
                </x-admin.forms.input>
            </div>

            <div data-title="رقم الحساب" class="col-lg-6 col-12  way  bank">
                <x-admin.forms.input for="bank_account_id" lable_title="رقم الحساب" name="bank_account_id"
                    placeholder="رقم الحساب">
                </x-admin.forms.input>
            </div>

            <div data-title="عنوان الفرع" class="col-lg-6 col-12  way  bank">
                <x-admin.forms.input for="bank_branch_number" lable_title="عنوان الفرع" name="bank_branch_number"
                    placeholder="عنوان الفرع">
                </x-admin.forms.input>
            </div>




            <div class="col-12">
                <x-admin.forms.mainBtn onclick="store()" icon="plus" title="اضافة" class="mt-3">
                </x-admin.forms.mainBtn>
            </div>




        </div>
    </div>

    <div class="mt-3">
        <x-admin.forms.buttonLink path="/{{$type}}" title="رجوع" icon="back"></x-admin.forms.buttonLink>
    </div>
@endsection


@section('js')
    <script>
        $('#aside .settings').addClass('active');
        tippy('[data-tippy-content]');

        function change(e) {

            if (e.value == "cash") {
                $(".bank").removeClass("d-block")
                $(".bank").addClass("d-none")
                $(".cash").removeClass("d-none")
                $(".cash").addClass("d-block")
            } else {
                $(".cash").removeClass("d-block")
                $(".cash").addClass("d-none")
                $(".bank").removeClass("d-none")
                $(".bank").addClass("d-block")
            }


        }

        function store() {

            let data = {
                "title": $("input[name=title]").val(),
                "type": $("select[name=type]").val()
            }

            if (data["type"] == "cash") {
                data.cash_wallet_type = $("select[name=cash_wallet_type]").val();
                data.mobile = $("input[name=mobile]").val();
                data.mobile_confirmation = $("input[name=mobile_confirmation]").val();
            }

            if (data["type"] == "bank") {
                data.name = $("input[name=name]").val();
                data.bank_name = $("input[name=bank_name]").val();
                data.bank_account_id = $("input[name=bank_account_id]").val();
                data.bank_branch_number = $("input[name=bank_branch_number]").val();
            }



            $.ajax({
                url: '/{{$type}}/payment-methods',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    data: data
                },
                beforeSend() {
                    $("#loader").addClass("d-flex");
                    $("#loader").removeClass("d-none");
                },
                success: function(response) {

                    $("#loader").addClass("d-none");
                    $("#loader").removeClass("d-flex");


                    if (response.status == "success") {

                        Swal.fire({
                            title: "نجاح",
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'فهمت'
                        }).then(function() {
                            window.location.href = '/profile?tab=nav-payment';

                        });


                    } else if (response.status == "ValidationError") {

                        Swal.fire({
                            title: 'خطا!',
                            text: Object.values(response.Errors)[0][0],
                            icon: 'error',
                            confirmButtonText: 'فهمت'
                        })

                    } else if (response.status == "error") {
                        Swal.fire({
                            title: 'خطا!',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'فهمت'
                        })
                    }

                },
                error: function() {
                    $("#loader").addClass("d-none");
                    $("#loader").removeClass("d-flex");

                    Swal.fire({
                        title: 'خطا!',
                        text: "خطأ",
                        icon: 'error',
                        confirmButtonText: 'فهمت'
                    })
                }
            });



        }
    </script>
@endsection
