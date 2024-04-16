<div class="contnet-title"> اعدادات الاشعارات</div>

<form class="row bg-white  py-2 px-2 rounded" action="{{ url('users/notification_settings') }}" method="post"
    enctype="multipart/form-data">



    <x-notification notification="قيد المراجعة"></x-notification>
    <x-notification notification="تم المراجعة"></x-notification>
    <x-notification notification="محاولة تانية"></x-notification>
    <x-notification notification="جاري التجهيز للشحن"></x-notification>
    <x-notification notification="تم ارسال الشحن"></x-notification>
    <x-notification notification="تم التوصيل"></x-notification>
    <x-notification notification="قيد الانتظار"></x-notification>
    <x-notification notification="تم الالغاء"></x-notification>
    <x-notification notification="فشل التوصيل"></x-notification>
    <x-notification notification="مكتمل"></x-notification>

    <x-notification customMessage="اشعارت العمولات" notification="العمولات"></x-notification>


    @csrf
    @method('put')

    <div class="col-12">
        <x-admin.forms.mainBtn type="submit" icon="update" title="تعديل اﻻشعارات"
            class="mt-3"></x-admin.forms.mainBtn>
    </div>

</form>
