@extends('admin/layout')


@section('path')
    <ul class="paths">
        <li> <a href="{{ url('admin/products') }}"> المنتجات / </a> </li>
        <li class="active"> {{ $product->name }} /</li>
        <li class="active"> سجل التغيرات </li>
    </ul>
@endsection




@section('content')
    <div class=" mt-lg-4 mt-3 tableSpace">

        <div class="contnet-title">سجل التعديلات <div type="button" data-id="{{ $product->id }}"
                onclick="show_delete_model(this)" data-bs-toggle="modal" data-bs-target="#exampleModal"
                data-tippy-content="حذف" class="square-btn ltr has-tip"><i class="far fa-trash-alt mr-2 icon fa-fw"
                    aria-hidden="true"></i>
            </div>
        </div>

        <table class="not">
            <thead>

                <tr>
                    <th>نوع التعديل</th>

                    <th>التعديلات</th>

                    <th>المغير</th>

                    <th>الوقت</th>

                </tr>

            </thead>

            <tbody>

                @forelse ($product->logs as $log)
                    <tr>

                        <td>{{ $log->title }}</td>

                        <td>
                            @php
                                $updatedColumns = json_decode($log->updated_columns, true);
                            @endphp

                            @foreach ($updatedColumns as $column)
                                <span>{{ $column }}</span> <br>
                            @endforeach
                        </td>

                        <td>{{ $log->editer->name }} <br> {{ $log->editer->mobile }} </td>

                        <td>{{ $log->created_at }} <br> <small>{{ timeFormat($log->created_at) }}</small> </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4">لا يوجد اي بيانات  </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

        <x-admin.forms.deleteModel model="products/logs" id="product_id"></x-admin.forms.deleteModel>




    </div>
@endsection


@section('js')
    <script>
        $('#aside .products').addClass('active');
        tippy('[data-tippy-content]');

        function show_delete_model(e) {

            event.stopPropagation();
            let element = e;
            let data_id = element.getAttribute('data-id')

            $('#model_title').text("سيتم ازالة سجل التعديلات الخاص بهذا المنتج  قبل اخر 10 ايام");

            $("input[name='product_id']").val(data_id)

        }
    </script>
@endsection
