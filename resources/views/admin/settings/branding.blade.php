<form class="row " action="/admin/settings/branding" method="post" enctype="multipart/form-data">

    @csrf
    @method('put')

    <div class="col-lg-6 col-12">
        <x-admin.forms.input value="{{ settings('website_title') }}" notRequired for="website_title"
            lable_title="اسم الموقع" name="website_title" placeholder="اسم الموقع">
        </x-admin.forms.input>
    </div>

    <div class="col-lg-6 col-12">
        <x-admin.forms.input value="{{ settings('website_dis') }}" notRequired class="checkThis" for="website_dis"
            lable_title="وصف الموقع" name="website_dis" placeholder="وصف الموقع">
        </x-admin.forms.input>
    </div>


    <div class="col-lg-6 col-12">
        <x-admin.forms.input type="file" notRequired accept="image/*" for="website_logo" lable_title="لوجو الموقع"
            name="website_logo" placeholder="لوجو الموقع">
        </x-admin.forms.input>
    </div>

    <div class="col-lg-6 col-12">
        <x-admin.forms.input type="file" notRequired for="website_fav" lable_title="ايقونة الموقع" name="website_fav"
            placeholder="ايقونة الموقع">
        </x-admin.forms.input>
    </div>




    <div class="col-12">
        <x-admin.forms.mainBtn type="submit" title="تعديل العلامة التجارية" class="mt-3"></x-admin.forms.mainBtn>
    </div>




</form>
