@extends('admin/layout')



@section('content')
    <x-searchForm action="/users/moderators/search">

        @php
            !empty($_GET['id']) ? ($id = $_GET['id']) : ($id = '');
            !empty($_GET['name']) ? ($name = $_GET['name']) : ($name = '');
            !empty($_GET['mobile']) ? ($mobile = $_GET['mobile']) : ($mobile = '');
            !empty($_GET['email']) ? ($email = $_GET['email']) : ($email = '');

            !empty($_GET['role']) ? ($role = $_GET['role']) : ($role = '');
            !empty($_GET['active']) ? ($active = $_GET['active']) : ($active = '');
            !empty($_GET['deleted']) ? ($deleted = $_GET['deleted']) : ($deleted = '');
            !empty($_GET['date']) ? ($date = $_GET['date']) : ($date = '');
        @endphp


        <x-admin.forms.input name="id" value="{{ $id }}" placeholder="مثال : 123456" lable_title="كود المستخدم">
        </x-admin.forms.input>



        <x-admin.forms.input name="name" value="{{ $name }}" lable_title="اسم الموديتور"
            placeholder="مثال : اسلام احمد">
        </x-admin.forms.input>

        <div>
            <x-admin.forms.input name="mobile" placeholder="مثال : 0100xxxxxxx " value="{{ $mobile }}"
                lable_title=" رقم التليفون">

            </x-admin.forms.input>
        </div>

        <x-admin.forms.input name="email" value="{{ $email }}" lable_title="البريد الالكتروني"
            placeholder="مثال : Eslam@gmail.com ">
        </x-admin.forms.input>


        <div title="حالة الحساب">
            <select name="active">
                <option value="">كل الحالات</option>
                <option @if ($active == 'نشط') selected @endif value="نشط">نشط</option>
                <option @if ($active == 'غير نشط') selected @endif value="غير نشط">غير نشط</option>
            </select>
        </div>



        <div dir="ltr">
            <input name="date" class="date" type="text" value="{{ $date }}" placeholder="اختار التاريخ">
        </div>



        <div title="محذوف" style="margin-top: 16px">

            <select name="deleted">

                <option value="">محذوف او غير محذوف</option>
                <option value="yes" @if ($deleted == 'yes') selected @endif>محذوف فقط</option>
                <option value="no" @if ($deleted == 'no') selected @endif>غير محذوف فقط</option>
            </select>
        </div>





    </x-searchForm>


    <div class="actions">
        <div class="contnet-title">المودريتور </div>

        <div class="d-flex align-items-center gap-2">


            <x-searchBtn></x-searchBtn>

            <x-link path="/users/moderators/create" title="اضافة مودريتور"><svg width="22"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg></x-link>

        </div>
    </div>

    <div class=" tableSpace" title="الجدول">

        <table>
            <thead>


                <tr>

                    <th>#</th>
                    <th>الاسم</th>
                    <th>الايميل</th>
                    <th>التليفون</th>
                    <th>المحفظة</th>
                    <th>تاريخ التسجيل</th>
                    <th> حالة الحساب </th>
                    <th> الإجراءات </th>

                </tr>

            </thead>

            <tbody>

                @foreach ($moderators as $moderator)
                    <tr>


                        <td>{{ $loop->index += 1 }}</td>

                        <td><a href="/users/moderators/{{ $moderator->id }}/edit">{{ $moderator->name }}</a></td>

                        <td>{{ $moderator->email }} </td>
                        <td>{{ $moderator->mobile }}</td>
                        <td>{{ $moderator->wallet }}</td>
                        <td> {{ fixdata($moderator->created_at) }}</td>

                        @if ($moderator->deleted_at)
                            <td data-text="حالة الحساب "><span class="cancelled">محذوف</span></td>
                        @else
                            @if ($moderator->active == 1)
                                <td data-text="حالة الحساب "><span class="Delivered">نشط</span></td>
                            @elseif($moderator->active == 0)
                                <td data-text="حالة الحساب "><span class="tryAgain">غير نشط</span></td>
                            @elseif($moderator->active == 3)
                                <td data-text="حالة الحساب "><span class="cancelled">محظور</span></td>
                            @else
                                <td data-text="حالة الحساب "><span class="cancelled">مجهول</span></td>
                            @endif
                        @endif

                        <td data-text="الإجراءات">

                            <div class="d-flex">
                                @if (!$moderator->deleted_at)
                                    <div>
                                        <div onclick='window.location.href = "/users/moderators/{{ $moderator->id }}/edit"'
                                            id="myButton" data-tippy-content="تعديل" class="square-btn ltr has-tip"><i
                                                class="far fa-edit mr-2  icon" aria-hidden="true"></i></div>

                                    </div>
                                @endif


                                @if (!$moderator->deleted_at)
                                    <div type="button" data-id="{{ $moderator->id }}" data-name="{{ $moderator->name }}"
                                        onclick="show_delete_model(this)" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" data-tippy-content="حذف"
                                        class="square-btn ltr has-tip"><i class="far fa-trash-alt mr-2 icon"
                                            aria-hidden="true"></i></div>
                                @else
                                    <div type="button" data-id="{{ $moderator->id }}" data-name="{{ $moderator->name }}"
                                        onclick="show_restore_model(this)" data-tippy-content="استرجاع"
                                        class="square-btn ltr has-tip"><i class="fa-solid fa-trash-arrow-up mr-2 icon"
                                            aria-hidden="true"></i></div>
                                @endif

                            </div>

                        </td>


                    </tr>
                @endforeach

            </tbody>

        </table>


        <x-admin.forms.deleteModel type="users" model="moderators" id="moderator_id"
            withInput></x-admin.forms.deleteModel>



    </div>
@endsection


@section('js')
    <script>
        $('#aside .moderators').addClass('active');


        flatpickr('input.date', {
            enableTime: false,
            mode: "range",

            dateFormat: "Y-m-d"
        });


        function show_delete_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $('#model_title').text(data_name);

            $("input[name='moderator_id']").val(data_id)

        }
    </script>

    <script>
        function show_restore_model(e) {

            let element = e;


            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            if (confirm("هل انت متاكد من استرجاع " + data_name)) {
                window.location.href = `/users/moderators/restore/${data_id}`;
            }

        }
    </script>
@endsection
