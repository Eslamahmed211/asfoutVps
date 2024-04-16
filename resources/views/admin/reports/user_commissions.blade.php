@extends('admin.layout')


@include('admin.reports.style')

@section('content')
    <x-admin.report action="user_commissions_post" title="إختر المستخدم المراد تحميل سجل تفصيلي لعمولاته ثم قم بتحميل ملف الاكسيل">
        <select name="user_id" class="js-example-tags w-100">
            <option value> كل المستخدمين</option>
        </select>
    </x-admin.report>
@endsection


@section('js')
    <script>
        $('aside .reports').addClass('active');

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
@endsection
