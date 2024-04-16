@extends('admin/layout')


@section('path')
    <ul class="paths">
        <li> <a href="{{ url('admin/products') }}"> المنتجات / </a> </li>
        @php
            $id = $attr->product->id;
        @endphp
        <li> <a href="{{ url("admin/products/$id/variants") }}"> {{ $attr->product->name }} / </a> </li>
        <li> <a href="{{ url('#') }}">{{ $attr->name }}</a> </li>
    </ul>
@endsection

@section('content')

    <div class=" mt-lg-4  ">

        <form class="row py-3 rounded bg-white mx-2" action="{{ url('admin/attribute/addValue') }}" method="post" id="theForm"
            autocomplete="on">

            <input type="hidden" name="attribute_id" value="{{ $attr->id }}">


            @csrf


            <div class="col-12">
                <x-admin.forms.input class="checkThis2" for="value" lable_title="القيمة" name="value"
                    placeholder=" اكتب القيمة مثال (احمر - اخضر - ازرق) ">
                </x-admin.forms.input>
            </div>


            <div class="col-12">
                <x-admin.forms.mainBtn onclick="validate()" icon="plus" title="اضافة" class="mt-3">
                </x-admin.forms.mainBtn>
            </div>




        </form>

        @if (isset($attr) && !empty($attr))
            <div class="mt-4  mx-1 tableSpace p-0">



                <table class="not">

                    <thead>
                        <th>القيمة</th>
                        <th>اجراءات</th>
                    </thead>

                    <tbody>


                        @forelse ($attr->values as $value)
                            <tr>

                                <td id="{{ $value->id }}">{{ $value->value }}</td>

                                <td>

                                    <div type="button" data-id="{{ $value->id }}" data-name="{{ $value->value }}"
                                        onclick="show_new_value_model(this)" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal2" data-tippy-content="تعديل"
                                        class="square-btn ltr has-tip">
                                        <i class="far fa-edit mr-2 icon" aria-hidden="true"></i>
                                    </div>


                                    <div type="button" data-id="{{ $value->id }}" data-name="{{ $value->value }}"
                                        onclick="show_delete_model(this)" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" data-tippy-content="حذف"
                                        class="square-btn ltr has-tip"><i class="far fa-trash-alt mr-2 icon"
                                            aria-hidden="true"></i></div>
                                </td>
                            </tr>

                        @empty
                            <tr>

                                <td colspan="2">لا يوجد قيم متاحة</td>
                            </tr>
                        @endforelse

                    </tbody>


                </table>



                <x-admin.forms.deleteModel model="value" id="value_id"></x-admin.forms.deleteModel>



            </div>
        @endif

        <form method="post" action="{{ url('admin/values/edit') }}" class="modal fade" id="exampleModal2" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">تغير القيمة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="value_id">
                        <input required type="text" name="new_value" class="w-100" id="new_value_input">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">حفط التغيرات</button>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
@endsection





@section('js')
    <script>
        $('#aside .products').addClass('active');
        tippy('[data-tippy-content]');




        function show_new_value_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $("#new_value_input").val(data_name)
            $("input[name='value_id']").val(data_id)
        }

        function show_delete_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $('#model_title').text(data_name);

            $("input[name='value_id']").val(data_id)

        }
    </script>
@endsection
