@props(['notification' , 'customMessage'])
<div class="form-element inlined switch intable mb-2">
    <div class="flex  align-items-center">
        <label>

            <div class="toggle-container lg">
                <input name="notification[]" @checked(ON($notification)) id="{{ $notification }}" value="{{ $notification }}" type="checkbox"
                    class="sr-only">
                <div class="switch-bg"></div>
                <div class="dot"></div>
            </div>

            @if (!isset($customMessage))
                <label class="m-0 mx-1" for="{{ $notification }}">اشعارات طلبات {{ $notification }} </label>
            @else
                <label class="m-0 mx-1" for="{{ $notification }}"> {{$customMessage}} </label>
            @endif


        </label>
    </div>
</div>
