    <style>
        #loader {
            position: fixed;
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

        @media(max-width:993px) {
            table:not(.ssi-imgToUploadTable) td {
                padding: .7rem;
                font-size: 14px;
            }
        }

        .prodcut_img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            border-radius: 5px;

        }

        .frame table td {
            padding: 5px 5px !important;
            font-size: 13px !important;
        }



        .frame {
            background: rgba(0, 0, 0, .32);
            position: fixed;
            top: 0px;
            left: 0px;
            width: calc(100%);
            height: 100%;
            z-index: 9999999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .frameContent {
            box-shadow: 0 11px 15px -7px #0003, 0 24px 38px 3px #00000024, 0 9px 46px 8px #0000001f;
            background: #fff;
            border-radius: 4px;
            width: 70%;
            padding: 20px;
            overflow-y: scroll
        }

        @media (max-width:993px) {
            .frame {
                width: 100%;
            }

            .frameContent {
                width: 92vw;

            }
        }


        .frameHead {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }


        .value {
            color: var(--mainColor);
            font-size: 14px;
            font-weight: 600;

        }

        hr {

            opacity: .1
        }
    </style>


    <div class="frame" id="frame" style="display: none ">

        <div style="background-color: #f8f8f8" class="frameContent" onclick="event.stopPropagation();">
            <div class="frameHead">

                <div class="group">
                    <div>كود الطلب</div>
                    <div class="value" id="reference"></div>
                </div>

                <div class="group">
                    <div> اسم العميل</div>
                    <div class="value" id="name"> </div>
                </div>


                <div class="group">
                    <div> المحافظة </div>
                    <div class="value" id="city"> </div>
                </div>


                <div class="group">
                    <div>رقم الموبايل</div>
                    <div class="value" id="mobile"></div>
                </div>

                <div class="group">
                    <div id="status" class=" orderStatus "> </div>
                </div>
            </div>

            <hr>

            <p style="font-size: 20px; font-weight: 600">المنتجات</p>

            <div style="overflow-x: scroll">

                <table>
                    <thead>
                        <th>صورة المنتج</th>
                        <th>اسم المنتج</th>
                        <th>عدد القطع</th>
                        <th>السعر</th>
                        <th>العمولة</th>
                    </thead>

                    <tbody id="frameTable">


                    </tbody>
                </table>

            </div>
        </div>
    </div>

    @if (auth()->user()->role == 'admin' && request()->query('status') == 'جاهز للتغليف')
        <div class="frame " id="TestingFream" style="display: none ">

            <div style="background-color: #f8f8f8" class="frameContent" onclick="event.stopPropagation();">
                <div class="frameHead">

                    <div class="group">
                        <div>كود الطلب</div>
                        <div class="value" id="reference"></div>
                    </div>

                    <div class="group">
                        <div> اسم العميل</div>
                        <div class="value" id="name"> </div>
                    </div>


                    <div class="group">
                        <div> المحافظة </div>
                        <div class="value" id="city"> </div>
                    </div>


                    <div class="group">
                        <div>رقم الموبايل</div>
                        <div class="value" id="mobile"></div>
                    </div>

                    <div class="group">
                        <div id="status" class=" orderStatus "> </div>
                    </div>
                </div>


                <p style="font-size: 20px; font-weight: 600">المنتجات</p>

                <input type="text" id="testingInput" placeholder="اكتب هنا اكواد المنتجات واضغط Enter"
                    style="background: white" class="mx-auto mb-2">

                <div style="overflow-x: scroll">

                    <table>
                        <thead>
                            <th>صورة المنتج</th>
                            <th>اسم المنتج</th>
                            <th>عدد القطع</th>
                            <th>الفحص</th>
                        </thead>

                        <tbody id="frameTable2">


                        </tbody>
                    </table>

                </div>


                @if (request()->query('status') == 'طلب استرجاع')
                    <button disabled class="mainBtn mt-2">استلام جزئي</button>
                @endif


            </div>


        </div>
    @endif


    @if (auth()->user()->role == 'admin' && request()->query('status') == 'طلب استرجاع')
        <div class="frame " id="TestingFream" style="display: none ">

            <div style="background-color: #f8f8f8" class="frameContent" onclick="event.stopPropagation();">
                <div class="frameHead">

                    <div class="group">
                        <div>كود الطلب</div>
                        <div class="value" id="reference"></div>
                    </div>

                    <div class="group">
                        <div> اسم العميل</div>
                        <div class="value" id="name"> </div>
                    </div>


                    <div class="group">
                        <div> المحافظة </div>
                        <div class="value" id="city"> </div>
                    </div>


                    <div class="group">
                        <div>رقم الموبايل</div>
                        <div class="value" id="mobile"></div>
                    </div>

                    <div class="group">
                        <div id="status" class=" orderStatus "> </div>
                    </div>
                </div>


                <p style="font-size: 20px; font-weight: 600">المنتجات</p>

                <input type="text" id="return_input" placeholder="اكتب هنا اكواد المنتجات واضغط Enter"
                    style="background: white" class="mx-auto mb-2">

                <div style="overflow-x: scroll">

                    <table>
                        <thead>
                            <th>صورة المنتج</th>
                            <th>اسم المنتج</th>
                            <th>عدد القطع</th>
                            <th>الفحص</th>
                        </thead>

                        <tbody id="frameTable2">


                        </tbody>
                    </table>

                </div>


                <button disabled class="mainBtn mt-2" id="returnBtn" onclick="partial_refund()">استلام جزئي</button>

            </div>


        </div>
    @endif
