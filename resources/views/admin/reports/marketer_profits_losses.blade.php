@extends('admin.layout')


@include('admin.reports.style')

@section('content')
    <x-admin.report action="marketer_profits_losses_post"
        title="إختر المسوق المراد عرض تحليل دخل السيستم من هذا المسوق ثم قم بتحميل ملف الاكسيل">


        <select name="user_id" class="js-example-tags w-100">
            <option value> كل المسوقين</option>
        </select>

        <x-admin.forms.input dir="ltr" class="date" for="date" type="date" lable_title="تاريخ محدد" name="date">
        </x-admin.forms.input>


    </x-admin.report>
@endsection


@section('js')
    <script>
        $('aside .reports').addClass('active');

        $(".js-example-tags").select2({
            tags: true,
        });

        flatpickr('.date', {
            enableTime: false,
            mode: "range",

            dateFormat: "Y-m-d"
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
                url: '/admin/users/searchAjax',
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
@endsection
