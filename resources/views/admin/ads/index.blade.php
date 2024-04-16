@extends('admin.layout')




@section('content')
    <div class="  mt-3 ">


        <form class="row  bg-white  py-4 mx-2 rounded" action="{{ url('admin/ads/add') }}" enctype="multipart/form-data"
            method="post" id="theForm" autocomplete="off">

            @csrf

            <div class="col-lg-5 col-12">
                <x-admin.forms.input notRequired for="link" lable_title="اللينك" name="link" placeholder="اللينك">
                </x-admin.forms.input>
            </div>


            <div class="col-lg-5 col-12">
                <x-admin.forms.input class="checkThis" for="alt" lable_title="عنوان البنر   " name="alt"
                    placeholder="عنوان البنر   ">
                </x-admin.forms.input>
            </div>

            <div class="col-lg-2  col-12">
                <div>
                    <label for="show" class="mb-2">الحالة</label>
                </div>
                <select name="show">
                    <option value="show">ظهور</option>
                    <option value="hidden">اخفاء</option>
                </select>
            </div>


            <div class="col-12">
                <div class="contnet-title">البنر الاعلاني</div>
                <input class="checkThis" type="file" name="img" accept="image/*">
            </div>






            <div class="col-12">
                <x-admin.forms.mainBtn onclick="validate()" icon="plus" title="اضافة" class="mt-3">
                </x-admin.forms.mainBtn>
            </div>




        </form>

        <x-admin.forms.deleteModel model="ads" id="ads_id"></x-admin.forms.deleteModel>


        <div class="mt-2 mx-1 tableSpace p-0">


            <table class="not">
                <thead>

                    <tr>

                        <th>الصورة</th>
                        <th>عنوان البنر</th>
                        <th>حالة البنر</th>
                        <th>الاجراءات</th>
                    </tr>

                </thead>
                <tbody>

                    @forelse ($ads as $ad)
                        <tr>

                            @php
                                $img = str_replace('public', 'storage', $ad->img);
                            @endphp
                            <td><a target="_blank" href="{{ $ad->link }}"><img style="width:200px ; height:80px;"
                                        src="{{ asset("$img") }}"> </a></td>


                            <td>{{ $ad->alt }}</td>

                            <td>
                                <div class="form-element inlined switch intable">
                                    <div class="flex">
                                        <label>
                                            <div class="toggle-container lg">
                                                <input value="{{ $ad->id }}" onchange="showHideAds(this)"
                                                    @if ($ad->show == 'show') checked @endif type="checkbox"
                                                    class="sr-only">
                                                <div class="switch-bg"></div>
                                                <div class="dot"></div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </td>

                            <td data-text="الإجراءات">
                                <div>
                                    <div onclick='window.location.href = "/admin/ads/edit/{{ $ad->id }}"'
                                        id="myButton" data-tippy-content="تعديل" class="square-btn ltr has-tip"><i
                                            class="far fa-edit mr-2  icon" aria-hidden="true"></i></div>


                                    <div type="button" data-id="{{ $ad->id }}" data-name="{{ $ad->alt }}"
                                        onclick="show_delete_model(this)" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" data-tippy-content="حذف"
                                        class="square-btn ltr has-tip"><i class="far fa-trash-alt mr-2 icon"
                                            aria-hidden="true"></i></div>
                                </div>

                            </td>



                        </tr>

                    @empty
                        <tr>
                            <td colspan="4">لا يوجد بنرات اعلانية </td>
                        </tr>
                    @endforelse

                </tbody>


            </table>



            <x-admin.forms.deleteModel model="ads" id="ads_id"></x-admin.forms.deleteModel>



        </div>
    </div>
@endsection


@section('js')
    <script>
        $('#aside .ads ').addClass('active');
        tippy('[data-tippy-content]')

        function show_delete_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $('#model_title').text(data_name);

            $("input[name='ads_id']").val(data_id)
        }


        function showHideAds(e) {
            let id = e.value;

            let show = 'hidden';

            if (e.checked) {
                show = 'show';
            }

            fetch(`/admin/ads/showHideAds`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({
                        id: id,
                        show: show
                    }),
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json(); // Parse the response body as JSON
                })

        }
    </script>
@endsection
