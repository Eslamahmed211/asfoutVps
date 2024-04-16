<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>استعادة كلمة السر</title>



    @include('auth/head')




</head>

<body>


    <form action="{{ url('reset_password') }}" method="post">

        @csrf

        <div class="text-center">
            <a href="/">
                <img src="https://as4.shop/assets/logo.png" alt="logo" class="logo"></a>
        </div>



        <fieldset class="mt-3" title="البريد الالكتروني">

            <label for="email" class="mb-2"> البريد الالكتروني </label>

            <div @class(['input_div', 'invalid' => $errors->has('email')])>
                <i>
                    <svg class="svg-sm primary-text-color" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </i>
                <input placeholder="البريد الالكتروني" name="email" type="email" value="{{ old('email') }}">


            </div>

            @error('email')
                <div class="error">{{ $message }}
                </div>
            @enderror

        </fieldset>

        <div class="button">
            <button>ارسال الرمز</button>
        </div>

    </form>




    @include('auth/script')


</body>

</html>
