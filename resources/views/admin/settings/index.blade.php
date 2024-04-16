@extends('admin.layout')

@section('css')
    <style>
        .nav-tabs {
            padding: 16px 8px;
            border-radius: 5px;
        }


        .tab_content {
            background: var(--color);
            padding: 16px 12px;
            border-radius: 5px;
        }


        .nav-link svg {
            margin-left: 5px;
        }

        @media (min-width:993px) {
            .nav-tabs {
                display: flex;
                flex-direction: column;
                padding: 16px 8px;
                border-radius: 5px;

            }

            .nav-tabs button {
                display: flex;
            }

            .tab_content {
                background: var(--color);
                padding: 16px 12px;
                border-radius: 5px;
            }

            .nav-tabs .nav-item.show .nav-link,
            .nav-tabs .nav-link {
                margin-bottom: 10px;
            }

        }
    </style>
@endsection


@section('content')
    <div class="contnet-title">اعدادات الموقع</div>


    <div class="w-100 row m-auto p-0">
        <div class="col-lg-3 p-lg-1 p-0">

            <nav>

                <div class="nav nav-tabs" id="nav-tab" role="tablist">

                    <x-tab class="active" name="branding" title="العلامة التجارية"><svg width="22"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg></x-tab>


                    <x-tab name="social-media" title="لينكات التواصل"><svg width="20" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" />
                        </svg></x-tab>

                    <x-tab name="delivery-price" title="اسعار الشحن"><svg width="20" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                    </x-tab>


                </div>
            </nav>
        </div>

        <div class="col-lg-9  p-lg-1 p-0 mb-5">
            <div class="tab-content" id="nav-tabContent">

                <div class="tab-pane fade show active" id="nav-branding" role="tabpanel" aria-labelledby="nav-branding-tab"
                    tabindex="0">
                    <div class="tab_content p-3">
                        @include('admin/settings/branding')
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-social-media" role="tabpanel" aria-labelledby="nav-social-media-tab"
                    tabindex="0">
                    <div class="tab_content p-3">
                        @include('admin/settings/social-media')

                    </div>
                </div>

                <div class="tab-pane fade" id="nav-delivery-price" role="tabpanel" aria-labelledby="nav-delivery-price-tab"
                    tabindex="0">
                    <div class="tab_content p-3" style="background: transparent">
                        @include('admin/settings/delivery-price')

                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection


@section('js')
    <script>
        $('aside .settings').addClass('active');

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
            if (activeTab === "nav-social-media") {
                removeActiveFromTabs(); // Remove active class from other tabs
                const navImgsTab = document.getElementById("nav-social-media-tab");
                const navImgsTabContent = document.getElementById("nav-social-media");
                navImgsTab.classList.add("active");
                navImgsTab.setAttribute("aria-selected", "true");
                navImgsTabContent.classList.add("show", "active");
            } else if (activeTab === "nav-delivery-price") {
                removeActiveFromTabs(); // Remove active class from other tabs
                const navImgsTab = document.getElementById("nav-delivery-price-tab");
                const navImgsTabContent = document.getElementById("nav-delivery-price");
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
    <x-admin.extra.move model="deliveryPrice"></x-admin.extra.move>

    <script>
        function show_delete_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $('#model_title').text(data_name);

            $("input[name='deliveryPrice_id']").val(data_id)

        }


        function show_new_value_model(e) {

            event.stopPropagation();
            let element = e;
            let data_id = element.getAttribute('data-id')

            $("#name").val(element.getAttribute('data-name'))
            $("#code").val(element.getAttribute('data-code'))
            $("#delivery_price").val(element.getAttribute('data-delivery_price'))
            $("#return_price").val(element.getAttribute('data-return_price'))


            $("input[name='deliveryPrice_id']").val(data_id)
        }
    </script>

    <script>
        function create() {
            let html = ` <form  action="/admin/deliveryPrice" method="post"
            autocomplete="on">

            @csrf

            <div >
                <x-admin.forms.input for="name"  lable_title="اسم المحافظة" id="name"
                    name="name" placeholder="اسم المحافظة">
                </x-admin.forms.input>
            </div>
            <div >
                <x-admin.forms.input for="code" lable_title="كود المحافظة" id="code"
                    name="code" placeholder="كود المحافظة">
                </x-admin.forms.input>
            </div>
            <div >
                <x-admin.forms.input for="delivery_price" min="0" type="number"
                    lable_title="سعر الشحن" id="delivery_price" name="delivery_price" placeholder="سعر الشحن">
                </x-admin.forms.input>
            </div>
            <div >
                <x-admin.forms.input for="return_price" min="0"  type="number"
                    lable_title="سعر المرتجع" id="return_price" name="return_price" placeholder="سعر المرتجع">
                </x-admin.forms.input>

            </div>

            <x-admin.forms.mainBtn type="submit" title="حفظ"> </x-admin.forms.mainBtn>

        </form>`

            Swal.fire({
                title: "اضافة محافظة جديدة",

                html: html,
                focusConfirm: false,
                showConfirmButton: false,
                inputAutoFocus: false,
            })
        }
    </script>
@endsection
