<form class="row bg-white  py-4 pb-1  px-2 rounded" action="/admin/orders/{{ $order->id }}" method="post" id="theForm"
    autocomplete="off">

    @csrf
    @method('put')

    <div class="col-lg-3 col-12" title="اسم العميل">
        <x-admin.forms.input value="{{ $order->clientName }}" class="checkThis" for="clientName"
            lable_title="اسم العميل ثنائي" name="clientName" placeholder="اسم العميل ثنائي">
        </x-admin.forms.input>
    </div>

    <div class="col-lg-3 col-12" title="رقم العميل">
        <x-admin.forms.input value="{{ $order->clientPhone }}" class="checkThis" for="clientPhone" type="tel"
            lable_title="رقم العميل " name="clientPhone" placeholder="رقم العميل ">
        </x-admin.forms.input>
    </div>



    <div class="col-lg-3 col-12" title="رقم  اخر للعميل ">
        <x-admin.forms.input value="{{ $order->clientPhone2 }}" notRequired for="clientPhone2" type="tel"
            lable_title="رقم  اخر للعميل " name="clientPhone2" placeholder="رقم  اخر للعميل ">
        </x-admin.forms.input>
    </div>


    <div data-title="المحافظة" class="col-lg-3 col-12">


        <label for="city" class="mb-2">المحافظة</label>


        <select name="city" class="js-example-basic-single w-100">


            @foreach ($cities as $city)
                <option @if ($order->city == $city->name) selected @endif value="{{ $city->id }}">
                    {{ $city->name }} ( {{ $city->delivery_price }} )
                </option>
            @endforeach

        </select>


    </div>





    <div title="العنوان" class=" col-lg-8 col-12">


        <x-admin.forms.input value="{{ $order->address }}" class="checkThis" for="address"
            lable_title="العنوان بالتفصيل" name="address" placeholder="العنوان بالتفصيل">
        </x-admin.forms.input>



        @error('address')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>



    <div title="ملاحظات" class=" col-lg-6 col-12 my-2">


        <label for="notes" class="mb-2">ملاحظات لخدمة العملاء <span
                style="font-weight: bold ; font-size: 12px ;color:#aaa">( اختياري)</span>
        </label>

        <textarea name="notes" id="notes" rows="2" oninput="enforceMaxLength(this, 150)">{{ $order->notes }}</textarea>


    </div>

    <div title="محلاظات الشحن" class="col-lg-6 col-12  my-2">


        <label for="notesBosta" class="mb-2">ملاحظات لشركة الشحن <span
                style="font-weight: bold ; font-size: 12px ;color:#aaa">( اختياري)</span> </label>

        <textarea name="notesBosta" id="notesBosta" rows="2" oninput="enforceMaxLength(this, 150)">{{ $order->notesBosta }}</textarea>


    </div>


    <div data-title="تغير حالة الاوردر" class="col-lg-4 col-12 mb-3">


        <label for="status" class="mb-2">تغير حالة الاوردر</label>


        <select name="status" class="js-example-basic-single w-100">


            <option selected value="{{ $order->status }}"> {{ $order->status }} ( الحالة الحالية ) </option>

            @php
                $status = Get_Next_Status($order->id);
            @endphp

            @foreach ($status as $state)
                @if ($state != $order->status)
                    <option value="{{ $state }}"> {{ $state }} </option>
                @endif
            @endforeach


        </select>


    </div>
    @php
        $data = getOrderData($order->details);
    @endphp

    @if (auth()->user()->role == 'admin')
        <div class="col-lg-2 col-12" title="خصم من العمولة">
            <x-admin.forms.input min="0" id="get" max="{{ $data['comissation'] + $data['ponus'] }}"
                onkeyup="changeComissation()" value="{{ $order->get }}" for="get" lable_title="خصم من العمولة"
                name="get" type="number" placeholder="خصم من العمولة">
            </x-admin.forms.input>
        </div>
    @else
        <div class="col-lg-2 col-12" title="خصم من العمولة">
            <x-admin.forms.input min="0" id="get" onkeyup="changeComissation()"
                value="{{ $order->get }}" for="get" lable_title="خصم من العمولة" name="get" type="number"
                placeholder="خصم من العمولة">
            </x-admin.forms.input>
        </div>
    @endif





    <div class="col-lg-2 col-12" title="زيادة العمولة">
        <x-admin.forms.input min="0" id="take" onkeyup="changeComissation()" value="{{ $order->take }}"
            for="take" lable_title="زيادة العمولة" name="take" type="number" placeholder="زيادة العمولة">
        </x-admin.forms.input>
    </div>



    <input type="hidden" value="{{ $data['comissation'] + $data['ponus'] }}" id="comissation">
    <input type="hidden" value="{{ $data['total'] + $order->delivery_price }}" id="total">



    <div class="col-lg-2 col-12" title="عمولة المسوق">
        <x-admin.forms.input disabled required value="{{ $data['comissation'] + $data['ponus'] }}" for="comissation"
            lable_title="عمولة المسوق" name="comissation" placeholder="">
        </x-admin.forms.input>
    </div>

    <div class="col-lg-2 col-12" title="اجمالي الاوردر">
        <x-admin.forms.input disabled required value="{{ $data['total'] + $order->delivery_price }}" for="total"
            lable_title="اجمالي الاوردر" name="total" placeholder="">
        </x-admin.forms.input>
    </div>

    <div class="col-12 d-flex">
        <div class="form-check">
            <input class="form-check-input radio" type="radio" name="type" id="normal" value="normal"
                checked>
            <label class="form-check-label" for="normal">
                اوردر جديد
            </label>
        </div>
        <div class="form-check mx-2">
            <input class="form-check-input radio" type="radio" name="type" id="exchange" value="exchange">
            <label class="form-check-label" for="exchange">
                اوردر استبدال
            </label>
        </div>
    </div>



    <div data-title="اتمام الاوردر" class="my-3 d-flex flex-row-reverse align-items-center justify-content-between"
        style="text-align: left">


        <x-admin.forms.mainBtn type="button" onclick="validate()" title="حفظ"></x-admin.forms.mainBtn>

        @if ($order->status == 'محاولة تانية')
            <x-admin.forms.buttonLink title="العميل لم يرد" path="/admin/orders/{{ $order->id }}/tryAgian">
            </x-admin.forms.buttonLink>
        @endif


    </div>


</form>
