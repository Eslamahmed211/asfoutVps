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

        .path {
            padding: 0px 10px;
        }

        table {
            cursor: pointer;
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
    <div class="w-100">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">

                <button onclick="change('nav-add')" class="nav-link active" id="nav-add-tab" data-bs-toggle="tab"
                    data-bs-target="#nav-add" type="button" role="tab" aria-controls="nav-add"
                    aria-selected="true">اضافة خصائص جديدة</button>


                <button onclick="change('nav-attr')" class="nav-link " id="nav-attr-tab" data-bs-toggle="tab"
                    data-bs-target="#nav-attr" type="button" role="tab" aria-controls="nav-attr" aria-selected="true">
                    الخصائص المربوطة</button>

                <button class="nav-link " onclick="change('nav-var')" id="nav-var-tab" data-bs-toggle="tab"
                    data-bs-target="#nav-var" type="button" role="tab" aria-controls="nav-var"
                    aria-selected="true">المنتجات الفرعية</button>


            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">

            <div class="tab-pane fade show active" id="nav-add" role="tabpanel" aria-labelledby="nav-add-tab"
                tabindex="0">
                <div class="py-3 px-2 mt-3 bg-white rounded">
                    @include('admin.variants.variantsTabs.CreateAttributes')
                </div>
            </div>

            <div class="tab-pane fade show " id="nav-attr" role="tabpanel" aria-labelledby="nav-attr-tab" tabindex="0">
                <div class="py-3  px-2 mt-3 bg-white rounded">
                    @include('admin.variants.variantsTabs.select')
                </div>
            </div>

            <div class="tab-pane fade" id="nav-var" role="tabpanel" aria-labelledby="nav-var-tab" tabindex="0">
                <div class="py-3 px-2 mt-3 bg-white rounded">
                    @include('admin.variants.variantsTabs.variants')

                </div>
            </div>

        </div>
    </div>
@endsection




@section('js')
    <script>
        $("tbody tr").click(function(e) {
            // Check if the clicked element is not the checkbox
            if (!$(e.target).is('input[type="checkbox"]')) {
                let tr = $(this);
                let checkbox = $(tr).find("input[type='checkbox']");
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
            check()
        });

        function allVarant(e) {
            $("input.variantCheckBox").not(e).prop("checked", e.checked);
            check()
        }

        function check() {


            var count = $('input[type="checkbox"].variantCheckBox:checked').length;


            if (count == 0) {
                $("#delete").hide();
            } else {
                $("#delete").show();

            }

        }

        function bulkDelete() {
            if (confirm("هل انت متاكد من الازالة ؟")) {

                let ids = [];
                let inputs = $('input.variantCheckBox[type="checkbox"]:checked ');

                for (const input of inputs) {
                    ids.push(input.value);
                }

                $("input#ids").val(ids);
                $("#bulkDeleteForm").submit();


            }
        }
    </script>


    <script>
        $('#aside .products').addClass('active');

        tippy('[data-tippy-content]');



        $(".select-dropdown").selectize({

            delimiter: ',',
            plugins: ["restore_on_backspace", "clear_button", "drag_drop", "remove_button"],

            persist: false,
            maxItems: null,
            placeholder: 'اختار خصائص المنتج',


        });



        function show_delete_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $('#model_title').text(data_name);

            $("input[name='attribute_id']").val(data_id)

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
            if (activeTab === "nav-var") {
                removeActiveFromTabs(); // Remove active class from other tabs
                const navImgsTab = document.getElementById("nav-var-tab");
                const navImgsTabContent = document.getElementById("nav-var");
                navImgsTab.classList.add("active");
                navImgsTab.setAttribute("aria-selected", "true");
                navImgsTabContent.classList.add("show", "active");
            } else if (activeTab === "nav-add") {
                removeActiveFromTabs(); // Remove active class from other tabs
                const navImgsTab = document.getElementById("nav-add-tab");
                const navImgsTabContent = document.getElementById("nav-add");
                navImgsTab.classList.add("active");
                navImgsTab.setAttribute("aria-selected", "true");
                navImgsTabContent.classList.add("show", "active");
            } else if (activeTab === "nav-attr") {
                removeActiveFromTabs(); // Remove active class from other tabs
                const navImgsTab = document.getElementById("nav-attr-tab");
                const navImgsTabContent = document.getElementById("nav-attr");
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
        function show_new_value_model(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')
            let data_place = element.getAttribute('data-place')

            $("#stock").val(0)
            $("input[name='variant_id']").val(data_id)
            $("input[name='place']").val(data_place)
        }
    </script>

    <script>
        function deleteVariant(e) {

            event.stopPropagation();
            let element = e;
            let data_name = element.getAttribute('data-name')
            let data_id = element.getAttribute('data-id')

            $('#deleteVariant #model_title').text(data_name);

            $("input[name='variant_id']").val(data_id)

        }
    </script>

    <script>
        function qr(id) {

            $.ajax({
                url: `/admin/products/${id}/qrVairant`,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.status == "success") {
                        let html = `
                    <section id="print" class="test text-center">
                        <div style="font-family: cairo ; margin-top:1px;" >
                            ${response.qr}
                        </div>
                        <div style="margin-top: 0px">
                            <div class="name" style="font-size: 12px">${response.name}</div>
                        </div>




                        <style>
                            @page {
                                size: 4in 2in;
                                display: flex;
                                align-items: center;
                                justify-content: center;

                            }

                            @media print {
                              body {
                                  padding:5px;
                                  display: flex;
                                align-items: center;
                                justify-content: center;
                                flex-direction: column;
                              }
                              #print   {
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                flex-direction: column;
                            }
                          }


                            #print   {
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                flex-direction: column;
                                font-family: 'Cairo', sans-serif;
                            }
                            .nameprint {
                                font-family: 'Cairo', sans-serif;
                            }
                            #id {
                                font-family: cairo;
                                font-size: 13px;
                            }
                            #name {
                                text-align: test;
                            }
                            .value {
                                direction: rtl;
                                -webkit-print-color-adjust: exact;
                            }
                        </style>

                    </section>
                `;

                        Swal.fire({
                            title: "",
                            html: html,
                            focusConfirm: false,
                            showConfirmButton: true,
                            inputAutoFocus: false,
                            confirmButtonText: "طباعة",
                        }).then((result) => {
                            if (result.isConfirmed) {

                                $('#print').printThis({

                                    importCSS: true,
                                    importStyle: true,
                                });
                            }
                        })
                    }
                },
            });

        }
    </script>
@endsection
