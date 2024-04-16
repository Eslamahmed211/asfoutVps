@extends('admin.layout')

@section('css')
    <style>
        .select2-container {

            width: 100% !important;
        }

        .select2-container .select2-selection--single,
        select {

            height: 40px;

        }
    </style>
@endsection

@section('path')
    <ul class="paths">
        <li> <a href="{{ url('admin/products') }}"> المنتجات / </a> </li>
        <li class="active"> {{ $product->name }} /</li>
        <li class="active">تخصيص </li>
    </ul>
@endsection

@section('content')
    <div class=" mt-lg-4  ">

        <form class="row py-3 rounded bg-white mx-2" action="{{ url("admin/products/$product->id/optimize") }}" method="post"
            id="theForm" autocomplete="off">
            @csrf


            <div data-title="المسوق" class="col-lg-6 col-12 mb-4 ">

                <label class="mb-3">المسوق</label>


                <select name="user_id" class="js-example-tags w-100">

                </select>


            </div>

            <div data-title="الاجراء" class="col-lg-6 col-12 mb-4 ">

                <label class="mb-3">الاجراء</label>

                <select class="w-100" name="action">
                    <option value="0">اخفاء</option>
                    <option value="1">ظهور</option>
                </select>


            </div>

            <div class="col-12">
                <x-admin.forms.mainBtn onclick="validate()" icon="plus" title="اضافة" class="mt-3">
                </x-admin.forms.mainBtn>
            </div>



        </form>


        <div class="mt-4  mx-1 tableSpace p-0">



            <table class="not">

                <thead>
                    <th>المستخدم</th>
                    <th>نوع الاجراء</th>
                    <th>ازالة</th>
                </thead>

                <tbody>


                    @forelse ($product->optimizes as $optimize)
                        <tr>

                            <td>{{ $optimize->user->name ?? '' }} <br> {{ $optimize->user->mobile ?? '' }}</td>

                            <td>{{ $optimize->action == 0 ? 'اخفاء' : 'ظهور' }}</td>

                            <td>


                                <div type="button" data-id="{{ $optimize->id }}"
                                    data-name="{{ $optimize->user->name ?? '' }}" onclick="show_delete_model(this)"
                                    data-bs-toggle="modal" data-bs-target="#exampleModal" data-tippy-content="حذف"
                                    class="square-btn ltr has-tip"><i class="far fa-trash-alt mr-2 icon"
                                        aria-hidden="true"></i></div>
                            </td>
                        </tr>

                    @empty
                        <tr>

                            <td colspan="3">لا يوجد تخصيص لهذا المنتج</td>
                        </tr>
                    @endforelse

                </tbody>


            </table>



            <x-admin.forms.deleteModel model="products/optimize" id="optimize_id"></x-admin.forms.deleteModel>



        </div>
    </div>
@endsection


@section('js')
    <script>
        $('aside .products').addClass('active');
        tippy('[data-tippy-content]');

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
                            cartona += `<option value="${user.id}">${user.name}</option>`

                        }


                        $('.js-example-tags').html(cartona);



                    } else {

                        $('.js-example-tags').html('');


                    }
                }
            });

        });

        function show_delete_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $('#model_title').text(data_name);

            $("input[name='optimize_id']").val(data_id)

        }
    </script>
@endsection
