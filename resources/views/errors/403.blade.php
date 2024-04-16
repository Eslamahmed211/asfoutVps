<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>403</title>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/adminstyle.css?ver=2.1') }}">

    <Style>
        body {
            --tw-text-opacity: 1;
            color: var(--mainColor);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
        }

        .error-code {
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            font-size: 8rem;
            line-height: 1;
            font-weight: 800;
        }

        .error-container {
            margin: auto;
            margin-top: 2rem;
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-left: 1.25rem;
            padding-right: 1.25rem;
        }

        .error-icon {
            height: 9rem;
            width: 9rem;
        }

        .error-title {
            font-size: 2rem;
            line-height: 2rem;
            font-weight: 600;
            font-family: Alexandria, sans-serif;
            color: black;
            text-align: center
        }

        .error-description {
            margin-top: 1rem;
            margin-bottom: 2rem;
            color: black
        }

        .xbutton {
            font-weight: 700;
            padding: 0.5rem 1rem;

            font-size: 14px;
        }

        @media (max-width:768px) {
            .error-code {

                font-size: 5rem;

            }

            .error-icon {
                width: 7rem;
                height: 7rem;
            }

            .error-title {
                font-size: 1.5rem
            }
        }
    </Style>
</head>

<body>
    <div class='error-container max-w-md text-center'>
        <h2 class="error-code">
            <svg  class="error-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
              </svg>

            403
        </h2>

        <p class="error-title"> صلاحيات مفقودة </p>

        <p class="error-description"> انت لا لا يوجد لديك صلاحية لعمل هذا الاجراء </p>

        <div style="display: flex;gap:20px">

            <x-link title="الصفحة الرئيسية " path="/home"></x-link>
            {{-- <x-link title="رجوع " path="{{ URL::previous() }}"></x-link> --}}

        </div>
    </div>
</body>

</html>
