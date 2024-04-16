@props(['lable_title', 'for', 'name', 'placeholder', 'type', 'notRequired'])


<div class="mb-3">
    <div class="d-flex gap-2 align-items-center ">
        @isset($lable_title)
            @isset($icon)
                {{ $icon }}
            @endisset
            <label for="{{ $name }}" class="mb-2">{{ $lable_title }} @isset($notRequired)
                    <span style="font-weight: bold ; font-size: 12px ;color:#aaa">( اختياري)</span>
                @endisset
            </label>
        @endisset
        @isset($info)
            {{ $info }}
        @endisset

    </div>

    <input name="{{ $name }}" type="{{ $type ?? 'text' }}"
        {{ $attributes->merge(['value' => old("$name")])->class(['invalid' => $errors->has("$name")]) }}
        placeholder="{{ $placeholder ?? '' }}">

    @error("$name")
        <div class="error">{{ $message }}</div>
    @enderror



</div>
