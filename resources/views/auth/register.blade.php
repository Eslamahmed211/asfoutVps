<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>تسجيل عضويه جديده</title>

    @include('auth/head')



    <style>
        @media(min-width:993px) {
            form {
                width: 60%
            }
        }
    </style>

</head>

<body>


    <form class="row p-lg-2 p-0" action="/register" method="post">

        @csrf

        <div class="text-center">
            <a href="/">
                <img src="https://as4.shop/assets/logo.png" alt="logo" class="logo"></a>
        </div>


        <fieldset class=" col-lg-6 col-12 mt-3" title="الاسم">

            <label for="name" class="mb-2"> اسم المستخدم </label>

            <div @class(['input_div', 'invalid' => $errors->has('name')])>
                <i>
                    <svg class="svg-sm primary-text-color" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </i>
                <input placeholder="اسم المستخدم " name="name" type="text" value="{{ old('name') }}">


            </div>

            @error('name')
                <div class="error">{{ $message }}
                </div>
            @enderror

        </fieldset>

        <fieldset class="col-lg-6 col-12  mt-3" title="الايميل">

            <label for="email" class="mb-2"> البريدالالكتروني </label>

            <div @class(['input_div', 'invalid' => $errors->has('email')])>
                <i>
                    <svg class="svg-sm primary-text-color" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </i>
                <input placeholder="البريدالالكتروني " name="email" type="text" value="{{ old('email') }}">


            </div>

            @error('email')
                <div class="error">{{ $message }}
                </div>
            @enderror

        </fieldset>


        <fieldset class=" col-lg-6 col-12 mt-3" title="رقم التليفون">

            <label for="mobile" class="mb-2"> رقم التليفون </label>

            <div @class(['input_div', 'invalid' => $errors->has('mobile')])>
                <i>
                    <svg class="svg-sm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>

                </i>
                <input placeholder="رقم التليفون " name="mobile" type="tel" value="{{ old('mobile') }}">


            </div>

            @error('mobile')
                <div class="error">{{ $message }}
                </div>
            @enderror

        </fieldset>

        <fieldset class="col-lg-6 col-12  mt-3" title="نوع الحساب">

            <label for="role" class="mb-2"> نوع الحساب </label>

            <div @class(['input_div', 'invalid' => $errors->has('role')])>
                <i>
                    <svg class="svg-sm primary-text-color" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </i>

                <select name="role">
                    <option value="trader">تاجر</option>
                    <option selected value="user">مسوق</option>
                </select>


            </div>

            @error('role')
                <div class="error">{{ $message }}
                </div>
            @enderror

        </fieldset>





        <fieldset class="mt-3 col-lg-6 col-12" title=" العنوان ">

            <label for="address" class="mb-2">العنوان</label>


            <div @class(['input_div', 'invalid' => $errors->has('address')])>
                <i>
                    <svg class="svg-sm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>

                </i>

                <input name="address" type="text" placeholder="العنوان" value="{{ old('address') }}">

            </div>

            @error('address')
                <div class="error">{{ $message }}
                </div>
            @enderror




        </fieldset>


        <fieldset class="mt-3 col-lg-6 col-12" title=" المحافظة ">

            <label for="city" class="mb-2">المحافظة</label>


            <div @class(['input_div', 'invalid' => $errors->has('city')])>
                <i>
                    <svg class="svg-sm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </i>

                <input name="city" type="text" placeholder="المحافظة" value="{{ old('city') }}">

            </div>

            @error('city')
                <div class="error">{{ $message }}
                </div>
            @enderror




        </fieldset>


        <fieldset class="mt-3 col-lg-6 col-12" title="الرقم السرى">

            <label for="password" class="mb-2">كلمة المرور</label>


            <div @class(['input_div', 'invalid' => $errors->has('password')])>
                <i>
                    <svg class="svg-sm primary-text-color" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                        </path>
                    </svg>
                </i>

                <input name="password" type="password" placeholder="كلمة المرور" value="{{ old('password') }}">

            </div>

            @error('password')
                <div class="error">{{ $message }}
                </div>
            @enderror




        </fieldset>



        <fieldset class="mt-3 col-lg-6 col-12" title="الرقم السرى">

            <label for="password_confirmation" class="mb-2"> تاكيد كلمة المرور </label>


            <div @class([
                'input_div',
                'invalid' => $errors->has('password_confirmation'),
            ])>
                <i>
                    <svg class="svg-sm primary-text-color" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                        </path>
                    </svg>
                </i>

                <input name="password_confirmation" type="password" placeholder=" تاكيد كلمة المرور  "
                    value="{{ old('password_confirmation') }}">

            </div>

            @error('password_confirmation')
                <div class="error">{{ $message }}
                </div>
            @enderror




        </fieldset>






        <div class="my-2">


            <p class="text-xs">
                <span> تمتلك حسابا ؟</span>
                <a href="/login" class="font-bold primary-text-color">تسجيل الدخول</a>
            </p>






        </div>

        <div class="button">
            <button>تسجيل دخول</button>
        </div>

    </form>



    @include('auth/script')



</body>

</html>
