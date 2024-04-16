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

    <style>
        .way {
            display: none;
        }

        #loader {
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            z-index: 9;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            display: none;
        }

        #loader {
            font-size: 40px;
            color: var(--mainColor);
        }
    </style>
@endsection


@section('content')
    <div class="contnet-title">اعدادات الحساب</div>


    <div class="w-100 row m-auto p-0">
        <div class="col-lg-3 p-lg-1 p-0">

            <nav>

                <div class="nav nav-tabs" id="nav-tab" role="tablist">

                    <x-tab class="active" name="info" title="المعلومات الاساسية"><svg width="22"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg></x-tab>


                    <x-tab name="password" title="تغير كلمة السر"><svg width="22" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                        </svg>
                    </x-tab>


                    @if (auth()->user()->role != 'admin')

                    <x-tab name="payment" title="طرق سحب العمولات"><svg width="22" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                        </svg>

                    </x-tab>
                    @endif

                    @if (auth()->user()->role == 'user')
                        <x-tab name="nofi" title="اعدادات الاشعارات"><svg width="22"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>

                        </x-tab>
                    @endif


                </div>
            </nav>
        </div>

        <div class="col-lg-9  p-lg-1 p-0 mb-5">
            <div class="tab-content" id="nav-tabContent">

                <div class="tab-pane fade show active" id="nav-info" role="tabpanel" aria-labelledby="nav-info-tab"
                    tabindex="0">
                    <div class="tab_content p-3">
                        @include('profile/info')
                    </div>
                </div>

                <div class="tab-pane fade show " id="nav-password" role="tabpanel" aria-labelledby="nav-password-tab"
                    tabindex="0">
                    <div class="tab_content p-3">
                        @include('profile/password')
                    </div>
                </div>

                @if (auth()->user()->role != 'admin')


                <div class="tab-pane fade show " id="nav-payment" role="tabpanel" aria-labelledby="nav-payment-tab"
                    tabindex="0">
                    <div class="tab_content p-3">
                        @include('profile/payment')
                    </div>
                </div>
                @endif


                @if (auth()->user()->role == 'user')
                    <div class="tab-pane fade show " id="nav-nofi" role="tabpanel" aria-labelledby="nav-nofi-tab"
                        tabindex="0">
                        <div class="tab_content p-3">
                            @include('profile/nofi')
                        </div>
                    </div>
                @endif


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
            if (activeTab === "nav-info" || activeTab === "") {
                removeActiveFromTabs(); // Remove active class from other tabs
                const navImgsTab = document.getElementById("nav-info-tab");
                const navImgsTabContent = document.getElementById("nav-info");
                navImgsTab.classList.add("active");
                navImgsTab.setAttribute("aria-selected", "true");
                navImgsTabContent.classList.add("show", "active");
            } else if (activeTab === "nav-password") {
                removeActiveFromTabs(); // Remove active class from other tabs
                const navImgsTab = document.getElementById("nav-password-tab");
                const navImgsTabContent = document.getElementById("nav-password");
                navImgsTab.classList.add("active");
                navImgsTab.setAttribute("aria-selected", "true");
                navImgsTabContent.classList.add("show", "active");

            } else if (activeTab === "nav-payment") {
                removeActiveFromTabs(); // Remove active class from other tabs
                const navImgsTab = document.getElementById("nav-payment-tab");
                const navImgsTabContent = document.getElementById("nav-payment");
                navImgsTab.classList.add("active");
                navImgsTab.setAttribute("aria-selected", "true");
                navImgsTabContent.classList.add("show", "active");

            } else if (activeTab === "nav-nofi") {
                removeActiveFromTabs(); // Remove active class from other tabs
                const navImgsTab = document.getElementById("nav-nofi-tab");
                const navImgsTabContent = document.getElementById("nav-nofi");
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

    <script>
        function show_delete_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $('#model_title').text(data_name);

            $("input[name='payment_id']").val(data_id)

        }
    </script>
@endsection
