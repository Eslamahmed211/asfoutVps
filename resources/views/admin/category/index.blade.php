@extends('admin/layout')



@section('content')
    <div class="actions">

        <div class="contnet-title">التصنيفات </div>

        @can('has', 'products_action')
            <div class="d-flex align-items-center gap-2">

                <a class="xbutton" href="/admin/categories/create">
                    اضافة تصنيف <svg width="22" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg>
                </a>

            </div>
        @endcan

    </div>

    @can('has', 'products_action')
        <p class="tip"><i aria-hidden="true" class="fas fa-info-circle "></i>

            يمكن تغير ترتيب التنصيقات من خلال الازرار او من خلال Drag and Drop

        </p>
    @endcan


    <div class=" tableSpace ">



        <table class="not">
            <thead>

                <tr>
                    <th>#</th>

                    <th> العنوان </th>
                    @can('has', 'products_action')
                        <th> الإجراءات </th>
                    @endcan
                </tr>

            </thead>
            <tbody>


                @forelse ($Categories as $category)
                    <tr draggable='true' ondragstart='start()' ondragover='dragover()'>


                        <td style="font-weight: 600" data-text="#">{{ $category->order }}</td>

                        <input type="hidden" value="{{ $category->id }}" class="ids">

                        <td data-text='العنوان'> {{ $category->name }}
                        </td>
                        @can('has', 'products_action')
                            <td data-text="الإجراءات">
                                <div>
                                    <div onclick="up(this)" data-tippy-content="فوق" class="square-btn ltr has-tip"><i
                                            class=" fa-solid fa-up-long mr-2  icon" aria-hidden="true"></i></div>

                                    <div onclick="down(this)" data-tippy-content="تحت" class="square-btn ltr has-tip">
                                        <i class=" fa-solid fa-down-long mr-2  icon" aria-hidden="true"></i>
                                    </div>

                                    <div onclick='window.location.href = "/admin/categories/{{ $category->id }}/edit"'
                                        id="myButton" data-tippy-content="تعديل" class="square-btn ltr has-tip"><i
                                            class="far fa-edit mr-2  icon" aria-hidden="true"></i></div>


                                    <div type="button" data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                        onclick="show_delete_model(this)" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                        data-tippy-content="حذف" class="square-btn ltr has-tip"><i
                                            class="far fa-trash-alt mr-2 icon" aria-hidden="true"></i></div>
                                </div>

                            </td>
                        @endcan

                    </tr>

                @empty
                    <tr>

                        <td colspan="3">لا يوجد تصنيقات متاحة</td>
                    </tr>
                @endforelse

            </tbody>


        </table>

        @can('has', 'products_action')
            @if (isset($Categories[0]['name']))
                <x-admin.forms.mainBtn onclick="UpdateOrder()" icon="update" title="تحديث" class="mt-3">
                </x-admin.forms.mainBtn>
            @endif
        @endcan

        <x-admin.forms.deleteModel model="categories" id="category_id"></x-admin.forms.deleteModel>



    </div>
@endsection




@section('js')
    <x-admin.extra.move model="categories"></x-admin.extra.move>

    <script>
        $('#aside .products').addClass('active');
        tippy('[data-tippy-content]');

        function show_delete_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $('#model_title').text(data_name);

            $("input[name='category_id']").val(data_id)

        }
    </script>
@endsection
