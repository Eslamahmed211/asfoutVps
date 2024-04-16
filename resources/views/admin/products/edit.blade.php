@extends('admin/layout')

@section('css')
    <style>
        form {
            margin: auto;
            padding: 10px;
        }

        form .col-lg-6 col-12 {
            padding: 0px !important;
        }

        .ssi-dropZonePreview {
            min-height: 150px !important;
        }

        .prodcut_img {
            width: 65px;
            height: 65px;
            object-fit: contain
        }

        .path {
            padding: 0px 10px;
        }
    </style>
@endsection

@section('path')
    <ul class="paths">
        <li> <a href="{{ url('admin/products') }}"> المنتجات / </a> </li>
        <li class="active"> {{ $product->name }} </li>
    </ul>
@endsection


@section('content')
    <div class=" mt-lg-4 mx-3  mb-4  ">

        <div class="w-100">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button onclick="change('nav-edit')" class="nav-link active" id="nav-edit-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-edit" type="button" role="tab" aria-controls="nav-edit"
                        aria-selected="true">تعديل البيانات</button>

                    <button class="nav-link " onclick="change('nav-imgs')" id="nav-imgs-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-imgs" type="button" role="tab" aria-controls="nav-imgs"
                        aria-selected="true">صور المنتج</button>


                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">

                <div class="tab-pane fade show active" id="nav-edit" role="tabpanel" aria-labelledby="nav-edit-tab"
                    tabindex="0">
                    <div class="py-3">
                        @include('admin.products.tabs.edit')
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-imgs" role="tabpanel" aria-labelledby="nav-imgs-tab" tabindex="0">
                    <div class="py-3">
                        @include('admin.products.tabs.imgs')

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection



@section('js')
    <script>
        $('#aside .products').addClass('active');

        tippy('[data-tippy-content]');

        $(".content").css("padding", "0px 10px")

        $("#select-dropdown").selectize({

            delimiter: ',',
            plugins: ["restore_on_backspace", "clear_button", "drag_drop", "remove_button"],

            persist: false,
            maxItems: null,
            placeholder: 'اختار تصنيفات من هنا', // Add your desired placeholder text here


        });

        function show_delete_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $('#model_title').text(data_name);

            $("input[name='img_id']").val(data_id)

        }
    </script>

    {{-- tabs --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Read the URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get("tab");

            // Function to remove active class from other tabs
            function removeActiveFromTabs() {
                const tabs = document.querySelectorAll(".nav-link");
                const tabContents = document.querySelectorAll(".tab-pane");
                tabs.forEach((tab) => {
                    tab.classList.remove("active");
                    tab.setAttribute("aria-selected", "false");
                });
                tabContents.forEach((tabContent) => {
                    tabContent.classList.remove("show", "active");
                });
            }

            // Activate the tab based on the URL parameter
            if (activeTab === "nav-imgs") {
                removeActiveFromTabs(); // Remove active class from other tabs
                const navImgsTab = document.getElementById("nav-imgs-tab");
                const navImgsTabContent = document.getElementById("nav-imgs");
                navImgsTab.classList.add("active");
                navImgsTab.setAttribute("aria-selected", "true");
                navImgsTabContent.classList.add("show", "active");
            }
        });

        function change(tab) {
            const urlWithoutTab = window.location.href.split("?")[0];
            const newUrl = urlWithoutTab + `?tab=${tab}`;
            window.history.replaceState({}, "", newUrl);
        }
    </script>

    {{-- upload --}}
    <script>
        $('#ssi-uploader').ssi_uploader({
            inForm: true,
            dropZone: true,
            allowed: ['jpg', 'jpeg', 'png', 'webp', 'gif'],
            allowDuplicates: false,
            maxFileSize:10,


        });
    </script>




    <x-admin.extra.move model="product_images"></x-admin.extra.move>
@endsection
