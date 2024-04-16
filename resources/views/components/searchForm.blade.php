<div id="search" class="search">
    <div class="title">البحث المتقدم <svg style="cursor: pointer" id="searchBtnClose" data-v-95d07650=""
            xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="feather feather-x ">
            <line data-v-95d07650="" x1="18" y1="6" x2="6" y2="18"></line>
            <line data-v-95d07650="" x1="6" y1="6" x2="18" y2="18"></line>
        </svg> </div>
    <form {{ $attributes->merge(['method' => 'get']) }} class="body" autocomplete="off">

        <input type="hidden" value="" name="type" id="type">


        {{ $slot }}


        <div class="d-flex justify-content-between align-items-center">
            <x-admin.forms.mainBtn onclick="$('#type').val('search')" type="submit"
                title="بحث"></x-admin.forms.mainBtn>



            <div>

                @isset($withExel)
                    <x-admin.forms.mainBtn style="border-color: rgb(16 185 129 /1) ; color: rgb(16 185 129 /1)"
                        onclick="$('#type').val('excel')" type="submit" title="اكسيل"
                        class="rest"></x-admin.forms.mainBtn>
                @endisset
                
                <x-admin.forms.mainBtn type="reset" id="resetBtn" title="اعادة تعيين"
                    class="rest"></x-admin.forms.mainBtn>
            </div>
        </div>

    </form>
</div>
