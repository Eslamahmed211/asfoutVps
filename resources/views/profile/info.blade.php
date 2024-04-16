<form class="row" action="{{ url('/profile/info') }}" method="post" enctype="multipart/form-data">

    @csrf
    @method('put')

    <div class="col-lg-6 col-12">
        <x-admin.forms.input value="{{ auth()->user()->name }}" class="checkThis" for="name" lable_title="اسمك ثنائي"
            name="name" placeholder="اسمك ثنائي">
        </x-admin.forms.input>
    </div>

    <div class="col-lg-6 col-12">
        <x-admin.forms.input disabled type="email" value="{{ auth()->user()->email }}" for="email"
            lable_title="البريد الالكتروني" name="email" placeholder="البريد الالكتروني">
        </x-admin.forms.input>
    </div>

    <div class="col-lg-6 col-12">
        <x-admin.forms.input value="{{ auth()->user()->mobile }}" class="checkThis" for="mobile"
            lable_title="رقم التليفون" name="mobile" placeholder="رقم التليفون">
        </x-admin.forms.input>
    </div>

    <div class="col-lg-6 col-12">
        <x-admin.forms.input value="{{ auth()->user()->address }}" class="checkThis" for="address"
            lable_title="العنوان" name="address" placeholder="العنوان">
        </x-admin.forms.input>
    </div>

    <div class="col-lg-6 col-12">
        <x-admin.forms.input value="{{ auth()->user()->city }}" class="checkThis" for="city" lable_title="المحافظة"
            name="city" placeholder="المحافظة">
        </x-admin.forms.input>
    </div>




    <div class="col-12">
        <x-admin.forms.mainBtn type="submit" title="تعديل  بيانات الحساب" class="mt-3"></x-admin.forms.mainBtn>
    </div>




</form>
