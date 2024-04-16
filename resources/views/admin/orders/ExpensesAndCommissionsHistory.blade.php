@extends('admin.layout')



@section('content')
    <x-searchForm action="{{ url('admin/orders/ExpensesAndCommissionsHistory') }}">

        @php
            !empty($_GET['user_id']) ? ($user_id = $_GET['user_id']) : ($user_id = '');
            !empty($_GET['date']) ? ($date = $_GET['date']) : ($date = '');
            !empty($_GET['type']) ? ($type = $_GET['type']) : ($type = '');
        @endphp

        <div data-title="المسوق">

            <label>اختار المستخدم</label>

            <select name="user_id" class="js-example-tags w-100">
                <option value> كل المستخدمين</option>

                @if (isset($user))
                    <option selected value="{{ $user->id }}"> {{ $user->name }}</option>
                @endif

            </select>

        </div>

        <label> خصم ام اضافة عمولة</label>


        <select name="type">
            <option value="">كل الحالات</option>
            <option @selected($type == 'خصم') value="خصم">خصم </option>
            <option @selected($type == 'اضافة') value="اضافة">اضافة </option>
        </select>

        <label>تاريخ محدد</label>


        <div class="mb-3" dir="ltr">
            <input name="date" class="date" type="text" value="{{ $date }}" placeholder="اختار التاريخ">
        </div>

    </x-searchForm>


    <div class="actions">
        <div class="contnet-title"> اضافة وخصم عمولات </div>

        <div class="d-flex align-items-center gap-2">


            <x-searchBtn></x-searchBtn>

            <x-admin.forms.mainBtn onclick="take_get()" title="خصم واضافة"></x-admin.forms.mainBtn>


        </div>
    </div>











    <div class=" py-l-4 py-3 px-2 rounded position-relative " style="min-height: 60vh; overflow-x: scroll">

        <div class="frameHead mb-3">

            <div class="group">
                <div> اجمالي الاضافات :</div>
                <div class="value" id="reference">{{ $plus }}</div>
            </div>
            <div class="group">
                <div> اجمالي الخصومات :</div>
                <div class="value" id="reference">{{ $minus }}</div>
            </div>

        </div>



        <table class="not ">
            <thead>
                <tr>


                    <th>#</th>
                    <th>التاجر</th>
                    <th>المبلغ</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th>السبب</th>

                </tr>
            </thead>
            <tbody>

                @forelse ($all as $i)
                    <tr>
                        <td> {{ $loop->index + 1 }} </td>

                        <td>{{ $i->user->name }}</td>
                        <td>{{ $i->commission }}</td>
                        <td>{{ $i->type }}</td>
                        <td>{{ fixdata($i->created_at) }}</td>
                        <td>{{ $i->message }}</td>
                    </tr>
                @empty

                    <tr>
                        <td colspan="6">لا يوجد اي بيانات</td>
                    </tr>
                @endforelse


            </tbody>

        </table>
    </div>
@endsection


@section('js')
    <script>
        $('aside .orders').addClass('active');
        tippy('[data-tippy-content]');

        flatpickr('input.date', {
            enableTime: false,
            mode: "range",

            dateFormat: "Y-m-d"
        });
    </script>

    <script>
        $(".js-example-tags").select2({
            tags: true,
        });

        $('.js-example-tags').on('select2:select', function(e) {


            if (e.params.data.id == "") {
                $('.js-example-tags').html('');

            }



            if (e.params.data.element || e.params.data.id.length < 4) {
                return;
            }


            var data = e.params.data;

            $.ajax({
                url: '/admin/users/searchAjax?all=yes',
                type: 'GET',
                dataType: 'json',
                data: {
                    query: data.id
                },
                success: function(response) {
                    if (response.status == "success") {


                        let cartona = ``;


                        for (const user of response.users) {
                            cartona +=
                                `<option value="${user.id}">${user.name}</option>`

                        }

                        $('.js-example-tags').html(cartona);

                    } else {

                        $('.js-example-tags').html('');


                    }
                }
            });

        });
    </script>

    <script>
        const myComponent = {
            template: `<form method="post" action="/admin/get_take_users">
                    @csrf

                    <div class="w-100 mb-2">

                                    <label for="amount" class="mb-2">المسوق</label>


                                    <div data-title="المسوق" class="col-12">


                        <select name="user_id" class="js-example-tags w-100">
                            <option value> كل المستخدمين</option>

                            @if (isset($user))
                                <option selected value="{{ $user->id }}"> {{ $user->name }}</option>
                            @endif

                        </select>


                    </div>



                        <x-admin.forms.input for="amount" class='mt-2' type="number" lable_title="المبلغ" placeholder=" 100   -50 " name="amount">
                        </x-admin.forms.input>

                        <label for="amount" class="mb-2">البيان</label>


                        <textarea name="message"  rows="2"></textarea>
                    </div>

                    <x-admin.forms.mainBtn type="submit" title="حفظ"></x-admin.forms.mainBtn>
                </form>`
        }

        function take_get() {



            Swal.fire({
                title: "اضافة وخصم",

                html: myComponent.template,
                didOpen: () => {
                    $('.js-example-basic-single2').select2();
                    $(".js-example-tags").select2({
                        tags: true,
                    });

                    $('.js-example-tags').on('select2:select', function(e) {


                        if (e.params.data.id == "") {
                            $('.js-example-tags').html('');

                        }



                        if (e.params.data.element || e.params.data.id.length < 4) {
                            return;
                        }


                        var data = e.params.data;

                        $.ajax({
                            url: '/admin/users/searchAjax?all=yes',
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                query: data.id
                            },
                            success: function(response) {
                                if (response.status == "success") {


                                    let cartona = ``;


                                    for (const user of response.users) {
                                        cartona +=
                                            `<option value="${user.id}">${user.name}</option>`

                                    }

                                    $('.js-example-tags').html(cartona);

                                } else {

                                    $('.js-example-tags').html('');


                                }
                            }
                        });

                    });
                },

                focusConfirm: false,
                showConfirmButton: false,
                inputAutoFocus: false
            })
        }
    </script>
@endsection
