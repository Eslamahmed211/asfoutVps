<form class="row" action="{{ url('/profile/password') }}" method="post">

    @csrf
    @method('put')


    <div class="col-lg-6 col-12" title=" كلمة السر القديمة">
        <x-admin.forms.input class="checkThis" type="password" notRequired for="passwordOld"
            lable_title=" كلمة السر القديمة" name="passwordOld" placeholder=" كلمة السر القديمة">
        </x-admin.forms.input>
    </div>

    <div class="col-lg-6 col-12" title="كلمة السر الجديدة">
        <x-admin.forms.input class="checkThis" type="password" notRequired for="password"
            lable_title=" كلمة السر الجديدة" name="password" placeholder=" كلمة السر الجديدة">
        </x-admin.forms.input>
    </div>

    <div class="col-lg-6 col-12" title="تأكيد كلمة السر الجديدة">
        <x-admin.forms.input class="checkThis" type="password" notRequired for="password_confirmation"
            lable_title="تأكيد كلمة السر الجديدة" name="password_confirmation" placeholder="تأكيد كلمة السر الجديدة">
        </x-admin.forms.input>
    </div>





    <div class="col-12">
        <x-admin.forms.mainBtn type="submit" title="تغير كلمة السر" class="mt-3"></x-admin.forms.mainBtn>
    </div>




</form>
