@extends('admin/layout')


@section('css')
    <style>

    </style>
@endsection


@section('content')
    <x-searchForm action="/admin/users/search">

        @php
            !empty($_GET['id']) ? ($id = $_GET['id']) : ($id = '');
            !empty($_GET['name']) ? ($name = $_GET['name']) : ($name = '');
            !empty($_GET['mobile']) ? ($mobile = $_GET['mobile']) : ($mobile = '');
            !empty($_GET['email']) ? ($email = $_GET['email']) : ($email = '');

            !empty($_GET['role']) ? ($role = $_GET['role']) : ($role = '');
            !empty($_GET['active']) ? ($active = $_GET['active']) : ($active = '');
            !empty($_GET['deleted']) ? ($deleted = $_GET['deleted']) : ($deleted = '');
            !empty($_GET['date']) ? ($date = $_GET['date']) : ($date = '');
            !empty($_GET['withModetor']) ? ($withModetor = $_GET['withModetor']) : ($withModetor = '');
        @endphp


        <x-admin.forms.input name="id" value="{{ $id }}" placeholder="مثال : 123456" lable_title="كود المستخدم">
        </x-admin.forms.input>



        <x-admin.forms.input name="name" value="{{ $name }}" lable_title="اسم المسخدم"
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


        <div title="نوع الحساب">
            <select name="role">
                <option value="">كل الحسابات</option>
                <option @if ($role == 'user') selected @endif value="user">مسوق</option>
                <option @if ($role == 'moderator') selected @endif value="moderator">مودريتور</option>
                <option @if ($role == 'admin') selected @endif value="admin">ادمن</option>
                <option @if ($role == 'super') selected @endif value="super">خدمة عملاء</option>
                <option @if ($role == 'trader') selected @endif value="trader">تاجر</option>
                <option @if ($role == 'postman') selected @endif value="postman">مندوب شحن</option>
            </select>
        </div>


        <div title="حالة الحساب">
            <select name="active">
                <option value="">كل الحالات</option>
                <option @if ($active == 'نشط') selected @endif value="نشط">نشط</option>
                <option @if ($active == 'غير نشط') selected @endif value="غير نشط">غير نشط</option>
                <option @if ($active == 'محظور') selected @endif value="محظور">محظور</option>
            </select>
        </div>


        <div title="الموديور">

            <select name="withModetor">

                <option @selected($withModetor == 'no') value="no"> بدون الموديتور</option>
                <option @selected($withModetor == 'yes') value="yes">مع الموديتور</option>
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


        <div class="contnet-title">المستخدمين </div>

        <div class="d-flex align-items-center gap-2">


            <x-searchBtn></x-searchBtn>

            @can('has', 'users_action')
                <x-link path="/admin/users/create" title="اضافة مستخدم"><svg width="22" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg></x-link>
            @endcan

        </div>




    </div>

    <div class="tableSpace">

        <table class="not">
            <thead>


                <tr>

                    <th>#</th>
                    @can('has', 'users_action')
                        <th><input onchange="allUsers(this)" type="checkbox" class="not"></th>
                    @endcan

                    <th>الاسم</th>
                    <th>الايميل</th>
                    <th>التليفون</th>
                    <th> نوع الحساب</th>
                    <th>المحافظة</th>
                    <th>تاريخ التسجيل</th>

                    <th> حالة الحساب </th>

                    @can('has', 'users_action')
                        <th> الإجراءات </th>
                    @endcan

                </tr>

            </thead>

            <tbody class="clickable">
                @php
                    $rowNumber = ($users->currentPage() - 1) * $users->perPage() + 1;
                @endphp

                @foreach ($users as $outerIndex => $user)
                    <tr style="cursor: pointer">
                        <td> {{ $rowNumber }} </td>
                        @can('has', 'users_action')
                            <td>
                                <input type="checkbox" @if ($user->deleted_at) disabled @endif
                                    onchange="check(this)" class="not UsersCheckBox" value="{{ $user->id }}">
                            </td>
                        @endcan
                        <td data-text="الاسم">{{ $user->name }}</td>
                        <td data-text="الايميل">{{ $user->email }}</td>
                        <td data-text="الموبيل">{{ $user->mobile }}</td>

                        @if ($user->role == 'admin')
                            <td data-text="نوع الحساب">ادمن</td>
                        @elseif($user->role == 'user')
                            <td data-text="نوع الحساب">مسوق</td>
                        @elseif($user->role == 'postman')
                            <td data-text="نوع الحساب">مندوب شحن</td>
                        @elseif($user->role == 'trader')
                            <td data-text="نوع الحساب">تاجر</td>
                        @elseif($user->role == 'moderator')
                            <td data-text="نوع الحساب">موديتور</td>
                        @endif

                        <td data-text="المحافظة">{{ $user->city }}</td>
                        <td data-text="تاريخ التسجيل">{{ fixData($user->created_at) }}</td>

                        @if ($user->active == 1)
                            <td data-text="حالة الحساب"><span class="Delivered">نشط</span></td>
                        @elseif($user->active == 0)
                            <td data-text="حالة الحساب"><span class="tryAgain">غير نشط</span></td>
                        @elseif($user->active == 3)
                            <td data-text="حالة الحساب"><span class="cancelled">محظور</span></td>
                        @else
                            <td data-text="حالة الحساب"><span class="cancelled">مجهول</span></td>
                        @endif

                        @can('has', 'users_action')
                            <td data-text="الإجراءات">
                                <div>
                                    @if (!$user->deleted_at)
                                        <div onclick='window.location.href = "/admin/users/{{ $user->id }}/edit"'
                                            data-tippy-content="تعديل" class="square-btn ltr has-tip"><i
                                                class="far fa-edit mr-2 icon" aria-hidden="true"></i></div>
                                    @endif

                                    @if (!$user->deleted_at)
                                        <div type="button" data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                            onclick="show_delete_model(this)" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal" data-tippy-content="حذف"
                                            class="square-btn ltr has-tip"><i class="far fa-trash-alt mr-2 icon"
                                                aria-hidden="true"></i></div>
                                    @else
                                        <div type="button" data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                            onclick="show_restore_model(this)" data-tippy-content="استرجاع"
                                            class="square-btn ltr has-tip"><i class="fa-solid fa-trash-arrow-up mr-2 icon"
                                                aria-hidden="true"></i></div>
                                    @endif
                                </div>
                            </td>
                        @endcan
                        @php
                            $rowNumber++;
                        @endphp

                    </tr>
                    @if (!empty($user->moderators) && $withModetor == 'yes')
                        @foreach ($user->moderators as $innerIndex => $moderator)
                            <tr>
                                <td> {{ $rowNumber }} </td>
                                <td>
                                    <input type="checkbox" @if ($moderator->deleted_at) disabled @endif
                                        onchange="check(this)" class="not UsersCheckBox" value="{{ $moderator->id }}">
                                </td>


                                <td data-text="الاسم">{{ $moderator->name }}</td>
                                <td data-text="الايميل">{{ $moderator->email }}</td>
                                <td data-text="الموبيل">{{ $moderator->mobile }}</td>



                                @if ($moderator->role == 'moderator')
                                    <td data-text="حالة الحساب">موديتور</td>
                                @endif


                                <td data-text="المحافظة">{{ $moderator->city }}</td>


                                <td data-text="تاريخ التسجيل">{{ fixData($moderator->created_at) }}</td>



                                @if ($moderator->active == 1)
                                    <td data-text="حالة الحساب"><span class="Delivered">نشط</span></td>
                                @elseif($moderator->active == 0)
                                    <td data-text="حالة الحساب"><span class="tryAgain">غير نشط</span></td>
                                @elseif($moderator->active == 3)
                                    <td data-text="حالة الحساب"><span class="cancelled">محظور</span></td>
                                @else
                                    <td data-text="حالة الحساب"><span class="cancelled">مجهول</span></td>
                                @endif

                                <td data-text="الإجراءات">
                                    <div>
                                        @if (!$moderator->deleted_at)
                                            <div onclick='window.location.href = "/admin/users/{{ $moderator->id }}/edit"'
                                                data-tippy-content="تعديل" class="square-btn ltr has-tip"><i
                                                    class="far fa-edit mr-2 icon" aria-hidden="true"></i></div>
                                        @endif
                                        @if (!$moderator->deleted_at)
                                            <div type="button" data-id="{{ $moderator->id }}"
                                                data-name="{{ $moderator->name }}" onclick="show_delete_model(this)"
                                                data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                data-tippy-content="حذف" class="square-btn ltr has-tip"><i
                                                    class="far fa-trash-alt mr-2 icon" aria-hidden="true"></i></div>
                                        @else
                                            <div type="button" data-id="{{ $moderator->id }}"
                                                data-name="{{ $moderator->name }}" onclick="show_restore_model(this)"
                                                data-tippy-content="استرجاع" class="square-btn ltr has-tip"><i
                                                    class="fa-solid fa-trash-arrow-up mr-2 icon" aria-hidden="true"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                @php
                                    $rowNumber++;
                                @endphp
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>

        </table>

        <x-admin.forms.deleteModel model="users" id="user_id" withInput></x-admin.forms.deleteModel>

    </div>
    @can('has', 'users_action')
        <x-admin.forms.mainBtn onclick="bulkActive()" title="تفعيل" class="m-2" id="active" style="display: none">
        </x-admin.forms.mainBtn>
    @endcan
    <div title="bulk" class="col-12 " style="display: none" title="فورم البلك">
        <form action="/admin/users/bulkActive" method="POST" id="bulkActiveForm">
            @csrf
            <input type="hidden" name="ids" id="ids">
        </form>

        <x-admin.forms.mainBtn onclick="bulkActive()" title="تفعيل الحسابات المختارة" icon="delete"
            class="ssi-button info"></x-admin.forms.mainBtn>
    </div>


    <div class="pagnate" class="السابق والتالي">
        {{ $users->appends(request()->query())->links() }}
    </div>
@endsection




@section('js')
    <script>
        $('#aside .users').addClass('active');

        function show_delete_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $('#model_title').text(data_name);

            $("input[name='user_id']").val(data_id)

        }


        flatpickr('input.date', {
            enableTime: false,
            mode: "range",

            dateFormat: "Y-m-d"
        });
    </script>

    <script>
        function allUsers(e) {
            $("input.UsersCheckBox").not(e).prop("checked", e.checked);

            $("tr").toggleClass("active", e.checked);
            check()
        }

        function check(e) {

            if ($(e).prop("checked")) {
                $(e).parent().parent().addClass("active")
            } else {
                $(e).parent().parent().removeClass("active")
            }


            var count = $('input[type="checkbox"].UsersCheckBox:checked').length;




            if (count == 0) {
                $("#active").hide();
            } else {
                $("#active").show();

            }

        }


        $("tr").on("click", function(event) {
            if ($(event.target).is("td:not(:first-child)")) {
                $(this).toggleClass("active");

                var checkbox = $(this).find("input[type='checkbox']");
                checkbox.prop("checked", !checkbox.prop("checked"));
            }

            check()
        });



        function bulkActive() {
            if (confirm("هل انت متاكد من التفعيل")) {

                let ids = [];
                let inputs = $('input.UsersCheckBox[type="checkbox"]:checked ');

                for (const input of inputs) {
                    ids.push(input.value);
                }

                $("input#ids").val(ids);
                $("#bulkActiveForm").submit();


            }
        }
    </script>

    <script>
        function show_restore_model(e) {

            let element = e;


            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            if (confirm("هل انت متاكد من استرجاع " + data_name)) {
                window.location.href = `/admin/users/restore/${data_id}`;
            }

        }
    </script>
@endsection
